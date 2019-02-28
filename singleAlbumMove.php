<?php
	require_once("config.php");
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


    $hiddenAlbumID = $_GET['id'];
    // echo $hiddenAlbumID;
    // echo "<script>alert('$hiddenAlbumID')</script>";
    // die;
        // $userImages=null;

    $photo_response = $facebook_data->get($hiddenAlbumID."?fields=id,name,photos{images}", $album_token);
    $userData = $photo_response->getGraphNode()->asArray();
    $userPhotos = $userData['photos'];

    for($j=0;$j<count($userPhotos);$j++)
    {
      $userImages[] = $userPhotos[$j]['images'];
    }

    mkdir("C:/AlbumData");
  	$AlbumPath = "C:/AlbumData";

    mkdir($AlbumPath.'/'.$userData['name']);
    $final_path = $AlbumPath.'/'.$userData['name'];
    // die;
    for($k=0;$k<count($userImages);$k++)
    {
      $final_images[$k] = $userImages[$k][0]['source'];
      
      $newfile = $final_path.'/'.$k.".jpg";
      copy($final_images[$k], $newfile);
    }
    echo "<script>alert('Album Downloaded In C:/AlbumData')</script>";
    header("location: index.php?singleAlbumSuccess=true");

?>