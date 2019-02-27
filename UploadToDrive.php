<?php
    require_once("googleConfig.php");
    if (isset($_SESSION['code']) || (isset($_SESSION['google_access_token']) && $_SESSION['google_access_token'])) {
        if (isset($_GET['code'])) {
            $googleClient->authenticate($_SESSION['code']);
            $_SESSION['google_access_token'] = $googleClient->getAccessToken();
        }
        $googleClient->setAccessToken($_SESSION['google_access_token']);

        $service = new Google_Service_Drive($googleClient);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'Album Data',
            'mimeType' => 'application/vnd.google-apps.folder'));
        $file = $service->files->create($fileMetadata, array(
            'fields' => 'id'));
        
        $folder_id = $file->id;
        echo $folder_id;

        if((isset($_SESSION['userData'])) && (isset($_SESSION['accessToken'])))
        {
            $access_token = $_SESSION['accessToken'];
            foreach ($access_token as $key => $value) {
            if($key=="value")
            {
                $album_token = $value;
            }
            }
        }
        
        if((isset($_SESSION['accessToken'])) && (isset($_SESSION['userData'])))
        {
            $accessToken = $_SESSION['accessToken'];
            $userData = $_SESSION['userData'];
            
            $data = json_encode($userData['albums']);
            $album_data = json_decode($data);
             
            $count = count($album_data);

            for($i = 0; $i<$count; $i++)
            {
              $album_id[] = $album_data[$i]->id;
              $album_names[] = $album_data[$i]->name;

                $albumMetadata = new Google_Service_Drive_DriveFile(array(
                    'name' => $album_names[$i],
                    'parents' => array($folder_id),
                    'mimeType' => 'application/vnd.google-apps.folder'));

                $file2[] = $service->files->create($albumMetadata, array(
                    'fields' => 'id'));

                $userImages=null;

                $photo_response = $facebook_data->get($album_id[$i]."?fields=id,name,photos{images}", $album_token);
                $userData = $photo_response->getGraphNode()->asArray();
                $userPhotos = $userData['photos'];

                for($j=0;$j<count($userPhotos);$j++)
                {
                    $userImages[] = $userPhotos[$j]['images'];
                }
                for($k=0;$k<count($userImages);$k++)
                {
                    $final_images[$i][$k] = $userImages[$k][0]['source'];
                    // $file2[$i]->id;

                    $fileMetadata2 = new Google_Service_Drive_DriveFile(array(
                        'name' => $k.".jpg",
                        'parents' => array($file2[$i]->id)
                    ));
                    $content = file_get_contents($final_images[$i][$k]);
                    $file = $service->files->create($fileMetadata2, array(
                        'data' => $content,
                        'mimeType' => 'image/jpeg',
                        'uploadType' => 'multipart',
                        'fields' => 'id'));
                }
            }
        }
        else
        {
          header("location:login.php");
          exit();
        }
    } else {
        $authUrl = $googleClient->createAuthUrl();
        header('Location: ' . $authUrl);
        exit();
    }
?>