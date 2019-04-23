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
	<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Movie Title Search (Name includes)
 </div>
<div class="inner">
 <form action="<?php $_SERVER['PHP_SELF'];?>" method="get">
    <div class="row">
      <div class="col-25">
        <label for="iTitle">Please enter partial movie title</label>
      </div>
      <div class="col-75">
        <input type="text" id="iTitle" name="title" placeholder="Partial Movie Title.."  autofocus>
      </div>
    </div><br><br>
    <div class="row">
		<div class="col-75">
			<?php
			if (!empty($_GET['title']))
				{
					echo "Title includes : <font size='5'><b>".$_GET['title']."</b></font>";
					echo "<div id='count'></div>";
				}
			?>
		</div>
		<input type="submit" value="Search">
    </div>
  </form>
 <?php
	require_once "vendor/autoload.php";
	if (!empty($_GET['title']))
	{
		$param = $_GET['title'];
		$count = 0;
		$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$_SESSION["BackLink"] = "searchTitle.php?page=$page&title=".$param;
		$limit = 20;
		$skip  = ($page - 1) * $limit;
		$next  = ($page + 1);
		$prev  = ($page - 1);
		$range = 10;
		$start_movement = 7;
		$start_offset = ceil($range / 2);

		$search = new MongoDB\BSON\Regex($_GET['title'],"i");
		/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
			1.	Connect to the "Movies" collection in the "Video" database
			2.	Find all documents that "title" is equal to "$search", sorted by "title" in ascsending order, where limit is "$limit", skip by "$skip" and store results in "$result"
			3.	Count number of documents where "title" is equal to "$search" and store result in "$total"
		*/

		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->Video->Movies;

		$result = $collection->find(
			array("title" => $search),
				[
	        'limit' => $limit,
	        'sort' => ['title' => 1],
					'skip' =>$skip
	    ]
	);

	$total = $collection->countDocuments(
		array("title" => $search)
	);

		$total_num_pages = ceil($total / $limit);
		echo "<table><tr><th>ImdbId</th><th>Movie Title</th><th>Genre</th><th>Year</th></tr>";
		foreach ($result as $entry) {
			$id =  $entry['_id'];
			$genre = isset($entry['genre']) ? $entry['genre'] : '';
			$imdbId = isset($entry['imdbId']) ? $entry['imdbId'] : '';
			$title = isset($entry['title']) ? $entry['title'] : '';
			$year = isset($entry['year']) ? $entry['year'] : '';
			$poster = 	isset($entry['PosterPath']) ? 1 : 0;
			echo "<tr id =".($count % 2 ? 'whiterow' : '')."><td><a href='details.php?id=$id'>".$imdbId.($poster == 1 ? '*' : '')."</a></td><td>".$title."</td><td>".$genre."</td><td>".$year."</td><tr>";
			$count++;
		}


		echo "</table>";
		echo "<div class='pagination'>";
		if ($page >= $start_movement)
		{
			$start = $page-$start_offset;
		}
		else
		{
			$start = 1;
		}
		if ($page != 1)
		{
			echo '<a href="?page='.$prev.'&title='.$param.'">Previous</a>';
		}
		if (($page < $total_num_pages - $start_offset) && ($total_num_pages > $start+$range))
		{
			for ($x=$start;$x<=$start+$range;$x++)
			{
				if ($x > 0)
				{
					echo ' <a href="?page='.$x.'&title='.$param.'" class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
				}
			}
		}
		else
		{
			for ($x=$total_num_pages-$range;$x<=$total_num_pages;$x++)
			{
				if ($x > 0)
				{
					echo ' <a href="?page='.$x.'&title='.$param.'"  class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
				}
			}
		}
		if($page * $limit < $total)
		{
			echo ' <a href="?page='.$next.'&title='.$param.'">Next</a>';
		}
		echo "</div>";
	}

 ?>
</div>
</div>

<script>
	document.getElementById('count').innerHTML = "Number of records found <font size='4'><b>"+<?php echo $total; ?>+"</b></font>";
</script>
</body>
</html>
