<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class NewRaffle extends BaseCommand{

    public $key_word = "new";

    public $options = [
        "description" => "Creates a new raffle. Members that have all mentioned @role(s) gain 1 ticket.",
        "usage" => "@role(s)",
    ];

    public function command(){

        return function($data, $params, $lazy = false){

            // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                 return "Not permitted to perform this command.";
            }

            // get roles from message
            $roles = Helper::getRoleNamesFromMessage($data);
            if(empty($roles)){
                return "Need at least one role to be supplied.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();
            $raffle->clear();

            // get all members of server
            $members = Helper::GetAllMembers();

            /////////////////////////////////////////////
            // ADD ALL USERS THAT MATCH ALL ROLES ONCE //
            /////////////////////////////////////////////

            // super lazy hack for me to not have to do too many commands...
            if($lazy == true){
                $roles[] = "Raffle Donor";
            }

            // amount of matches required to be added...
            $required_matches = count($roles);

            // loop through all users
            foreach($members as $member){

                $matches = 0;

                // loop through all mentioned roles
                foreach($roles as $role){

                    // if member role matches, increase matches
                    if(in_array($role, $member->roles)){
                        $matches++;
                    }

                }

                if($matches >= $required_matches){
                    $raffle->addIfNotExist($member);
                }

            }

            return $raffle->showList();

        };

    }

}
