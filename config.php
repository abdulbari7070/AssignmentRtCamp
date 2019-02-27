<?php
    session_start();
    require_once("Facebook/autoload.php");
    //error_reporting(0);


    $facebook_data = new \Facebook\Facebook([
        'app_id' => '351283179057802',
        'app_secret' => '7f31cb8302623f8c3dc90789b2497a83',
        'default_graph_version' => 'v3.2'
    ]);

    $helper = $facebook_data->getRedirectLoginHelper();

?>