<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class ShowRaffle extends BaseCommand{

    public $key_word = "show";

    public $options = [
        "description" => "Show current raffle entries.",
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

            // show current raffle
            return $raffle->showList();

        };

    }

}
