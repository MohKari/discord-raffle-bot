<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class AddToRaffle extends BaseCommand{

    public $key_word = "add";

    public $options = [
        "description" => "Adds user(s) to current raffle.",
        "usage" => "[@users]",
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

            // get mentioned roles
            $mentioned_users = Helper::getMentionedUsers($data);
            if(empty($mentioned_users)){
                return "Need at least one user to be supplied.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();

            // add mentions
            foreach($mentioned_users as $user){
                $raffle->add($user);
            }

            return $raffle->showList();

        };

    }

}
