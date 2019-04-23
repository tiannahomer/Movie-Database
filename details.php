<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
	<?php
		require_once "vendor/autoload.php";
		$theID = $_GET['id'];
		/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
			1.	Connect to the "Movies" collection in the "Video" database
			2.	Find one record with "_id" field equals to "new MongoDB\BSON\ObjectID($theID)" and store result in "$item"
		*/

		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->Video->Movies;

		$item = $collection->findOne(
			array("_id" => new MongoDB\BSON\ObjectID($theID))
		);


	?>
	<div class='container_50'>
		<div id='title'>
			<?php
				$back = str_replace(" ","%20",$_SESSION['BackLink']);
				echo "<a href=$back>&lt;&lt;&nbsp;&nbsp;&nbsp;&nbsp;</a>Movie Details";
			?>
		</div>
		<div class='inner'>
		<div class='row'>
			<div class='col-35'>
				<?php
					if (isset($item['PosterPath']))
					{
						echo "<p><img src=".$item['PosterPath']." width='90%' height='400'/>";
					}
					else
					{
						echo "<p><img src='no_poster.png' width='90%' height='400'/>";

					}
				?>
				<form action="comments.php" method="get">
					<input type="hidden" name="id" value="<?php echo $theID ?>">
					<?php
					if(!isset($item['comments']))
					{
						echo "<input type='submit' value='Add Comments' id='comments'>";
					}
					else
					{
						$comCount = $item['comments'];
						echo "<input type='submit' value='$comCount Comments' id='comments'>";
					}
				    ?>
				</form>
			</div>
		<div class='col-65'>
			<div class='row'>
				<div class='col-25'>
					<b>TITLE:</b>
				</div>
				<div class='col-75'>
					<?php
						if (is_string($item['title']))
						{
							echo isset($item['title']) ? $item['title'] : "No title found!";
						}
						else
						{
							echo "No title found!";
						}
					?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>YEAR:</b>
				</div>
				<div class='col-75'>
					<?php echo isset($item['year']) ? $item['year'] : "No year found!";?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>IMDBID:</b>
				</div>
				<div class='col-75'>
					<?php echo isset($item['imdbId']) ? $item['imdbId'] : "No imdbId found!" ;?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>DIRECTOR:</b>
				</div>
				<div class='col-75'>
					<?php echo isset($item['director']) ? $item['director'] : "No Director found!"; ?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>CAST:</b>
				</div>
				<div class='col-75'>
					<?php
					if (isset($item['cast']))
					{
						foreach ($item['cast'] as $entry) {
							echo $entry."<br>";
						}
					}
					else
					{
						echo "No cast found!";
					}
					?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>GENRE:</b>
				</div>
				<div class='col-75'>
					<?php echo isset($item['genre']) ? $item['genre'] : "No Genre found!";?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>MPAA RATING:</b>
				</div>
				<div class='col-75'>
						<?php echo isset($item['mpaaRating']) ? $item['mpaaRating'] : "No mpaa Rating found!";?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>RUNTIME:</b>
				</div>
				<div class='col-75'>
						<?php echo isset($item['runtime']) ? $item['runtime']." Mins" : "No Runtime found!";?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>LANGUAGE:</b>
				</div>
				<div class='col-75'>
						<?php echo isset($item['language']) ? $item['language'] : "No Language found!";?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>VIEWER VOTES:</b>
				</div>
				<div class='col-75'>
						<?php echo isset($item['viewerVotes']) ? $item['viewerVotes'] : "No Viewer Votes found!";?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>VIEWER RATING:</b>
				</div>
				<div class='col-75'>
					<?php
						if (! is_object(isset($item['viewerRating']) ? $item['viewerRating'] : Null))
						{
							echo isset($item['viewerRating']) ? $item['viewerRating'] : "No Viewer Rating found!";
						}
						else
						{
							if (isset($item['viewerRating']))
								{
									foreach ($item['viewerRating'] as $entry)
									{
										echo $entry."<br>";
									}
								}
								else
								{
									echo "No Viewer Rating found!";
								}
						}
					?>
				</div>
			</div>
			<p>
			<div class='row'>
				<div class='col-25'>
					<b>PLOT:</b>
				</div>
				<div class='col-75'>
						<?php echo isset($item['plot']) ? $item['plot'] : "No plot found!";?>
				</div>
			</div>
			</div>
		</div>
		</div>
	</div>


</body>
</html>
