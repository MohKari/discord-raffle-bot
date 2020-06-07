<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class RemoveFromRaffle extends BaseCommand{

    public $key_word = "remove";

    public $options = [
        "description" => "Remove user(s) from raffle.",
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

            // loop through each mention...
            foreach($mentioned_users as $user_name){
                $raffle->remove($user_name);
            }

            return $raffle->showList();

        };

    }

}
