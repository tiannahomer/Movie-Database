<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<div class="container_50">
<div id="title">
	<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Delete movie from collection
 </div>
<div class="inner">
  <form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
    <div class="row">
      <div class="col-25">
        <label for="iImdb">Enter IMDB ID</label>
	  </div>
	  <div class="col-75">
		  <input type="text" id="iImdb" name="Imdb" placeholder="Imdb ID..."  autofocus>
      </div>
    </div><br><br>
    <div class="row">
			<input type="submit" value="Delete">
    </div>
  </form>
  <?php
		require_once "vendor/autoload.php";
		if (!empty($_POST['Imdb']))
		{
			$imdbID = $_POST['Imdb'];

			/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
				1.	Connect to the "Movies" collection in the "Video" database
				2.	Find one record with "imdbId" field equals to "$imdbID" and store result in "$result"
			*/

			$client = new MongoDB\Client("mongodb://localhost:27017");
			$collection = $client->Video->Movies;

			$result = $collection->findOne(
		  		array("imdbId" => $imdbID)
				);

			if (!empty($result['title']))
			{
				/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
					1.	Delete one record with "imdbId" field equals to "$imdbID" and place return value in "$deleteResult"
				*/

				$deleteResult = $collection->deleteOne(
					array("imdbId" => $imdbID)
				);


				if ($deleteResult->getDeletedCount() == 1)
				{
					echo "<b>".$result['title']."</b><br><font color='red'>Have been deleted from the movies collection</font>";
				}
			}
			else
			{
				echo "<b>".$imdbID."</b><br><font color='green'>Not found in the movies collection</font>";

			}
		}
  ?>
 </div>
 </div>
