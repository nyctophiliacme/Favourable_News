<?php
	$headline = $_POST['headline'];
	$content = $_POST['content'];
	$extra = $_POST['extra'];
	$urlToImg = $_POST['urlToImg'];
    $desc = $_POST['desc'];    

    session_start();
	
    $username='ca41e809-cf53-432f-bcd7-0afce2af4732';
    $password='h07WVQxPeMRL';
    $format = 'audio/wav';
    $pause = "<speak version=\"1.0\">
                <break time=\"1s\"></break>
              </speak>";


    if($desc == "null")
    {
        $data = $headline;
    }
    else
    {
        $data = $headline.$pause.$desc;
    }
    // $data = $headline.$pause.$content;

    // echo "$data";

    $jsonData = json_encode(array('text' => $data));
    $URL='https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?voice=en-US_AllisonVoice';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$URL);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept:'.$format));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    curl_close ($ch);

    $myfile = fopen("test.wav", "w") or die("Unable to open file!");
    fwrite($myfile, $result);
    fclose($myfile);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="imgs/title.jpg" />
    <title>
        Article
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="css/articleStyles.css" /> 
    <script type="text/javascript">

    $(window).scroll(function(event)
                {
                    var scroll = $(window).scrollTop();
                    // console.log(scroll);
                    if(scroll>=38)
                    {
                        // console.log("Hurray");
                        $('#title-header').css("position","fixed");
                        $('#title-header').css("top","0");
                    }
                    else
                    {
                        $('#title-header').css("position","static");
                        $('#title-header').css("top","38px");
                    }
                });
    </script>
</head>
<body>
    <div id="icon-header">
        <div id="icon">
            <img src="imgs/logo.png" height="28px">
        </div>
        <div id="logos">
            <a href="https://www.facebook.com" class="social-icons" target="_blank">
                <img src="imgs/fb.png" height="28px" width="28px" align="top">
            </a>
            <a href="https://www.twitter.com" class="social-icons" target="_blank">
                <img src="imgs/twitter.png" height="28px" width="28px">
            </a>
        </div>
        <div id = "logged-in">
            Welcome
        <?php
          if(isset($_SESSION['uname']))
             
              echo $_SESSION['uname']; 
              
              else

                echo "Guest";

          ?>
                
            </div>
    </div>
    <div id="title-header">
         <div id="title">
             Your daily source for Positive, Uplifting and Inspiring Stories from around the world!
         </div>
    </div>

    <div class = "container">
        <div id = "firstSection">
        </div>
        <div id = "secondSection">
        	<h2>
        		<?php echo $headline; ?>
        	</h2>
        	<img id="pic" src="<?=$urlToImg ?>">
        	<h4>
        		<?php echo $extra; ?>
        	</h4>
        	<p>
        		<?php echo $content; ?>
        	</p>
            <audio controls>
                <source src="test.wav" type="audio/wav">
                <!-- <source src="horse.mp3" type="audio/mpeg"> -->
                Your browser does not support the audio element.
            </audio>
        </div>
        <div id = "thirdSection">
        </div>
    </div>
</body>
</html>
