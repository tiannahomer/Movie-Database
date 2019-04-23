<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<div class="container_50">
<div id="title">
	<a href="index.html"> <<&nbsp;&nbsp;&nbsp;&nbsp;</a>Star Search (Partial name....)
</div>
<div class="inner">
  <form action="<?php $_SERVER['PHP_SELF'];?>" method="get">
    <div class="row">
      <div class="col-25">
        <label for="iStar">Star name includes</label>
      </div>
      <div class="col-75">
        <input type="text" id="iStar" name="star" placeholder="Star Name.."  autofocus>
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label for="iPostion">Position in Cast list</label>
      </div>
      <div class="col-75">
        <div class="radios">
			<input type="radio" name="position" value="-1" checked="checked"> Any Position&emsp;
			<input type="radio" name="position" value="0"> First&emsp;&emsp;&emsp;
			<input type="radio" name="position" value="1"> Second&emsp;&emsp;&emsp;
			<input type="radio" name="position" value="2"> Third&emsp;&emsp;&emsp;
			<input type="radio" name="position" value="3"> Fourth&emsp;&emsp;&nbsp;
			<input type="radio" name="position" value="4"> Fifth<br>
		</div>
	  </div>
    </div><br>

    <div class="row">
		<div class="col-75">
			<?php
			if (!empty($_GET['star']))
				{
					echo "Star name includes : <b>".$_GET['star']."</b><br>";
					switch ($_GET['position'])
					{
					case "0":
						echo "Star in first position in the cast list";
						break;
					case "1":
						echo "Star in <b>second</b> position in the cast list";
						break;
					case "2":
						echo "Star in <b>third</b> position in the cast list";
						break;
					case "3":
						echo "Star in <b>fourth</b> position in the cast list";
						break;
					case "4":
						echo "Star in <b>fifth</b> position in the cast list";
						break;
					default:
						echo "Star in <b>any</b> position in the cast list";
					}
					echo "<div id='count'></div>";
				}
			?>
		</div>
		<input type="submit" value="Search">
    </div>
  </form>
 <?php
	require_once "vendor/autoload.php";
	if (!empty($_GET['star']))
	{
		$param = $_GET['star'];
		$position = $_GET['position'];
		$count = 0;
		$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$limit = 20;
		$skip  = ($page - 1) * $limit;
		$next  = ($page + 1);
		$prev  = ($page - 1);
		$range = 10;
		$start_movement = 7;
		$start_offset = ceil($range / 2);
		$search = new MongoDB\BSON\Regex($_GET['star'],"i");

		/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
			1.	Connect to the "Movies" collection in the "Video" database
			2.	Find all documents where "cast" is equal to "$search", sorted by "title" in ascsending order, where limit is "$limit", skip by "$skip" and store results in "$result"
			3.	Count number of documents where "cast" is equal to "$search" and store result in "$total"
		*/

		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->Video->Movies;

		$result = $collection->find(
			array($position == '-1' ? 'cast' : 'cast.'.$position => $search),
				[
					'sort' => ['title' => 1],
	        'limit' => $limit,
					'skip' =>$skip
	    ]
	);

	$total = $collection->countDocuments(
		array($position == '-1' ? 'cast' : 'cast.'.$position => $search)
	);


		$total_num_pages = ceil($total / $limit);
		echo "<table><tr><th>ImdbId</th><th>Movie Title</th><th>Cast</th><th>Year</th></tr>";
		foreach ($result as $entry) {
			$imdbId = isset($entry['imdbId']) ? $entry['imdbId'] : '';
			$title = isset($entry['title']) ? $entry['title'] : '';
			$year = isset($entry['year']) ? $entry['year'] : '';
			$cast = "";
			if (isset($entry['cast']))
				{
					foreach ($entry['cast'] as $entry) {
						$cast = $cast.$entry.", ";
					}
				}
			echo "<tr id =".($count % 2 ? 'whiterow' : '')."><td>".$imdbId."</td><td>".$title."</td><td>".$cast."</td><td>".$year."</td><tr>";
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
			echo '<a href="?page='.$prev.'&star='.$param.'&position='.$position.'">Previous</a>';
		}
		if (($page < $total_num_pages - $start_offset) && ($total_num_pages > $start+$range))
		{
			for ($x=$start;$x<=$start+$range;$x++)
			{
				if ($x > 0)
				{
					echo '<a href="?page='.$x.'&star='.$param.'&position='.$position.'" class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
				}
			}
		}
		else
		{
			for ($x=$total_num_pages-$range;$x<=$total_num_pages;$x++)
			{
				if ($x > 0)
				{
					echo '<a href="?page='.$x.'&star='.$param.'&position='.$position.'" class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
				}
			}
		}
		if($page * $limit < $total)
		{
			  echo ' <a href="?page='.$next.'&star='.$param.'&position='.$position.'">Next</a>';
		}
		echo "</div>";
	}

 ?>
</div>
</div>
<script>
	document.getElementById('count').innerHTML = "Number of movies found <font size='4'><b>"+<?php echo $total; ?>+"</b></font>";
</script>
</body>
</html>
