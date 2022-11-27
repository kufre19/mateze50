<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait MakeMessages {

    


    public function make_main_menu_message($to="",$text="")
    {
       $menu_raw = Config::get("menus.0");
        $command = ["command"=>"choose from main menu","command_value"=>$menu_raw];
        $this->add_command_to_session($command);
        $main_menu_string = Config::get("messages.main_menu");
        return $this->make_text_message("",$main_menu_string);
       

    }

    public function make_text_message($to="",$text="",$preview_url=false)
    {
        if($to == "")
        {
            $to = $this->userphone;
        }
        $message = [
            "messaging_product"=> "whatsapp",
            "recipient_type"=>"individual",
            "to"=> $to ,
            "type"=> "text",
            "text"=> [
                "preview_url"=> $preview_url,
                "body"=> $text
            ]

        ];

        return json_encode($message);

    }

    public function send_greetings_message()
    {
        $to = $this->userphone;
        $app_name = env("APP_NAME");

        $text = <<<MSG
        Hello {$this->username}, Greetings from {$app_name}.  
        How can we help you today?. 
        MSG;
        $this->send_post_curl($this->make_text_message($to,$text));
        $this->send_post_curl($this->make_main_menu_message($to));

        die;

    }

    public function send_end_button()
    {
        $button = [
            [
                "type" => "reply",
                "reply" => [
                    "id" => "menu",
                    "title" => "Return to menu"
                ]
            ],
            [
                "type" => "reply",
                "reply" => [
                    "id" => "customer-service",
                    "title" => "Chat with member"
                ]
            ],


        ];
        $text = "What do you want to do now";
        $data = $this->make_button_message($this->userphone,"Select option",$text,$button);
        $this->send_post_curl($data);
        
    }
    public function make_button_message($to,$header_text,$body_text,$buttons,$preview_url=false)
    {
        $message = [
            "messaging_product"=> "whatsapp",
            "recipient_type"=>"individual",
            "to"=> $to ,
            "type"=> "interactive",
            "interactive"=> [
                "type"=> "button",
                "header"=> [
                    "type"=> "text",
                    "text"=> $header_text
                ],
                "body"=> [
                    "text"=> $body_text
                ],
                "action"=> [
                    "buttons"=>$buttons
                    
                    
                ]
            ]

        ];

        return json_encode($message);

    }

    public function send_post_curl($post_data)
    {
        $token = env("WB_TOKEN");
        $url = env("WB_MESSAGE_URL");

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
            "Authorization: Bearer {$token}"
        ),
        ));

        $response = curl_exec($curl);
        http_response_code(200);
        echo $response;

        // curl_close($curl);

    }
    
}