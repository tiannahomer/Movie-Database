<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
 <?php
	date_default_timezone_set('America/Barbados');
	$theID = $_GET['id'];
	require_once "vendor/autoload.php";

	/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
		1.	Connect to the "Movies" collection in the "Video" database
		2.	Find one record with "_id" field equals to "new MongoDB\BSON\ObjectID($theID)"
		3.	Get "comments" value and store it in "$comCount". Store zero in "$comCount" if comments field is not set
	*/

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$movcollection = $client->Video->Movies;

	$item = $movcollection->findOne(
		array('_id' => new MongoDB\BSON\ObjectID($theID))
	);


	if(isset($item['comments'])){
		$comCount = $item['comments'];
	}
	else{
		$comCount = 0;
	}

?>
<div class="container_50">
	<div id='title'>
		<a href='details.php?id=<?php echo $theID; ?>'>&lt;&lt;&nbsp;&nbsp;&nbsp;&nbsp;</a>Add or View Comments
	</div>
   <div class="inner">
   <form action="<?php $_SERVER['PHP_SELF'];?>" method="post" >
    <div class="row">
	  <div class="col-25">
	  <?php
		if (isset($item['PosterPath']))
		{
			$img = $item['PosterPath'];
			echo "<img src=$img width='90%' height='225'/>";
		}
		else
		{
			echo "<img src='no_poster.png' width='90%' height='225'/>";
			$title = $item['title'];
			echo "<font color='green' size='6'>$title</b></font>";
		}

		?>
	  </div>
	<div class="col-75">
	  <div class="col-25">
		<label for="iUser">Your Name</label>
	  </div>
	  <div class="col-75">
		 <input type="text" id="iUser" name="User" placeholder="Your Name..."  autofocus>
	  </div>
	  <div class="row">
	  </div>
	  <div class="col-25">
		<label for="iComment">Your Comment</label>
	  </div>
	  <div class="col-75">
		<textarea name="comment" rows="9"> </textarea>
	  </div>
	</div>

  </div>
	  <div class="row">
		<br><input type="submit" value="Submit">
	   </div>

  </form>
   </div>
  </div>
  <?php
		require_once "vendor/autoload.php";
		$comment = (isset($_POST['comment']) ? $_POST['comment'] : null );
		$username = (isset($_POST['User']) ? $_POST['User'] : null );
		if ((strlen(trim($comment)) != 0) && (!empty($username)))
		{
			$theArr = ['userName' => $username];
			$theArr['comment'] = $comment;
			$theArr['parentID'] = new MongoDB\BSON\ObjectID($theID);
			$theArr['dateTime'] = date('M,d,Y h:i:s A');

			/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
				1.	Connect to the "Comments" collection in the "Video" database
				2.	Insert the "$theArr" into the "Comments" collection
				3.	Increment the "comments" field of the "Movies" collection form "_id" field equals "new MongoDB\BSON\ObjectID($theID)"
				4.	Get the "comments" value from "Movies" collection after the update, store the amount in "$comCount"
			*/

			$client = new MongoDB\Client("mongodb://localhost:27017");
			$commcollection = $client->Video->Comments;
			$moviescollection = $client->Video->Movies;

			$insertOneResult = $commcollection->insertOne($theArr);

		$updateOneResult = $moviescollection->updateOne(
				array('_id' => new MongoDB\BSON\ObjectID($theID)),
				array('$inc' => array('comments' => 1))
			);

			//$comCount = $item['comments'];
			$comCount = $moviescollection->count(array('_id' => new MongoDB\BSON\ObjectID($theID)), array('comments' => 1));

		}

		if ($comCount > 0)
		{

			$page  = isset($_GET['zpage']) ? (int) $_GET['zpage'] : 1;
			$limit = 10;
			$start_movement = 5;
			$skip  = ($page - 1) * $limit;
			$next  = ($page + 1);
			$prev  = ($page - 1);
			$range = 5;
			$start_offset = ceil($range / 2);

			/*<<MONGODB PHP LIBRARY CODE GOES HERE>>
				1.	Connect to the "Comments" collection in the "Video" database
				2.	Get all records with "parentID" field equals to "new MongoDB\BSON\ObjectID($theID)]" sorted by "_id" in descending order, limit by "$limit" and skip by "$skip"
				3.	Count number of records for "parentID" field equals to "new MongoDB\BSON\ObjectID($theID)" and store value in "$total"
			*/

			$client = new MongoDB\Client("mongodb://localhost:27017");
			$collection3 = $client->Video->Comments;

			$result = $collection3->find(
		  		array("parentID" => new MongoDB\BSON\ObjectID($theID)),

					[
		        'limit' => $limit,
		        'sort' => ['_id' => -1],
						'skip' => $skip
		    ]
			);

			$total = $collection3->count(array("parentID" => new MongoDB\BSON\ObjectID($theID)));


			$total_num_pages = ceil($total / $limit);
			echo "<div class='bar'><b>".$comCount." Previous Comments</b><br><br><br>";
			foreach ($result as $entry)
			{
				$id =  $entry['_id'];
				$userName = isset($entry['userName']) ? $entry['userName'] : '';
				$comment = isset($entry['comment']) ? $entry['comment'] : '';
				$date = isset($entry['dateTime']) ? $entry['dateTime'] : '';
				echo "<div class='comm_title'>";
				echo "<font size='4'><b>$userName</b></font>";
				echo "<div style='float: right'>";
					echo "<font size='2'>".$date."</font>";
				echo "</div>";
				echo "</div>";
				echo "<div class='comm'>";
					echo "$comment";
				echo "</div>";
				echo "<br>";
			}
			echo "</div>";
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
				echo '<a href="?zpage='.$prev.'&id='.$theID.'">Previous</a>';
			}
			if (($page < $total_num_pages - $start_offset) && ($total_num_pages > $start+$range))
			{
				for ($x=$start;$x<=$start+$range;$x++)
				{
					if ($x > 0)
					{
						echo ' <a href="?zpage='.$x.'&id='.$theID.'" class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
					}
				}
			}
			else
			{
				for ($x=$total_num_pages-$range;$x<=$total_num_pages;$x++)
				{
					if ($x > 0)
					{
						echo ' <a href="?zpage='.$x.'&id='.$theID.'"  class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
					}
				}
			}
			if($page * $limit < $total)
			{
				echo ' <a href="?zpage='.$next.'&id='.$theID.'">Next</a>';
			}
			echo "</div>";

		}
  ?>
