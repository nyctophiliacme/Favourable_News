<?php
    $var = $_POST['url'];
    $paper = $_POST['newsPaper'];
    $imgs = $_POST['imgs'];
    echo "$paper<br/>";
    echo "$imgs<br/>";
    
    $urls = json_decode($var);
    $urlImgs = json_decode($imgs);
    echo "<br/>$urls";
    echo "<br/>$urlImgs";
    //     $fileName = "";
    switch($paper)
    {
        case "the-hindu": 
            $fileName = "serverTheHindu.php";
            // echo "$fileName 456"; 
            break;

        case "the-times-of-india":
            $fileName = "serverTheTimes.php";
            break;

        case "bbc-news": 
            $fileName = "serverBbc.php";
            // echo "$fileName 456"; 
            break;

        case "cnn":
            $fileName = "serverCnn.php";
            break;

        case "espn-cric-info":
            $fileName = "serverEspn.php";
            break; 
    }
    
    echo $fileName;

    // $json = json_encode($urls);
    // $ret = shell_exec("php tempBBC.php serialize($urls)");
        $ret = shell_exec('php '.$fileName.' '.escapeshellarg(serialize($urls)).' '.escapeshellarg(serialize($urlImgs)));
       
       echo $ret;
       echo "Error";
        // $json = "Hello";  
    // $ret = shell_exec("php receiver.php $json ");
    // echo "$ret";
    // echo "Hello";
?>