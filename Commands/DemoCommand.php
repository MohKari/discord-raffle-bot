<?php

namespace Commands;

use Commands\BaseCommand;

class DemoCommand extends BaseCommand{

	public $key_word = "demo";

	public $options = [
		"description" => "demo command, you can ignore me.",
		"usage" => "Does't do much, just returns hello world.",
	];

	// public function command(){

	// }

}
