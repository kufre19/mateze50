<?php

use Illuminate\Support\Facades\Config;

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
            "value"=> Config::get("menus.01"),
        ],
        [
            "action_type"=>"say",
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
                "q"=>"Have you already bought the accessory?"
               
            ]
        ]


        

    ]
];