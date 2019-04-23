<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<div class="container_70">
<div id="title">
	<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Movies grouped and counted by year, sorted in ascending order, year greater than or equal to 1900
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


  /*There is a Bonus 10 Marks if you can add an additional menu item and all the necessary PHP,
  HTML and MongoDB code. That will use MongoGB aggregate function to group and count the movies by year,
  sorted in ascending order and year is greater than or equal to 1900.*/

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->Video->Movies;

  $result = $collection->aggregate([

    ['$match'  => ['year' => ['$gte' => 1900]]],

    ['$group' => ['_id' => '$year', 'count' => ['$sum' => 1]]],

    ['$sort' => ['_id' => 1]]

  ]);

  $total = $collection->count(array("year" => ['$gte' => 1900]));

	$total_num_pages = ceil($total / $limit);

	echo "<table><tr><th>Movie Year</th><th>Total Titles</th></tr>";
	$count = 0;
	foreach ($result as $year) {
		echo "<tr id =".($count % 2 ? 'whiterow' : '')."><td>".$year['_id']."</td><td>".$year['count']."</td><tr>";
		$count++;
	}
	echo "</table>";
	//include "pagenation.php";
   ?>
</div>
</div>
</body>
</html>
