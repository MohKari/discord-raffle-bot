<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;

class DemoCommand extends BaseCommand{

	public $key_word = "demo";

	public $options = [
		"description" => "demo command, you can ignore me.",
		"usage" => "Does't do much, just returns hello world.",
	];

	public function command(){

 		// return function($data, $params){

   //          // if author is not admin, eep!
   //          if(!Helper::isAuthorAdmin($data)){
   //              return "Not permitted to perform this command.";
   //          }

   //          // get ids of any @members and names of any @roles
   //          $ids = Helper::GetMemberIdsFromMessage($data);

   //          foreach($ids as $id){
   //          	return "<".$id.">";
   //          }
   //          // ddd($ids);

   //       };

	}

}
