<?php
  require_once("config.php");

  $redirectUrl = "http://localhost/AssignmentRtCamp/fb-callback.php";
  $permissions = ['email']; 
  $loginUrl = $helper->getLoginUrl($redirectUrl,$permissions);
  if(isset($_SESSION['userData']))
	{
		header("location:index.php");
	}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Facebook Albums</title>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
  </head>	

  <body class="text-center">

    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
		 <header class="masthead mb-auto">
        <div class="inner">
          <h1 class="masthead-brand">Welcome To Facebook Album.</h1>
        </div>
      </header>
		<br>
      <main role="main" class="inner cover">
		<img class="img-responsive" src="images/fb_logo.png" width="300px" height="300px"/></br></br>
        <p class="lead">An easy way to backup all your facebook albums on your desktop or in your google drive directly.</p></br>
          <input type="button" class="btn btn-lg btn-primary" onclick="window.location = '<?php echo $loginUrl ?>';" value="LOGIN WITH YOUR FACEBOOK ACCOUNT"/>
      </main>

      
    </div>
  </body>
</html>
