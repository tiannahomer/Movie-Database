<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<div class="container_50">
	<div id="title">
		<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Update movie in collection
	</div>
	<div class="inner">
  <form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
    <div class="row">
      <div class="col-25">
        <label for="iImdb" style ="float: right;">Enter IMDB ID</label>
	  </div>
	  <div class="col-75">
		  <input type="text" id="iImdb" name="Imdb" placeholder="Imdb ID..."  autofocus>
      </div>
    </div><br>
	 <div class="row">
			<input type="submit" value="Display Movie Document">
    </div><br><br>
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


			$jsonDoc ="";
			if (isset($result))
			{
				$_SESSION["ID"] = $result['_id'];
				$jsonDoc = json_encode(iterator_to_array($result));
				$_SESSION["Doc"] = $jsonDoc;
				$_SESSION["IMDB"] = $imdbID;
				echo "<font size='5'>IMDB ID: $imdbID<br><br>".$jsonDoc."</font><br><br>";
				echo '<form action='.$_SERVER["PHP_SELF"].' method="post">';
				echo '<div class="row">';
					echo '<div class="col-25">';
						echo '<label for="ifname" style ="float: right;">Field Name</label>';
					echo '</div>';
					echo '<div class="col-25">';
						echo '<input type="text" id="ifname" name="Fname" placeholder="Field Name...">';
					echo '</div>';
					echo '<div class="col-25">';
						echo '<label for="ifvalue" style ="float: right;">New Field Value</label>';
					echo '</div>';
					echo '<div class="col-25">';
						echo '<input type="text" id="ifvalue" name="fValue" placeholder="New Value...">';
					echo '</div>';
				echo '</div><br>';
				echo '<div class="row">';
					echo '<input type="submit" value="Update Movie Document">';
				echo '</div><br>';
				echo '</form>';
  			}
			else
			{
				echo "<font size='5'>Document was not found!</font>";
			}
		}
		if (!empty($_POST['Fname']) && !empty($_POST['fValue']))
		{
			$Fname = $_POST['Fname'];
			$Fvalue = $_POST['fValue'];
			echo "<font size='5'><b>OLD DOCUMENT</b></font><br><br>";
			echo "<font size='5'>IMDB ID: ".$_SESSION["IMDB"]."</font><br><br>";
			echo "<font size='5'>".$_SESSION["Doc"]."</font><br><br><br><br>";
			$theID = $_SESSION["ID"];

			/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
				1.	Connect to the "Movies" collection in the "Video" database
				2.	Update one document where "_id" is equals to "new MongoDB\BSON\ObjectID($theID)])" and set $Fname to $Fvalue for this document
				3.	Find one document where "_id" is equals to "new MongoDB\BSON\ObjectID($theID)])" and store in "$item"
			*/
			$client = new MongoDB\Client("mongodb://localhost:27017");
			$collection2 = $client->Video->Movies;

			$updateResult = $collection2->updateOne(
				array('_id' => new MongoDB\BSON\ObjectID($theID)),
				array('$set' => array($Fname => $Fvalue))
			);

			$item = $collection2->findOne(
				array('_id' => new MongoDB\BSON\ObjectID($theID))
			);



			$newjsonDoc = json_encode(iterator_to_array($item));
			echo "<font size='5'><b>CHANGED DOCUMENT</b></font><br><br>";
			echo "<font size='5'>IMDB ID: ".$_SESSION["IMDB"]."</font><br><br>";
			echo "<font size='5'>".$newjsonDoc."</font><br><br><br><br>";

		}
  ?>
</div>
</div>
