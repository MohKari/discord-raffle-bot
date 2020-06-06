<?php

// composer autoloader
include __DIR__.'/vendor/autoload.php';
include __DIR__.'/debug.php';
include __DIR__.'/shit.php';

use Discord\DiscordCommandClient;

//bots token
$token = 'NzE4NTYwOTI2NTM3NjEzNDM0.Xtqr7g.K_73Dne1U8hj8u-0fKFo3BVl-0M';
$admin_role = "Raffle-Admin";

// discord object
$discord = new DiscordCommandClient([
    'token' => $token,
    'prefix' => "!",
    // 'discordOptions' => [
    //     'loadAllMembers' => true,
    // ],
]);

// becomes discord object
$object; 

// list of people in raffle
$list = [];

// bot is ON!
$discord->on('ready', function ($discord) {

    $GLOBALS["object"] = $discord;

    echo "Bot is ready.", PHP_EOL;

     // Listen for events here
    // $discord->on('message', function ($message) {
    //     echo "Recieved a message from {$message->author->username}: {$message->content}", PHP_EOL;
    // });

});

//////////////
// COMMANDS //
//////////////

// example
// $discord->registerCommand("ping", function(){

// 	return "pong";

// });

// create new raffle, overwrites existing raffle
$discord->registerCommand("new-raffle", function(string $data, array $params){

    // decode what is sent in...
    $data = json_decode($data);

    // only allow raffle-admins to use this command...
    $author_roles = Helper::getRoles($data);
    if(!in_array($GLOBALS["admin_role"], $author_roles)){
        return "Not permitted to perform this command.";
    }

    // get mentioned roles
    $mentioned_roles = Helper::getMentionedRoles($data);
    if(empty($mentioned_roles)){
        return "Need at least one role to be supplied.";
    }

    // list of all users and their roles
    $users = Helper::getUsers($GLOBALS["object"]);

    /////////////////////////////////////////////////////////////////
    // ADD EACH USER ONCE TO THE LIST IF THEY HAVE A MATCHING ROLE //
    /////////////////////////////////////////////////////////////////

    // ddd($users, false);
    // ddd($mentioned_roles, false);
    foreach($mentioned_roles as $k => $v){

        // k = id
        // v = name
        // loop through each user's roles
        foreach($users as $user => $roles){

            foreach($roles as $role){

                if($role == $v){

                    // dont add user twice if multiple roles are used....
                    if(!in_array($user, $GLOBALS["list"])){
                        $GLOBALS["list"][] = $user;
                        break;
                    }

                }

            }

        }

    }

    // output
    $output = Helper::getList();
    return $output;

});

// add name to list
$discord->registerCommand("add", function(string $data, array $params){

    // decode what is sent in...
    $data = json_decode($data);

    // only allow raffle-admins to use this command...
    $author_roles = Helper::getRoles($data);
    if(!in_array($GLOBALS["admin_role"], $author_roles)){
        return "Not permitted to perform this command.";
    }

    // get mentioned roles
    $mentioned_users = Helper::getMentionedUsers($data);
    if(empty($mentioned_users)){
        return "Need at least one user to be supplied.";
    }

    // add mentions
    foreach($mentioned_users as $user){
        $GLOBALS["list"][] = $user;
    }

    return "Added.";

});

// remove name to list
$discord->registerCommand("remove", function(string $data, array $params){

    // decode what is sent in...
    $data = json_decode($data);

    // only allow raffle-admins to use this command...
    $author_roles = Helper::getRoles($data);
    if(!in_array($GLOBALS["admin_role"], $author_roles)){
        return "Not permitted to perform this command.";
    }

    // get mentioned roles
    $mentioned_users = Helper::getMentionedUsers($data);
    if(empty($mentioned_users)){
        return "Need at least one user to be supplied.";
    }

    // loop through each item....
    foreach($GLOBALS["list"] as $k => $v){

        // loop through each mention...
        foreach($mentioned_users as $user){

            if($v == $user){
                unset($GLOBALS["list"][$k]);
                break;
            }

        }
        
    }

    return "Removed.";

});

// show current list
$discord->registerCommand("show", function(){

    $output = Helper::getList();
    return $output;

});

// draw 1 item and remove it from array
$discord->registerCommand("draw", function(){

    if(empty($GLOBALS["list"])){
        return "No more names in raffle.";
    }

    // random index from array
    $index = array_rand($GLOBALS["list"], 1);

    // random item from the array
    $winner = $GLOBALS["list"][$index];

    // remove all instances that share the same value in the array
    foreach($GLOBALS["list"] as $k => $v){
        if($v == $winner){
            unset($GLOBALS["list"][$k]);
        }
    }

    return $winner;

});

// run bot...
$discord->run();
