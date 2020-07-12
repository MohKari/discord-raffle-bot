<?php

namespace Classes;

use Classes\Member;

/**
 * Class represents a list of entrys that are going to be raffled
 */
class Raffle{

	/**
	 * list of names that are in the raffle
	 * @var array
	 */
	public $data = [];

	/**
	 * is the list empty?
	 * @return boolean [description]
	 */
	public function isEmpty(){

		if(empty($this->data)){
			return true;
		}

		return false;

	}

	/**
	 * reset $data object
	 */
	public function clear(){
		$this->data = [];
	}

	/**
	 * draw a item randomly.
	 * and remove any duplicate entries
	 * @return string | Member
	 */
	public function draw(){

		// output if empty
		if(empty($this->data)){
			return "No one is in the raffle bro.";
		}

		// random index from array
    	$index = array_rand($this->data, 1);

	    // random item from the array
	    $member = $this->data[$index];

	    // remove
	    $this->remove($member);

	    //
		return $member;

	}

	/**
	 * add name to data
	 */
	public function add(Member $member){
		$this->data[] = $member;
	}

	/**
	 * remove all entries from raffle
	 * @param  [type] $name
	 */
	public function remove(Member $member){
		foreach($this->data as $k => $_member){
			if($member->id == $_member->id){
				unset($this->data[$k]);
			}
		}
	}

	/**
	 * check if name exists in array
	 * @param  string $name
	 * @return bool
	 */
	public function exists(Member $member){
		return in_array($member, $this->data);
	}

	/**
	 * add to $data if not already in
	 * @param string $name
	 */
	public function addIFNotExist(Member $member){
		if(!$this->exists($member)){
			$this->add($member);
		}
	}

	/**
	 * show list
	 * @return string
	 */
	public function showList()
	{

		// output if empty
		if(empty($this->data)){
			return "No one is in the raffle bro.";
		}

		// keep track of any members that have already been "tallied"
		$found_ids = [];

		// keep track of member names and currently tallys
		$data = [];

		// loop through each member object...
		foreach($this->data as $member){

			$count = 1;

			// if we have not delt with the specific member id yet... lets do it..
			if(!in_array($member->id, $found_ids)){

				// add to array, so we dont do it twice...
				$found_ids[] = $member->id;

				// start to tally...
				$count = 0;
				foreach($this->data as $_member){
					if($member->id == $_member->id){
						$count++;
					}
				}

				// add to data...
				$data[$member->name] = $count;

			}

		}

		// sort data alphabetically
		ksort($data);

		// output
		$output = "Here is the list of current entries.";
		$output .= "```";
		foreach($data as $k => $v){
			$output .= PHP_EOL . $k . '-' . $v;
		}
		$output .= "```";

		return $output;

	}

}
