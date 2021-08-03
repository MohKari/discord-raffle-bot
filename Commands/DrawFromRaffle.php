<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class DrawFromRaffle extends BaseCommand{

    public $key_word = "draw";

    public $options = [
        "description" => "Draw Mon-Sun winners ( exclude Thursday & Sunday ). A user may not win more than once.",
        "usage" => "!draw",
        // "usage" => "!draw s \\ !draw",
    ];

    public function command(){

        return function($data, $params, $lazy = false){

            // standard draw dates
            $standard = ["Monday", "Tuesday", "Wednesday", "Friday", "Saturday"];
            // $donor = ["Saturday"];
            $use;

            // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                return "Not permitted to perform this command.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();

            // how many draws?
            // $mode = end($params);
            // if($mode == "s"){
                $use = $standard;
            // }else{
            //     $use = $donor;
            // }

            // hold the winners
            $winners = [];

            // draw X times
            for($i = 0; $i < count($use); $i++){

                // pick a winner....
                $member = $raffle->draw();

                // im not too sure why i'm doing this string check...
                if(is_string($member)){
                    $winner = $member;
                }else{
                    $winner = $member->id;
                }

                // puts winners in array so we can print it nicer in a moment...
                $winners[] = $use[$i] . " <@" . $winner . ">";

            }

            // if super lazy mode... return winners
            if($lazy == true){
                return $winners;
            }

            $string = PHP_EOL . "Your winners are;" . PHP_EOL . implode(PHP_EOL, $winners);

            return $string;

        };

    }

}
