<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class NewRaffle extends BaseCommand{

    public $key_word = "new";

    public $options = [
        "description" => "Creates a new raffle, all users with supplied role(s) gain one entry to the raffle.",
        "usage" => "[@roles]",
    ];

    public function command(){

        return function($data, $params){

            // permittied roles only
            $author_roles = Helper::getRoles($data);
            if(!in_array(en("ADMIN_ROLE"), $author_roles)){
                return "Not permitted to perform this command.";
            }

            // get mentioned roles
            $mentioned_roles = Helper::getMentionedRoles($data);
            if(empty($mentioned_roles)){
                return "Need at least one role to be supplied.";
            }

            // get and clear raffle
            $raffle = Helper::getRaffle();
            $raffle->clear();

            // get object
            $object = Helper::getObject();

            // list of all users and their roles
            $users = Helper::getUsers($object);

            //////////////////////////////////////////////////
            // ADD ALL USERS THAT HAVE A MATCHING ROLE ONCE //
            //////////////////////////////////////////////////

            // loop through all mentioned roles
            foreach($mentioned_roles as $id => $role_name){

                // loop through all users
                foreach($users as $user_name => $roles){

                    // loop through all of users roles
                    foreach($roles as $role){

                        // if role matches mentioned role name
                        if($role == $role_name){

                            // add the raffle if they are not already in there
                            if($raffle->addIfNotExist($user_name)){
                                break;
                            }

                        }

                    }

                }

            }

            return $raffle->showList();

        };

    }

}
