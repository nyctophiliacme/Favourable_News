<!DOCTYPE html>
<script src="js/autobahn.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
    var conn = new ab.Session('ws://localhost:8084',
        function() {
            // console.log("client");
            conn.subscribe('news', function(topic, data) {
                // console.log("In news");
                // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                $("#main").append(data.headlines + "<br/><br/>" + data.content + "<br/><br/>" + data.extra+"<br/><br/><br/>-------------------------<br/><br/><br/>")
                console.log(data.headlines + "\n\n" + data.content + "\n\n" + data.extra+"\n\n\n-------------------------\n\n\n");
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
</script>
<html>
<head>
    <title>
        
    </title>
</head>
<body>
    <div id="main">
        The news comes here!<br/><br/>
    </div>
</body>
</html>
<?php
    $var = $_POST['url'];
    // echo $json;
    
    
        $urls = json_decode($var);

    // $json = json_encode($urls);
    // $ret = shell_exec("php tempBBC.php serialize($urls)");
        $ret = shell_exec('php tempBBC.php '.escapeshellarg(serialize($urls)));
       // 
        // $json = "Hello";  
    // $ret = shell_exec("php receiver.php $json ");
    // echo "$ret";
?>