<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class AddToRaffle extends BaseCommand{

    public $key_word = "add";

    public $options = [
        "description" => "Add X tickets for user(s) | Members that have all mentioned @role(s) gain X ticket.",
        "usage" => "@user(s) / @role(s) X",
    ];

    public function command(){

        return function($data, $params){

            // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                return "Not permitted to perform this command.";
            }

            // get ids of any @members and names of any @roles
            $ids = Helper::GetMemberIdsFromMessage($data);
            $roles = Helper::getRoleNamesFromMessage($data);
            if(empty($ids) && empty($roles)){
                return "At least one user or role must be mentioned.";
            }

            // get all members
            $members = Helper::getAllMembers();

            // get raffle object
            $raffle = Helper::getRaffle();

            // how many entries?
            $count = end($params);
            if(!is_numeric($count)){
                $count = 1;
            }

            /////////////////////////
            // ADD MENTIONED USERS //
            /////////////////////////

            foreach($ids as $id){
                foreach($members as $member){
                    if($id == $member->id){
                        for($i = 0; $i < $count; $i++){
                            $raffle->add($member);
                        }
                        break;
                    }
                }
            }

            /////////////////////////
            // ADD MENTIONED ROLES //
            /////////////////////////

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
                    for($i = 0; $i < $count; $i++){
                        $raffle->add($member);
                    }
                }

            }

            return $raffle->showList();

        };

    }

}
