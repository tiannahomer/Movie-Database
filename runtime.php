<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<div class="container_70">
<div id="title">
	<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Greater than 500 minutes runtime and less than or equal to 650 minutes runtime
 </div>
<div class="inner">

  <?php

	$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
	$limit = 20;
	$skip  = ($page - 1) * $limit;
	$next  = ($page + 1);
	$prev  = ($page - 1);
	$range = 10;
	$start_movement = 7;
	$start_offset = ceil($range / 2);



	require_once "vendor/autoload.php";
	/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
		1.	Connect to the "Movies" collection in the "Video" database
		2.	Find all documents that "runtime" is greater than 500 and less than or equals to 650, sorted by "runtime" in ascsending order, where limit is "$limit", skip by "$skip" and store results in "$result"
		3.	Count number of documents where "runtime" is greater than 500 and less than or equals to 650 and store result in "$total"
	*/

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->Video->Movies;

	$result = $collection->find(
		array('$and' => array(
  		array("runtime" => ['$gt' => 500]),
  			array("runtime" => ['$lte' => 650])
			)),

			[
        'limit' => $limit,
        'sort' => ['runtime' => 1],
				'skip' => $skip
    ]
);

	$total = $collection->countDocuments(
		array('$and' => array(
  		array("runtime" => ['$gt' => 500]),
  			array("runtime" => ['$lte' => 650])
			))
	);


	$total_num_pages = ceil($total / $limit);
	echo "<table><tr><th>ImdbId</th><th>Movie Title</th><th>Genre</th><th>Year</th><th>RunTime</th></tr>";
	$count = 0;
	foreach ($result as $entry) {
		echo "<tr id =".($count % 2 ? 'whiterow' : '')."><td>".$entry['imdbId']."</td><td>".$entry['title']."</td><td>".$entry['genre']."</td><td>".$entry['year']."</td><td>".$entry['runtime']."</td><tr>";
		$count++;
	}
	echo "</table>";
	include "pagenation.php";
   ?>
</div>
</div>
</body>
</html>
