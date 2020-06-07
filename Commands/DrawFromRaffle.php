<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class DrawFromRaffle extends BaseCommand{

    public $key_word = "draw";

    public $options = [
        "description" => "Draw one \"Ticket\" from raffle.",
        "usage" => "",
    ];

    public function command(){

        return function($data, $params){

            // decode what is sent in...
            $data = json_decode($data);

            // only allow raffle-admins to use this command...
            $author_roles = Helper::getRoles($data);
            if(!in_array(en("ADMIN_ROLE"), $author_roles)){
                return "Not permitted to perform this command.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();
            $winner = $raffle->draw();

            return $winner;

        };

    }

}
