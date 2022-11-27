<?php



return [
    "1"=>[ 
        [
            "action_type"=>"show_options",
            "value"=> <<<M1T
            what type of accessory do you want?
            1. code a new key
            2. multimedia system
            3. supercharger
            4. other accessory
            M1T,
            
        ],

        [
            "action_type"=>"get_option_selected",
            "value"=> [
                "1" => "code a new key",
                "2" => "multimedia system",
                "3" => "supercharger",
                "4" => "other accessory",
                "alternative_opt" => [
                    "option" => "4",
                    "do_alt" => [
                        "action_type" => "ask_with_condition",
                        "value" => "What do you want to install?"
                    ]
                ],
                "original_question" => "What type of accessory do you want to change/ add to you car?"
        
            ],
        ],
        [
            "action_type"=>"store_response",
            "value"=> "What type of accessory do you want to change/ add to you car: ",
            "set_global_variable"=>"accessory",
            "check_menu"=>"01"
        ],
        [
            "action_type"=>"ask",
            "value"=><<<M2T
            Have you already bought the accessory?
            
            1. Yes
            2. No
            M2T
        ],
        [
            "action_type"=>"get_selected_condition",
            "value"=> [
                "options"=>[ 
                    "1"=>"yes",
                    "2"=>"no"
                ],
                "original_question"=>"Have you already bought the accessory?",
                "conditions"=>[
                    "1"=>["action_type"=>"load_new_steps","value"=>"1_1"],
                    "2"=>["action_type"=>"load_new_steps","value"=>"1_2"]

                ]
               
            ]
        ]
            ],//end of step 1

    "1_1"=>[
        [
            "action_type"=>"say",
            "value"=><<<M3T
             perfect you want us to help you install and program the accesory 
            M3T
        ],
        [
            "action_type"=>"ask",
            "value"=><<<M4T
            What is the reference?
            M4T
        ],

        [
            "action_type"=>"store_response",
            "value"=>"What is the reference?",
            "set_global_variable"=>"reference"
        ],
        
        [
            "action_type"=>"say",
            "value"=><<<M5T
            Ok ready, we note your need to program the [Accessory] [reference]
            M5T,
            "add_global_variable"=>["accessory"=>"[Accessory]","reference"=>"[reference]"]
        ],

        [
            "action_type"=>"load_form",
            "value"=> "form2",
        ],
        [
            "action_type"=>"say",
            "value"=><<<M5T
            You want to program the [Accessory] for a [Make] [Model] of [Year]
            M5T,
            "add_global_variable"=>[
                "accessory"=>"[Accessory]",
                "car_make"=>"[Make]",
                "car_model"=>"[Model]",
                "car_year"=>"[Year]"
                ]
        ],
        [
            "action_type"=>"load_form",
            "value"=> "form1",
        ],
        [
            "action_type"=>"say",
            "value"=><<<M5T
            Thanks [Name] for all the information. A member of our team will contact you very soon at [phone] to schedule an appointment.
            M5T,
            "add_global_variable"=>[
                "name"=>"[Name]",
                "phone"=>"[phone]",
                ]
        ],
        [
            "action_type"=>"end_step",
            "value"=>""
        ]

       



    ],//end of step 1.1


    "1_2"=>[

        ]//end of step 1.2

];