<?php
include "Render.php";

$arr = [
    "oi"=>[
        "casa",
        "da",
        "veia"
    ],
    "data" => "coisa nova",
    "carros" =>[
        [
            "tipo"=>"4x4",
            "marca"=>"toyota",
            "motor"=>"4.6"
        ],
        [
            "tipo"=>"normal",
            "marca"=>"VW",
            "motor"=>"1.0"
        ],
        [
            "tipo"=>"2x2",
            "marca"=>"pegeuot",
            "motor"=>"1.6"
        ]
    ]

];



Render::template("template.html")->view($arr);
