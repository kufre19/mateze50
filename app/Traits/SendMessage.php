<?php

namespace App\Traits;


trait SendMessage
{
    use MakeMessages;

    // public function send_first_timer_message()
    // {
    //     $to = $this->userphone;
    //     $message = <<<MSG
    //     Hello my name is Aura. Lovely to meet you {$this->username}. I want to remind you, today opens up many new possibilities for you.  Let's take the journey.
    //     Go ahead, have a look at our main menu of Products and services.
    //     MSG;

    //     $this->send_post_curl($this->make_text_message($to,$message));
    //     $this->send_post_curl($this->make_main_menu_message($to));       
    //     die;


    // }

   

  




    public function send_text_message($text, $to = "")
    {
        if ($to == "") {
            $to = $this->userphone;
        }
        $this->send_post_curl($this->make_text_message($to, $text));
        // return response("",200);

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
        echo $response;
        http_response_code(200);


        // curl_close($curl);

    }
}
