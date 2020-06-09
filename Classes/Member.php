<?php

namespace Classes;

use Discord\Parts\User\Member as DiscordMember;

class Member{

	/**
	 * unique ID of user
	 * @var [type]
	 */
	public $id;

	/**
	 * Display name of user
	 * @var [type]
	 */
	public $name;

	/**
	 * Discord username
	 * @var [type]
	 */
	public $username;

	/**
	 * Discord server nickname
	 * @var [type]
	 */
	public $nick;

	/**
	 * Roles of user
	 * @var array
	 */
	public $roles = [];

	/**
	 * On __construct, set properties i care about.
	 * @param DiscordMember $member [description]
	 */
	public function __construct(DiscordMember $member)
	{

		$this->id = $member->id;

		// try to use nickname for their name...
		if($member->nick != null){
			$this->name = $member->nick;
		}else{
			$this->name = $member->username;
		}

		$this->username = $member->username;
		$this->nick = $member->nick;

        foreach($member->roles as $role){
        	$this->roles[] = $role->name;
        }

	}

}
