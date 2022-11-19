<?php


return [
    "1"=>[ 
        [
            "action_type"=>"select_from_menu",
            "value"=> <<<M1T
            what type of accessory do you want?
            1. code a new key
            2. multimedia system
            3. supercharger
            4. other accessory
            M1T,
            "alternative_option"=>
            [
                "option"=>"4",
                "action_type"=>"ask",
                "value"=>"What do you want to install?"
            ]
        ],
        

    ]
];