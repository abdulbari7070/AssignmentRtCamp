<?php
    require_once("config.php");
    
    try{
        $accessToken = $helper->getAccessToken();   
    }
    catch(\Facebook\Exceptions\FacebookResponseException $e)
    {
        echo "Response Exception : " .$e->getMessage();
        exit();
    }
    catch(\Facebook\Exceptions\FacebookSDKException $e)
    {
        echo "SDK Exception : " .$e->getMessage();
        exit();
    }

    if(!$accessToken)
    {
        header('Location: login.php');
        exit();
    }

    $oAuth2Client = $facebook_data->getOAuth2Client();
	if (!$accessToken->isLongLived())
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

	$response = $facebook_data->get("me?fields=id,first_name,last_name,email,picture.type(large),albums{id,name,photo_count,picture,photos{images}}", $accessToken);
	$userData = $response->getGraphNode()->asArray();
    $_SESSION['userData'] = $userData;
    $_SESSION['accessToken'] = $accessToken;
    header('Location: index.php');
    exit(); 
?>