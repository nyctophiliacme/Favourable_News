<?php      
    $urlEncoded = $_POST['url'];
    echo $urlEncoded;

    $urls = json_decode($urlEncoded);
    echo $urls;

    foreach($urls as $url)
    {
       echo "$url<br/><br/>";
    }

    echo "asdfasdf";
?>