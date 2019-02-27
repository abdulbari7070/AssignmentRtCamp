<?php
  require_once("config.php");
	if(!isset($_SESSION['userData']))
	{
		header("location:login.php");
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Facebook Albums</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <!-- Fullscreen slider -->
    <link rel="stylesheet" href="css/fullscreen/jquery-fullsizable.css" />
    <link rel="stylesheet" href="css/fullscreen/jquery-fullsizable-theme.css" />
    <script src="js/fullscreen/jquery-1.7.2.js"></script>
    <script src="https://cdn.rawgit.com/mattbryson/TouchSwipe-Jquery-Plugin/1.6.6/jquery.touchSwipe.min.js"></script>
    <script src="js/fullscreen/jquery-fullsizable.js"></script>

    <style type="text/css">
      body
      {
        overflow-x:hidden;
        margin: 0 !important;
        padding: 0 !important;
      }
      a
      {
        margin:0;
        padding:0;
      }
    </style>
  </head>	

  <?php
    $get_id=$_GET['id'];
    // echo $get_id;  

    if((isset($_SESSION['userData'])) && (isset($_SESSION['accessToken'])))
    {
      $access_token = $_SESSION['accessToken'];
      foreach ($access_token as $key => $value) {
        if($key=="value")
        {
          $album_token = $value;
        }
      }
      // echo $album_token;
      $photo_response = $facebook_data->get($get_id."?fields=id,name,photos{images}", $album_token);
	    $userData = $photo_response->getGraphNode()->asArray();
      $userPhotos = $userData['photos'];

      for($i=0;$i<count($userPhotos);$i++)
      {
        $userImages[] = $userPhotos[$i]['images'];
      }

      for($i=0;$i<count($userImages);$i++)
      {
        $final_images[] = $userImages[$i][0]['source'];
      }
    }
  ?>

  <body>
    <div class="container">
      <div class="row">
        <?php
          for($i=0;$i<count($final_images);$i++)
            {
        ?>
        <div class="col-lg-4 col-md-6" style="margin-bottom: 10px;margin-top: 10px;">
          <a href=<?php echo $final_images[$i]; ?>>
            <img style="height:350px;width:100%;" alt="" src=<?php echo $final_images[$i]; ?>>
          </a>
        </div>
        <?php
          }
        ?>
      </div>
    </div>
  </body>

  <script>
    $(function() {
      $('a').fullsizable({
        detach_id: 'container'
      });

      $(document).on('fullsizable:opened', function(){
        $("#jquery-fullsizable").swipe({
          swipeLeft: function(){
            $(document).trigger('fullsizable:next')
          },
          swipeRight: function(){
            $(document).trigger('fullsizable:prev')
          },
          swipeUp: function(){
            $(document).trigger('fullsizable:close')
          }
        });
      });
    });
  </script>
</html>