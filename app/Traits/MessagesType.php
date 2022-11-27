<?php

namespace App\Traits;


/* 
Button and menu code IDs and their meaning

starting with 0 = main menu item
starting with 1 = product item



*/

trait MessagesType {

    

  

    public function make_text_message($to,$text,$preview_url=false)
    {
       
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

  

    public function make_menu_message($to,$menus,$text="",$button_name="")
    {

        $message = [
            "messaging_product"=> "whatsapp",
            "recipient_type"=>"individual",
            "to"=> $to ,
            "type"=> "interactive",
            "interactive"=> [
                "type"=> "list",
                "header"=> [
                    "type"=> "text",
                    "text"=> $text
                ],
                "body"=> [
                    "text"=> "select from the list"
                ],
                "action"=> [
                    "button"=> $button_name,
                    "sections"=> [
                        [
                            "title"=> "Select An Item",
                            "rows"=> $menus
                        ],
                       
                    ]
                ]
            ]

        ];

        return json_encode($message);

    }

    public function make_template_message($parameters,$to="",$template_name="")
    {
        if ($to == "") {
            $to = $this->userphone;
        }
        if ($template_name == "") {
            $template_name = "order_update";
        }

        $message = [

            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $template_name,
                "language"=> [
                    "code"=> "en",
                    "policy"=> "deterministic"
                ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => $parameters
                    ]
                ]
            ]


        ];


        return json_encode($message);
    }

  

   

  

   
    
}