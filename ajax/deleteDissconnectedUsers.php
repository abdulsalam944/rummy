<?php
	include("../config.php");
	echo $query='delete from dissconnection_detected_by_bot where room = "'.$_POST['session'].'" ';
	mysql_query($query);
?>