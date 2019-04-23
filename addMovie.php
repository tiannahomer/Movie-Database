<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
	require_once "vendor/autoload.php";
	if (!empty($_POST['title']))
	{
			$theArray = ['title' => $_POST['title']];
			if (!empty($_POST['year']))
				{
				$theArray['year'] = (int)$_POST['year'];
				}
			if (!empty($_POST['imdb']))
				{
				$theArray['imdbId'] = $_POST['imdb'];
				}
			if (!empty($_POST['cast']))
				{
				$theArray['cast'] = explode(",",$_POST['cast']);
				}
			if (!empty($_POST['poster']))
				{
				$theArray['PosterPath'] = $_POST['poster'];
				}
			//ARRAY POPULATING HAVE TO BE COMPLETED BY YOU I.E ADD ANY REMAINING FIELDS

			if (!empty($_POST['mpaa']))
				{
				$theArray['viewerRating'] = $_POST['mpaa'];
				}
			if (!empty($_POST['genre']))
				{
				$theArray['genre'] = $_POST['genre'];
				}
			if (!empty($_POST['vvotes']))
				{
				$theArray['viewerVotes'] = $_POST['vvotes'];
				}
			if (!empty($_POST['runTime']))
				{
				$theArray['runtime'] = $_POST['runTime'];
				}
			if (!empty($_POST['director']))
				{
				$theArray['director'] = $_POST['director'];
				}
			if (!empty($_POST['plot']))
				{
				$theArray['plot'] = $_POST['plot'];
				}


			/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
				1.	Connect to the "Movies" collection in the "Video" database
				2.	Insert the "$theArray" into the collection
			*/
			$client = new MongoDB\Client("mongodb://localhost:27017");
			$collection = $client->Video->Movies;

			$insertOneResult = $collection->insertOne($theArray);

			if($insertOneResult->getInsertedCount())
			{
				echo "<div id='msg'>";
				echo "Movie document added successfully!";
				echo "</div>";
	        }
	}

?>
<div class="container_50">
  <div id="title">
	<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Add Movie Document
 </div>
 <div class="inner">
  <form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
    <div class="row">
      <div class="col-25">
        <label for="iTitle">Title</label>
      </div>
      <div class="col-75">
        <input type="text" id="iTitle" name="title" placeholder="Movie Title.." autofocus>
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="iYear">Release Year</label>
      </div>
      <div class="col-75">
        <input type="text" id="iYear" name="year" placeholder="Release Year..">
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label for="iImdb">Imdb ID</label>
      </div>
      <div class="col-75">
         <input type="text" id="iImdb" name="imdb" placeholder="imdb ID..">
      </div>
    </div>
	<div class="row">
		  <div class="col-25">
			<label for="impaa">MPAA Rating</label>
		</div>
		<div class="col-75">
      		<select id="impaa" name="mpaa">
				<option value=""></option>
				<option value="NOT RATED">Not Rated</option>
				<option value="R">R</option>
				<option value="PG">PG</option>
				<option value="PG">PG-13</option>
			</select>
		</div>
	</div>
    <div class="row">
      <div class="col-25">
        <label for="igenre">Genre</label>
      </div>
      <div class="col-75">
			<select id="igenre" name="genre" >
				<option value=""></option>
				<option value="Comedy">Comedy</option>
				<option value="Thriller">Thriller</option>
				<option value="Drama">Drama</option>
				<option value="Action">Action</option>
				<option value="Drama">War</option>
				<option value="Action">Western</option>
				<option value="Action">Animation</option>
				<option value="Action">Musical</option>
			</select>
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label for="ivvotes">Viewer Votes</label>
      </div>
      <div class="col-75">
         <input type="text" id="ivvotes" name="vvotes" placeholder="Viewer Votes..">
      </div>
    </div>
	<div class="row">
      <div class="col-25">
			<label for="irunTime">Run Time</label>
      </div>
      <div class="col-75">
			<input type="text" id="irunTime" name="runTime" placeholder="Run Time..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
			<label for="iDirector">Director</label>
      </div>
      <div class="col-75">
			<input type="text" id="iDirector" name="director" placeholder="Director..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
			<label for="iCast">Cast (comma seperated list)</label>
      </div>
      <div class="col-75">
			<input type="text" id="iCast" name="cast" placeholder="Cast..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
			<label for="iPlot">Poster Path</label>
      </div>
      <div class="col-75">
			<input type="text" id="iPoster" name="poster" placeholder="Poster Path..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="iPlot">Movie Plot</label>
      </div>
      <div class="col-75">
        <textarea id="iPlot" name="plot" placeholder="Write something.." style="height:200px"></textarea>
      </div>
    </div><br>
    <div class="row">
      <input type="submit" value="Submit">
    </div>
  </form>
</div>
</div>

</body>
</html>
