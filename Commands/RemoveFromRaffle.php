<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class RemoveFromRaffle extends BaseCommand{

    public $key_word = "remove";

    public $options = [
        "description" => "Remove user(s) from raffle.",
        "usage" => "@user(s)",
    ];

    public function command(){

        return function($data, $params){

            // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                return "Not permitted to perform this command.";
            }

            // get id's of users in message
            $ids = Helper::GetMemberIdsFromMessage($data);
            if(empty($ids)){
                return "At least one user must be mentioned.";
            }

            // names of users that have been removed...
            $removed = [];

            // get all members
            $members = Helper::getAllMembers();

            // get raffle object
            $raffle = Helper::getRaffle();

            // remove mentions
            foreach($ids as $id){

                foreach($members as $member){

                    if($id == $member->id){
                        $raffle->remove($member);
                        $removed[] = $member->name;
                        break;
                    }

                }

            }

            $message = "I've just removed \"" . implode(", ", $removed) . "\" from the raffle.";

            return $message;

        };

    }

}
