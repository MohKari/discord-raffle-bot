<?php

namespace Classes;

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
	 * reset $data object
	 */
	public function clear(){
		$this->data = [];
	}

	/**
	 * draw a item randomly.
	 * and remove any duplicate entries
	 * @return [type] [description]
	 */
	public function draw(){

		// output if empty
		if(empty($this->data)){
			return "No one is in the raffle bro.";
		}

		// random index from array
    	$index = array_rand($this->data, 1);

	    // random item from the array
	    $winner = $this->data[$index];

	    // remove
	    $this->remove($winner);

	    //
		return $winner;

	}

	/**
	 * add name to data
	 */
	public function add($name){
		$this->data[] = $name;
	}

	/**
	 * remove all entries from raffle
	 * @param  [type] $name
	 */
	public function remove($name){
		foreach($this->data as $k => $v){
			if($name == $v){
				unset($this->data[$k]);
			}
		}
	}

	/**
	 * check if name exists in array
	 * @param  string $name
	 * @return bool
	 */
	public function exists($name){
		return in_array($name, $this->data);
	}

	/**
	 * add to $data if not already in
	 * @param string $name
	 * @return bool true if added
	 */
	public function addIFNotExist($name){
		if(!$this->exists($name)){
			$this->add($name);
			return true;
		}
		return false;
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

		// output
		$output = "Here is the list of current entries.";

		//
		$tally = array_count_values($this->data);

		//
		$output .= "```";
		foreach($tally as $name => $value){
			$output .= PHP_EOL . $name . "-" . $value;
		}
		$output .= "```";

		//
		return $output;

	}

}
