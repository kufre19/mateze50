<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait HandleSteps
{

    use  SendMessage, HandleForm;

    public $current_step_key;
    public $steps;
    public $conversations;
    public $current_step_to_run;
    public $user_response;

    /* 
    templates
    []

    // the show_option action type 
    will display a list of options plus an alternative if needed to be 
    followed by collecting the responsef from user// 
    
    */

    public function go_through_steps($user_message = "")
    {
        // this method will go through the steps stored in session wuth key "steps"
       
        $this->current_step_key = $this->user_session_data['current_step'];
        $this->steps = $this->user_session_data['steps'];
        $this->conversations =  $this->user_session_data['conversation'];
        $this->user_response = $user_message ?? "";

        $this->current_step_to_run = $this->steps[$this->current_step_key];
        $step_action = $this->current_step_to_run['action_type'];
        $step_value = $this->current_step_to_run['value'];

        $this->$step_action($step_value);
    }

    public function show_options($step_value)
    {
        // this should display options to user and increace to the next step

        $this->ask($step_value);
    }

    public function say($step_value)
    {
        $message = "";
        if(isset( $this->current_step_to_run['add_global_variable']))
        {
            $replacement = [];
            $to_be_replaced = [];
            $global_var_keys = $this->current_step_to_run['add_global_variable'];
            foreach( $global_var_keys as $key => $value)
            {
                array_push($to_be_replaced,$value);
                array_push($replacement,$this->user_session_data['global_variables'][$key]);
            }
            $message  = str_replace($to_be_replaced,$replacement,$step_value);
        }else{
            $message = $step_value;
        }
        $this->send_text_message($message);
        $this->increace_current_step();
        $this->go_through_steps($this->user_response);
    }

    public function get_option_selected($step_value)
    {
        // this is to get the value of what's been selected from a menu
        if (array_key_exists($this->user_response, $step_value)) {
            if (isset($step_value['alternative_opt'])) {
                if ($this->user_response == $step_value['alternative_opt']['option']) {
                    $this->do_alt($step_value['alternative_opt']);
                }
            }




            $this->increace_current_step();
            $this->go_through_steps($this->user_response);
        } else {
            $this->send_text_message("please select from the options given");
            $this->reduce_current_step();
            $this->go_through_steps($this->user_response);
        }
    }




    public function ask_with_condition($step_value)
    {
        // this is to send a question and die without increacing step
        $this->send_text_message($step_value);
        die;
    }

    public function get_selected_condition($step_value)
    {

        if (array_key_exists($this->user_response, $step_value['options'])) {

            if (isset($step_value['conditions'])) {
       
              
                $this->run_condition_action($step_value['conditions'][$this->user_response]);
            }
        } else {
        

            $this->send_text_message("please select from the options given");
            $this->reduce_current_step();
            $this->go_through_steps($this->user_response);
        }
    }

    public function load_new_steps($step_value)
    {
      
        $step = Config::get("steps." . $step_value);
    //    dd($step, $this->user_session_data['steps']);
        $this->add_steps_to_session($step);
        // $this->refresh_attributes();
        $this->increace_current_step();
        $this->go_through_steps($this->user_response);
    }



    public function ask($step_value)
    {
        $this->send_text_message($step_value);
        $this->increace_current_step();
        die;
    }



    public function load_form($step_value)
    {
        $command = ["command"=>"run form","command_value"=>""];
        $this->add_command_to_session($command);
        $this->add_form_to_session($step_value);
        $this->run_form_index($step_value);
       
    }

    public function store_response($step_value)
    {
        // dd($this->user_response);
        $user_response = "";
        if (isset($this->current_step_to_run['check_menu']))
            {
                $menu = Config::get("menus.".$this->current_step_to_run['check_menu']);
                $user_response = $menu[$this->user_response];
                
            }else{
                $user_response = $this->user_response;   
            }
        if ($step_value != "") {
            array_push($this->user_session_data['conversation'], ["q" => $step_value, "ans" =>  $user_response]);
        }
        // dd( $this->current_step_to_run);
        // print_r($this->current_step_to_run) . "<br>";

        

        if (isset($this->current_step_to_run['set_global_variable'])) {
            // dd($this->current_step_to_run);

            if (isset($this->current_step_to_run['global_value_as'])) {

                $value_as = $this->current_step_to_run['global_value_as'];
                $this->set_global_variable($this->current_step_to_run['set_global_variable'], $value_as);
            }elseif ($user_response != "") {
                $value_as = $user_response;
                $this->set_global_variable($this->current_step_to_run['set_global_variable'], $value_as);
            } else {
                $this->set_global_variable($this->current_step_to_run['set_global_variable']);
            }
        }
        $this->increace_current_step();

        $this->go_through_steps($this->user_response);
    }

    public function set_global_variable($key, $value_as = "")
    {
        if (!isset($this->user_session_data['global_variables'])) {
            $this->user_session_data['global_variables'] = [];
            $this->update_session($this->user_session_data);
            $this->refresh_attributes();
        }
        if ($value_as == "") {
            $data =  $this->user_response;
        } else {

            $data = $value_as;
        }
        $this->user_session_data['global_variables'][$key] = $data;
    }

    public function get_global_variable($keys)
    {
        $data = [];
        foreach ($keys as $key => $value) {
            array_push($data, $this->user_session_data['global_variables'][$value]);
        }
        return $data;
    }

    // public function check_condition($step_value)
    // {

    //     if(!array_key_exists($this->user_response,$step_value))
    //     {
    //         $this->send_text_message("please select from the options given");
    //         if(array_key_exists("s_a",$step_value))
    //         {
    //             $this->run_condition_action($step_value['s_a']);

    //         }else{
    //             $this->reduce_current_step();
    //             $this->go_through_steps($this->user_response);
    //         }

    //     }else{
    //         $action_to_do = $step_value[$this->user_response];
    //     // print_r($action_to_do) . "<br>";

    //         $this->run_condition_action($action_to_do);

    //     }


    // }

    public function run_condition_action($action)
    {

        $this->current_step_to_run = $action;
        $step_action = $action['action_type'];
        $step_value = $action['value'];
        $this->$step_action($step_value);
    }

    public function go_to_next_step($step_value)
    {
        $this->increace_current_step();
        $this->go_through_steps($this->user_response);
    }
    public function increace_current_step()
    {
        if(isset( $this->user_session_data['form_active']))
        {
            if($this->user_session_data['form_active'] ==1 )
            {
                $this->user_session_data['form']['current_step']++;
                return   $this->update_session($this->user_session_data);

            }
        }
        $this->user_session_data['current_step']++;
        $this->update_session($this->user_session_data);
        // $this->refresh_attributes();
    }
    public function reduce_current_step()
    {
        if(isset( $this->user_session_data['form_active']))
        {
            if($this->user_session_data['form_active'] ==1 )
            {
                $this->user_session_data['form']['current_step']--;
                return   $this->update_session($this->user_session_data);
    
            }
        }
       
        $this->user_session_data['current_step']--;
        $this->update_session($this->user_session_data);
        // $this->refresh_attributes();
    }

    public function refresh_attributes()
    {
        $this->current_step_key = $this->user_session_data['current_step'];
        $this->steps = $this->user_session_data['steps'];
        $this->conversations =  $this->user_session_data['conversation'];

        $this->current_step_to_run = $this->steps[$this->current_step_key];
        $step_action = $this->current_step_to_run['action_type'];
        $step_value = $this->current_step_to_run['value'];
    }


    public function do_alt($alt_step)
    {
        $step_action = $alt_step['action_type'];
        $step_value = $alt_step['value'];
        $this->increace_current_step();
        $this->$step_action($step_value);
    }

    public function end_step()
    {

        $collected_info = [];
        $conversation = [];
        $webhook_data = json_encode(["conversation"=>$conversation,"collected_info"=>$collected_info]) ;
        $this->send_end_button();

    }

    public function send_to_webhook($post_data)
    {
        // $token = env("WB_TOKEN");
        $url = env("WEBHOOK_URL");

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            // "Authorization: Bearer {$token}"
        ),
        ));

        $response = curl_exec($curl);
        http_response_code(200);
        echo $response;

        // curl_close($curl);

    }
}
