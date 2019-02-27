<?php
    require_once("Google/vendor/autoload.php");
    require_once('Google/src/Google/Client.php');
    require_once('Google/vendor/google/apiclient-services/src/Google/Service/Drive.php');
    require_once("config.php");

    $googleClient = new Google_Client();
    
    $googleClient->setClientId("258555714795-khf92rdov14bq73vr7ubrc1t5vmncb4a.apps.googleusercontent.com");
    $googleClient->setClientSecret("G8UqySmoONtmbMQi2X_G1Vg2");
    $googleClient->setApplicationName("AssignmentRtCamp");
    $googleClient->setRedirectUri("http://localhost/AssignmentRtCamp/googleCallback.php");
    
    $scopes = array('https://www.googleapis.com/auth/drive.file','https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile');

    $googleClient->setScopes($scopes);
   

   

?>