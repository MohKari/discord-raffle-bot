<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class GroupBuddies extends BaseCommand{

	/**
	 * Key word to run the command
	 * @var string
	 */
	public $key_word = "buddy";

	/**
	 * Any additional options
	 * @var array
	 */
	public $options =  [
        "description" => "\"Groups\" together members that have all mentioned @roles(s) into groups of X",
        "usage" => "@role(s) X",
	];

	/**
	 * Command to be run.
	 * @return callable function
	 */
	public function command()
	{

		return function($data, $params){

			 // if author is not admin, eep!
            if(!Helper::isAuthorAdmin($data)){
                return "Not permitted to perform this command.";
            }

            // get ids of any @members and names of any @roles
            $roles = Helper::getRoleNamesFromMessage($data);
            if(empty($roles)){
                return "At least one role must be mentioned.";
            }

            // get all members
            $members = Helper::getAllMembers();

            // get raffle object
            $raffle = Helper::getRaffle();
            $raffle->clear();

            // group into 2, 3's?
          	$group_size = end($params);
            if(!is_numeric($group_size)){
                $group_size = 2;
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
                    $raffle->add($member);
                }

            }

            // how many names in "Hat"
            $size = count($raffle->data);

            ///////////////////////////
            // DRAW INTO GROUPS OF X //
            ///////////////////////////

            // will get populated by arrays that contain "names"
            $groups = [];

            // while the raffle is not empty...
            while(!$raffle->isEmpty()){

            	// empty group...
            	$group = [];

            	// loop X times...
            	for($i = 0; $i < $group_size; $i++){

            		// if raffle is empty, dont bother anymore...
            		if($raffle->isEmpty()){
            			break;
            		}

            		// get someone from raffle...
            		$member = $raffle->draw();

		            // im not too sure why i'm doing this string check...
	                if(is_string($member)){
	                    $name = $member;
	                }else{
	                    $name = $member->name;
	                }

	                // add name to group
	                $group[] = "@".$name;

            	}

            	// add the group we just created to the array of groups...
            	$groups[] = $group;

            }

            $count = 0;

            // output
            $output = "In Raffle: " . $size . ", please run command a few times, if this number changes, I will be sad..." . PHP_EOL;
            $output .= "```" . PHP_EOL;

            // break groups up so I can output string...
            foreach($groups as $group){

            	$count++;
            	$output .= "Team " . $count . PHP_EOL;

                $team = [];
            	foreach($group as $name){
            		$team[] = $name;
            	}

                $output .= implode($team, ", ") . PHP_EOL;

            }

            $output .= "```";

            return $output;

		};

	}

}
