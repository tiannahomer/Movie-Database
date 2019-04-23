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
	$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
	$limit = 20;
	$start_movement = 7;
	$skip  = ($page - 1) * $limit;
	$next  = ($page + 1);
	$prev  = ($page - 1);
	$range = 10;
	$start_offset = ceil($range / 2);
	$_SESSION["BackLink"] = "posters.php?page=$page";

	/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
		1.	Connect to the "Movies" collection in the "Video" database
		2.	Find all documents that "PosterPath" exists, "year" greater than 1900, sorted by "year" in decesending order, where limit is "$limit", skip by "$skip" and store results in "$result"
		3.	Count number of documents where "PosterPath" exists and store result in "$total"
	*/

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->Video->Movies;

	$result = $collection->find(
		array('$and' => array(
  		array("PosterPath" => ['$exists' => true]),
  			array("year" => ['$gt' => 1900])
			)),

			[
        'limit' => $limit,
        'sort' => ['year' => -1],
				'skip' =>$skip
    ]
	);

	$total = $collection->countDocuments(
		array("PosterPath" => ['$exists' => true])
	);

	$total_num_pages = ceil($total / $limit);
	include 'pagenation.php';
	echo "<div class='container_80'>";
		echo "<div id='title'>";
		echo "<a href='index.html'> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Movie with posters";
		echo "</div>";
		foreach ($result as $entry) {
			$id =  $entry['_id'];
			$pic = $entry['PosterPath'];
			echo "<a href='details.php?id=$id'><div class='cardview'><img src='$pic'/></div></a>";
		}
	echo "</div>";
	include "pagenation.php";
 ?>

</body>
</html>
