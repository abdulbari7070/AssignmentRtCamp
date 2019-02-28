<?php
  require_once("config.php");
  require_once("googleConfig.php");
  
	if(!isset($_SESSION['userData']))
	{
		header("location:login.php");
  }
  
  $googleLoginUrl = $googleClient->createAuthUrl();
  // echo $_SESSION['google_user'];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Profile</title>
	  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </head>	

  <body class="bg-light">
    
    <div class="container-fluid" style="margin-top : 0px; background-color: #5d5d5d; color: #fff;">
      <div class="row justify-content-center">
        <div class="col-lg-3 col-md-5 col-sm-12">
          <img class="image-responsive" src="<?php echo $_SESSION['userData']['picture']['url'] ?>" style="border-radius:50%; margin-top: 15px; margin-left: 15px; margin-bottom:15px; width:200px;">
        </div>
        <div class="col-lg-7 col-md-7" style="margin-top:30px;">
          <h1 class="display-3"><?php echo $_SESSION['userData']['last_name']." ".$_SESSION['userData']['first_name'] ?></h1> 
          <p class="lead" style="font-size:25px;"><?php echo $_SESSION['userData']['email'] ?></p>    
        </div>
        <div class="col-lg-2  " style="margin-top:50px;">
          <div class="dropdown">
            <button type="button" style="background: #fff; float: right; margin-bottom:10px;" class="btn dropdown-toggle" data-toggle="dropdown"></button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#">Download All</a>
              <?php 

                if(isset($_SESSION['google_user']))
                {
                  echo "<a class='dropdown-item' href='UploadToDrive.php'>Move All To Drive</a>";
                }
                else
                {
                  echo "<a class='dropdown-item'  style='color:red;' href=$googleLoginUrl>Connect With Google</a>";
                }
              ?>
              
              <a class="dropdown-item" href="logout.php">Log Out</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
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
            $album_counts[] = $album_data[$i]->photo_count;

            $album_picture[] = $album_data[$i]->picture;
            $picture_url[] = $album_picture[$i]->url;
          }
      }
      else
      {
        header("location:login.php");
        exit();
      }
      
      for($i=0;$i<$count;$i++)
      {
        $album_first_image[] = $album_data[$i]->photos[0]->images[0]->source;
      }
    ?>

    <div class="container" style="text-align: center; margin-bottom: 50px;">
      <div style="margin-top:15px; margin-bottom:35px; text-align: center;">
        <h1 class="display-4">Your Facebook Albums</h1>
      </div>
        
      <form action="" method="POST">
        <div class="row justify-content-center">
          <?php
            for($i=0;$i<count($album_first_image);$i++)
            {
          ?>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <a href=<?php echo '#demo'.$i; ?> data-toggle="collapse">
              <img style="margin:20px;width: 300px; height:350px;" src="<?php echo $album_first_image[$i]; ?>" class="img-circle person" alt="Random Name">
            </a>
            <div id=<?php echo "demo".$i; ?> class="collapse">
              <div class="checkbox checkbox-inline checkbox-circle checkbox-lg">
                <input type="checkbox" name="album_image_checkbox[]" class="styled" id="inlineCheckbox1lgc" value="<?php echo $album_id[$i]; ?>">
                <label for="inlineCheckbox1lgc"><p class="lead">Select</p></label>
              </div>
              <p class="lead" style="font-size:25px;"><?php echo $album_names[$i]; ?></p>
              <p class="lead" style="font-size:25px;"><?php echo $album_counts[$i]; ?> Photos</p>
              <input type="button" class="btn btn-sm btn-warning" value="View Photos" onclick="window.location = 'displayPhotos.php?id=<?php echo $album_id[$i]; ?>';"/>
              <input type="hidden" name="hiddenAlbumID[]" value="<?php echo $album_id[$i]; ?>">
              <input type="button" name="downloadSingle" class="btn btn-sm btn-warning" value="Download" onclick="window.location = 'singleAlbumDownload.php?id=<?php echo $album_id[$i]; ?>';"/>
              <input type="button" class="btn btn-sm btn-warning" value="Move To Drive" onclick="window.location = 'singleAlbumMove.php?id=<?php echo $album_id[$i]; ?>';"/>
            </div>
          </div>
          <?php
            }
          ?>
        </div>
        <input style="margin-top:50px; margin-bottom:50px;" name="downloadSelected" type="submit" class="btn btn-lg btn-primary" value="Download Selected"/>
        <input style="margin-top:50px; margin-bottom:50px;" name="moveSelected" type="submit" class="btn btn-lg btn-primary" value="Move Selected"/>
      </form>
    </div>
  </body>
</html>

<?php
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

  if(isset($_GET['singleAlbumMoveSuccess']))
  {
    if($_GET['singleAlbumMoveSuccess']=="true")
    {
      echo "<script>alert('Album Downloaded In Drive Root/AlbumData')</script>";
    }
  }

  if(isset($_GET['singleAlbumSuccess']))
  {
    if($_GET['singleAlbumSuccess']=="true")
    {
      echo "<script>alert('Album Downloaded In C:/AlbumData')</script>";
    }
  }

  $AlbumPath = "C:/AlbumData";
  if(!file_exists($AlbumPath))
  {
    mkdir("C:/AlbumData");
  }

  if(isset($_POST['downloadSelected']))
  {
    $getSelectedCheckbox = $_POST['album_image_checkbox'];
    for($i=0;$i<count($getSelectedCheckbox);$i++)
    {
        $userImages=null;

        $photo_response = $facebook_data->get($getSelectedCheckbox[$i]."?fields=id,name,photos{images}", $album_token);
        $userData = $photo_response->getGraphNode()->asArray();
        $userPhotos = $userData['photos'];

        for($j=0;$j<count($userPhotos);$j++)
        {
          $userImages[] = $userPhotos[$j]['images'];
        }

        // echo $AlbumPath;
        // echo $userData['name'];
        mkdir($AlbumPath.'/'.$userData['name']);
        $final_path = $AlbumPath.'/'.$userData['name'];
        // die;
        for($k=0;$k<count($userImages);$k++)
        {

          $final_images[$i][$k] = $userImages[$k][0]['source'];
          
          $newfile = $final_path.'/'.$k.".jpg";
          copy($final_images[$i][$k], $newfile);
        }
    }
    echo "<script>alert('Album Downloaded In C:/AlbumData')</script>";
  }

  if(isset($_POST['moveSelected']))
  {
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


      $getSelectedCheckbox = $_POST['album_image_checkbox'];
      for($i=0;$i<count($getSelectedCheckbox);$i++)
      {
          $userImages=null;

          $photo_response = $facebook_data->get($getSelectedCheckbox[$i]."?fields=id,name,photos{images}", $album_token);
          $userData = $photo_response->getGraphNode()->asArray();
          $userPhotos = $userData['photos'];
          $album_names = $userData['name'];

          $albumMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $album_names,
            'parents' => array($folder_id),
            'mimeType' => 'application/vnd.google-apps.folder'));
  
           $file2[] = $service->files->create($albumMetadata, array(
            'fields' => 'id'));

          // echo "<script type='text/javascript'>progressBar('$album_names');</script>";
  

          for($j=0;$j<count($userPhotos);$j++)
          {
            $userImages[] = $userPhotos[$j]['images'];
          }
          
          for($k=0;$k<count($userImages);$k++)
          {
            

            $final_images[$i][$k] = $userImages[$k][0]['source'];
            
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
    }else {
      echo "<script>window.location= '$googleLoginUrl'</script>";
      exit();
    }
  }
?>