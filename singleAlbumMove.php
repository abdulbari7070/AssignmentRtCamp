<?php
  require_once("config.php");
  require_once("googleConfig.php");
	if(!isset($_SESSION['userData']))
	{
		header("location:login.php");
	}

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


  if (isset($_SESSION['code']) || (isset($_SESSION['google_access_token']) && $_SESSION['google_access_token'])) {
   
    $hiddenAlbumID = $_GET['id'];
    
    // echo $hiddenAlbumID;
    // echo "<script>alert('$hiddenAlbumID')</script>";
    // die;
        // $userImages=null;
     
    $photo_response = $facebook_data->get($hiddenAlbumID."?fields=id,name,photos{images}", $album_token);
    $userData = $photo_response->getGraphNode()->asArray();
    $userPhotos = $userData['photos'];
    $album_names = $userData['name'];

    echo $album_names;
    for($j=0;$j<count($userPhotos);$j++)
    {
      $userImages[] = $userPhotos[$j]['images'];
      
    }
    if (isset($_GET['code'])) {
      $googleClient->authenticate($_SESSION['code']);
      $_SESSION['google_access_token'] = $googleClient->getAccessToken();
      }
      echo "<br><h1>Downloading Your Files...</h1>";
      $googleClient->setAccessToken($_SESSION['google_access_token']);

      $service = new Google_Service_Drive($googleClient);

      $fileMetadata = new Google_Service_Drive_DriveFile(array(
          'name' => 'Album Data',
          'mimeType' => 'application/vnd.google-apps.folder'));
      $file = $service->files->create($fileMetadata, array(
          'fields' => 'id'));
        
      $folder_id = $file->id;

      $albumMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $userData['name'],
        'parents' => array($folder_id),
        'mimeType' => 'application/vnd.google-apps.folder'));

       $file2 = $service->files->create($albumMetadata, array(
        'fields' => 'id'));
    // mkdir("C:/AlbumData");
  	// $AlbumPath = "C:/AlbumData";

    // mkdir($AlbumPath.'/'.$userData['name']);
    // $final_path = $AlbumPath.'/'.$userData['name'];
    // die;
    for($k=0;$k<count($userImages);$k++)
    {
      $final_images[$k] = $userImages[$k][0]['source'];


      $fileMetadata2 = new Google_Service_Drive_DriveFile(array(
        'name' => $k.".jpg",
        'parents' => array($file2->id)
      ));
      $content = file_get_contents($final_images[$k]);
      $file = $service->files->create($fileMetadata2, array(
          'data' => $content,
          'mimeType' => 'image/jpeg',
          'uploadType' => 'multipart',
          'fields' => 'id'));
      // $newfile = $final_path.'/'.$k.".jpg";
      // copy($final_images[$k], $newfile);
    }
    //var_dump($final_images);
    
    echo "<script>alert('Album Downloaded In Drive/AlbumData')</script>";
     header("location: index.php?singleAlbumMoveSuccess=true");
  }
  else
  {
    $googleLoginUrl = $googleClient->createAuthUrl();
    header("location: $googleLoginUrl");
  }
?>