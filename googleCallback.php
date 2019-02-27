<?php
    require_once("googleConfig.php");

    if(isset($_GET['code']))
    {
        $_SESSION['code'] = $_GET['code'];
        $token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['google_access_token'] = $token;
    }

    $oAuth = new Google_Service_Oauth2($googleClient);
    $userData = $oAuth->userinfo_v2_me->get();

    $_SESSION['google_user']=$userData['email'];
    $_SESSION['id']=$userData['id'];
    // echo $_SESSION['google_user'];
    // die;
    header('Location: index.php');
    exit();
?>