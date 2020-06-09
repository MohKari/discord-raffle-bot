<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class DrawFromRaffle extends BaseCommand{

    public $key_word = "draw";

    public $options = [
        "description" => "Draw one \"Ticket\" from raffle. A user may not win more than once.",
        "usage" => "",
    ];

    public function command(){

        return function($data, $params){

            // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                return "Not permitted to perform this command.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();
            $member = $raffle->draw();

            return "The Winner Is.... " . $member->name;

        };

    }

}
