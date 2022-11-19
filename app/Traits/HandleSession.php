<?php

namespace App\Traits;
use App\Models\Session;


trait HandleSession {

    /* 
    //"choose from menu" this command here tells the bot
     that the main menu is sent and the next response has 
     to be a menu selection//

     //the "conversation" key in the session json data 
     represents the questions asked by the bot and the response 
     from user all stored in bits of array   //

     // the "steps" key in the session json data 
     represents the steps the bot will have to go through to complete a menu item selected   //
    
    
    
    */


   

    
    public function start_new_session()
    {
        $data = ["is_step_active"=>0,"conversation"=>[],"steps"=>[]];
        $json = json_encode($data);
        $model = new Session();
        $model->user_id = $this->userphone;
        $model->session_data = $json;
        $model->expired = time() + 7200;
        $model->save();
    }
    public function fetch_user_session()
    {
        $model = new Session();
        $fetch = $model->where('user_id', $this->userphone)->first();
        $this->user_session_data = json_decode($fetch->session_data, true);
    }


    public function did_session_expired()
    {
        $model = new Session();
        $fetch = $model->select('expired')->where('user_id', $this->userphone)->first();

        if (!$fetch) {
            $this->user_session_status = 0;
            return $this->start_new_session();
        } elseif ($fetch->expired < time()) {
            $this->update_session();
            $this->user_session_status = 2;
            return true;
        } else {
            $this->user_session_status = 1;
        }
    }


    public function update_session($data = null)
    {
        if ($data == null) {
            $data = ["is_step_active"=>0,"conversation"=>[],"steps"=>[]];
            $data = json_encode($data);
        } else {
            $data = json_encode($data);
        }

        $model = new Session();
        $model->where('user_id', $this->userphone)
            ->update([
                'session_data' => $data,
                'expired' => time() + 7200
            ]);
            
        $this->fetch_user_session();
    }


    public function add_steps_to_session(array $data)
    {
        foreach ($data as $step) {
            array_push($this->user_session_data['steps'],$step);
        }
        $this->update_session($this->user_session_data);


    }

    public function save_new_conversation($data)
    {
        array_push($this->user_session_data['conversation'],$data);
        $this->update_session($this->user_session_data);
        
    }

    public function add_command_to_session($data = null)
    {
       
        $this->user_session_data['active_command'] = $data;
        $this->update_session($this->user_session_data);
    }

    public function clear_command_from_session()
    {
        $this->user_session_data['active_command'] = array();
        $this->update_session($this->user_session_data);

    }

    public function handle_session_command($message)
    {
        $data = $this->user_session_data['active_command'];
        $command = $data['command'];
        $command_value = $data['command_value'];

        if($command == "choose from menu")
        {
            //call action to know what was chosed from the menu
            // dd("user must choose from menu",$message);
            if(array_key_exists($message,$command_value))
            {
                $conversation = ["q"=>$command_value["q"],"ans"=>$command_value[$message]];
                $this->save_new_conversation($conversation);
                $command = ["command"=>"run steps","command_value"=>""];
                // dd($this->user_session_data);
            }
        }
    }

   
}