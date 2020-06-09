<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class NewRaffle extends BaseCommand{

    public $key_word = "new";

    public $options = [
        "description" => "Creates a new raffle. Members with @role(s) have 1 ticket.",
        "usage" => "@role(s)",
    ];

    public function command(){

        return function($data, $params){

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

            //////////////////////////////////////////////////
            // ADD ALL USERS THAT HAVE A MATCHING ROLE ONCE //
            //////////////////////////////////////////////////

            // loop through all mentioned roles
            foreach($roles as $role){

                // loop through all users
                foreach($members as $member){

                    // if member role matches role, try to add it
                    if(in_array($role, $member->roles)){
                        $raffle->addIfNotExist($member);
                    }

                }

            }

            return $raffle->showList();

        };

    }

}
