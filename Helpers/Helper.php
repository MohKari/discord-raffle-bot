<?php

Namespace Helpers;

class Helper{

    /////////
    // NEW //
    /////////

    public static function getRaffle()
    {
        return $GLOBALS["raffle"];
    }

    public static function getObject()
    {
        return $GLOBALS["object"];
    }

    /////////////
    // CURRENT //
    /////////////

    /**
     * Get roles of user
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getRoles($data)
    {

        $roles = [];

        // loop through
        foreach($data->author->roles as $role){
            $roles[] = $role->name;
        }

        return $roles;

    }

    /**
     * Get mentioned roles
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getMentionedRoles($data)
    {

        $roles = [];

        foreach($data->mention_roles as $role){
            $roles[$role->id] = $role->name;
        }

        return $roles;

    }

    /**
     * Get mentioned users
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getMentionedUsers($data)
    {

        $users = [];

        foreach($data->mentions as $user){
            $users[$user->id] = $user->username;
        }

        return $users;

    }

    /**
     * return array of usernames => roles[]
     * @param  [type] $discord [description]
     * @return [type]          [description]
     */
    public static function getUsers($discord)
    {

        // username => roles []
        $users = [];

        // list all users/roles
        foreach($discord->guilds as $guild){
            foreach($guild->members as $member){
                    // username of member
                $username = $member->username;
                $users[$username] = [];

                foreach($member->roles as $role){
                    // all roles of member
                    $role = $role->name;
                    $users[$username][] = $role;

                }
            }
        }

        return $users;

    }

    // return list of names in list
    public static function getList()
    {

        $list = $GLOBALS["list"];

        $output = "";
        foreach($list as $item){
            $output .= PHP_EOL . "> " . $item;
        }

        if($output == ""){
            $output = "no raffle started.";
        }

        return $output;

    }

}