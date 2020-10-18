<?php

namespace Commands;

use Commands\BaseCommand;
use Commands\DrawFromRaffle;
use Helpers\Helper;

class LazyDraw extends BaseCommand{

    public $key_word = "lazy";

    public $options = [
        "description" => "Because I'm lazy, use this to do the Mon-Fri rolls, donors get 3 extra tickets",
        "usage" => "!lazy @role",
    ];

    public function command(){

        return function($data, $params){

            // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                return "Not permitted to perform this command.";
            }

            // hold the winners
            $winners = [];

            /////////////////////////////////
            // DO THE SATURDAY DRAW FIRST! //
            /////////////////////////////////

            // // new raffle just for donors
            // $class = new NewRaffle();
            // $func = $class->command();
            // $func($data, [], true);

            // // draw from donor raffle
            // $class = new DrawFromRaffle();
            // $func = $class->command();
            // $array = $func($data, [], true);

            // $winners = array_merge($array, $winners);

            //////////////////////
            // DO "WEEKLY DRAW" //
            //////////////////////

            // populate raffle in the usual way...
            $class = new NewRaffle();
            $func = $class->command();
            $func($data, [], false);

            // add to donors to raffle...
            $class = new AddToRaffle();
            $func = $class->command();
            $func($data, [3], true);

            // right now we have 1 entry for all entrants, + 3 for raffle guys

            // add to donors to raffle...
            $class = new DrawFromRaffle();
            $func = $class->command();
            $array = $func($data, ["s"], true);
            $winners = array_merge($array, $winners);

            $string = PHP_EOL . "Your winners are;" . PHP_EOL . implode(PHP_EOL, $winners);

            return $string;

        };

    }

}
