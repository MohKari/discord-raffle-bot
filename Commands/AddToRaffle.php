<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class AddToRaffle extends BaseCommand{

    public $key_word = "add";

    public $options = [
        "description" => "Add X tickets to user(s) and/or users with roles(s).",
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

            foreach($roles as $role){
                foreach($members as $member){
                    if(in_array($role, $member->roles)){
                        for($i = 0; $i < $count; $i++){
                            $raffle->add($member);
                        }
                    }
                }
            }

            return $raffle->showList();

        };

    }

}
