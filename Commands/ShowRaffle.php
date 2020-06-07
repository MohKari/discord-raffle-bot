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

            // decode what is sent in...
            $data = json_decode($data);

            // only allow raffle-admins to use this command...
            $author_roles = Helper::getRoles($data);
            if(!in_array(en("ADMIN_ROLE"), $author_roles)){
                return "Not permitted to perform this command.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();

            // show current raffle
            return $raffle->showList();

        };

    }

}
