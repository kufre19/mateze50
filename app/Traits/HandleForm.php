<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait HandleForm {

    public $form_current_step_key;
    public $form_steps;
    public $form_conversations;
    public $form_current_step_to_run;
    public $form_user_response;

    public function run_form_index($user_message="")
    {
        $this->form_current_step_key = $this->user_session_data['form']['current_step'];
        $this->form_steps = $this->user_session_data['form']['steps'];
        // dd( $this->form_steps,$this->form_current_step_key);
        $this->form_conversations =  $this->user_session_data['conversation'];
        $this->form_user_response = $user_message ?? "";

        $this->form_current_step_to_run = $this->form_steps[$this->form_current_step_key];
        $step_action = $this->form_current_step_to_run['action_type'];
        $step_value = $this->form_current_step_to_run['value'];

        $this->$step_action($step_value);

    }


    public function add_form_to_session($form_name)
    {
        $form =  Config::get("forms.".$form_name);
        $data =  [
            "current_step" => 0,
            "steps"=>$form

        ];

        $this->add_object_to_session('form',$data);
        $this->add_object_to_session('form_active',"1");


        
    }

    public function increace_form_current_step()
    {
      
        $this->user_session_data['form']['current_step']++;
        return   $this->update_session($this->user_session_data);
        // $this->refresh_attributes();
    }

    public function go_to_form_next_step($step_value)
    {
        $this->increace_form_current_step();
        $this->run_form_index($this->form_user_response);
    }

    public function ask_form_question($step_value)
    {
        $this->send_text_message($step_value);
        $this->increace_form_current_step();
        die;
    }
    public function say_form_text($step_value)
    {
        $message = "";
        if(isset( $this->form_current_step_to_run['add_global_variable']))
        {
            $replacement = [];
            $to_be_replaced = [];
            $global_var_keys = $this->form_current_step_to_run['add_global_variable'];
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
        $this->increace_form_current_step();
        $this->run_form_index($this->form_user_response);
    }

    public function store_form_response($step_value)
    {
        // dd($this->form_user_response);
        $user_response = "";
        if (isset($this->form_current_step_to_run['check_menu']))
            {
                $menu = Config::get("menus.".$this->form_current_step_to_run['check_menu']);
                $user_response = $menu[$this->form_user_response];
                
            }else{
                $user_response = $this->form_user_response;   
            }
        if ($step_value != "") {
            array_push($this->user_session_data['conversation'], ["q" => $step_value, "ans" =>  $user_response]);
        }
        // dd( $this->form_current_step_to_run);
        // print_r($this->form_current_step_to_run) . "<br>";

        

        if (isset($this->form_current_step_to_run['set_global_variable'])) {
            // dd($this->form_current_step_to_run);

            if (isset($this->form_current_step_to_run['global_value_as'])) {

                $value_as = $this->form_current_step_to_run['global_value_as'];
                $this->set_global_variable_form($this->form_current_step_to_run['set_global_variable'], $value_as);
            }elseif ($user_response != "") {
                $value_as = $user_response;
                $this->set_global_variable_form($this->form_current_step_to_run['set_global_variable'], $value_as);
            } else {
                $this->set_global_variable_form($this->form_current_step_to_run['set_global_variable']);
            }
        }
        $this->increace_current_step();

        $this->run_form_index($this->form_user_response);
    }

    public function set_global_variable_form($key, $value_as = "")
    {
        if (!isset($this->user_session_data['global_variables'])) {
            $this->user_session_data['global_variables'] = [];
            $this->update_session($this->user_session_data);
            $this->refresh_attributes();
        }
        if ($value_as == "") {
            $data =  $this->form_user_response;
        } else {

            $data = $value_as;
        }
        $this->user_session_data['global_variables'][$key] = $data;
    }


    public function end_form($step_value)
    {
        $command = ["command"=>"run steps","command_value"=>""];
        $this->add_object_to_session('form_active',"0");
        $this->add_object_to_session('form',"");
        $this->add_command_to_session($command);
        $this->make_step_active(1);
        $this->go_through_steps($step_value);

    }







}