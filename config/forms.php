<?php


return [

    "form1"=>[
        [
            "action_type"=>"say_form_text",
            "value"=><<<M1T
            Thanks for your reply. Now we need a couple of your details to be able to assist you as soon as possible:
            M1T
        ],
        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            What her name?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"What her name?",
            "set_global_variable"=>"name"

        ],
       

        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            What is your last name?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"What is your last name?",
            "set_global_variable"=>"last_name"

        ],
       
        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            In wich number we can contact you?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"In wich number we can contact you?",
            "set_global_variable"=>"phone"

        ],
       
    
        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            What is your e-mail?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"What is your e-mail?",
            "set_global_variable"=>"email"

        ],
        
        [
            "action_type"=>"end_form",
            "value"=> "",
        ]

    ],

    "form2"=>[
        [
            "action_type"=>"say_form_text",
            "value"=><<<M1T
            Thanks for your reply. We talk a little about his car now:
            M1T
        ],
        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            Make of your car?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"Make of your car?",
            "set_global_variable"=>"car_make"
        ],

        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            Model ?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"Model ?",
            "set_global_variable"=>"car_model"

        ],

        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            Again?
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"  Again?",
            "set_global_variable"=>"car_year"
        ],

        [
            "action_type"=>"ask_form_question",
            "value"=><<<M4T
            VIN of your vehicle (registered in the vehicle registry)
            M4T
        ],
        [
            "action_type"=>"store_form_response",
            "value"=>"VIN of your vehicle (registered in the vehicle registry)",
            "set_global_variable"=>"car_vin"


        ],
        
        [
            "action_type"=>"end_form",
            "value"=> "",
        ]


    ]

];