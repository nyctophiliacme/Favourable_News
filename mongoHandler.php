<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	
	$mng = new MongoDB\Driver\Manager();
	// var_dump($mng);

	// echo "Database mydb selected";
	$val = 5;

	switch($val)
	{
		case 1 : $collection = "theHinduData";
		         break;
		case 2 : $collection = "timesOfIndiaData";
		         break;
		case 3 : $collection = "bbcData";
		         break;
		case 4 : $collection = "cnnData";
		         break;
		case 5 : $collection = "espnCricInfoData";
		         break;
	}
	
	$query = new MongoDB\Driver\Query([]); 

    $rows = $mng->executeQuery("articles.$collection", $query);
    
    foreach ($rows as $row) 
    {
        echo "<h1> $row->headline </h1> ";
        echo "<h3> $row->extraInfo </h3> ";
     	echo "<p> $row->content </p> <br/>";
        echo "<h1>-----------------------------------------------------------------------------------------------------------------------</h1><br/>";
    }
?>