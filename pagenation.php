<?php
	
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
		echo '<a href="?page=' . $prev . '">Previous</a>';
	}
	if (($page < $total_num_pages - $start_offset) && ($total_num_pages > $start+$range))
	{
		for ($x=$start;$x<=$start+$range;$x++)
		{	
			if ($x > 0)
			{
				echo ' <a href="?page=' . $x . '" class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
			}
		}
	}
	else
	{
		for ($x=$total_num_pages-$range;$x<=$total_num_pages;$x++)
		{
			if ($x > 0)
			{
				echo ' <a href="?page=' . $x . '"  class = '. ($page == $x ? "active" : "").'>'.$x.'</a>';
			}
		}	
	}
	if($page * $limit < $total) 
	{
	    echo ' <a href="?page=' . $next . '">Next</a>';
	}
	echo "</div>";
?>