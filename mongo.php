<?php
	// $m = new Mongo();
	// $f = new MongoClient();
	// phpinfo(); 
	// echo extension_loaded("mongodb") ? "loaded\n" : "not loaded\n";
	// echo "asdf";
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	// $m = new MongoDB\Client();
	$headlineData = "Nyctophiliac";
	$contentData = "some Content";
	try
	{	
		$mng = new MongoDB\Driver\Manager();
		// $client = new MongoDB\Client("mongodb://localhost:27017");
		// echo "Connection to database successfully";
		// select a database
		// $db = $mongo->articles;
		// var_dump($mng);

		$bulk = new MongoDB\Driver\BulkWrite;
	    
	    $doc = ["_id" => new MongoDB\BSON\ObjectID, "headline" => $headlineData, "content" => $contentData];
	    $bulk->insert($doc);
	    // $bulk->update(['name' => 'Audi'], ['$set' => ['price' => 52000]]);
	    // $bulk->delete(['name' => 'Hummer']);
	    
	    $mng->executeBulkWrite('articles.timesOfIndiaData', $bulk);


		// $db = $mongo->selectDatabase('DB');
		// $db = $mongo->selectCollection("articles");
		echo "Database mydb selected";

		$query = new MongoDB\Driver\Query([]); 
     
	    $rows = $mng->executeQuery("articles.timesOfIndiaData", $query);
	    
	    foreach ($rows as $row) {
	    
	        echo "$row->content : $row->headline<br/>";
	    }
    }
	catch (MongoDB\Driver\Exception\Exception $e) 
	{

	    $filename = basename(__FILE__);
	    
	    echo "The $filename script has experienced an error.\n"; 
	    echo "It failed with the following exception:\n";
	    
	    echo "Exception:", $e->getMessage(), "\n";
	    echo "In file:", $e->getFile(), "\n";
	    echo "On line:", $e->getLine(), "\n";    
    }
?>