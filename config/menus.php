<?php

return [
    "0" =>
    [
        "1" => "I want to install or code an accessory",
        "2" => "I want to lower my fuel consumption",
        "3" => "My car has problems",
        "4" => "I want increace the horse power of my car",
        "5" => "I have another query",
        "q" => "How can I help you?"

    ],
    "01" => [
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
    "02" => []
];
