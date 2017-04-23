<?php
	$headline = $_POST['headline'];
	$content = $_POST['content'];
	$extra = $_POST['extra'];
	$urlToImg = $_POST['urlToImg'];
	
	// echo "$headline<br/><br/><br/>";
	// echo "$content<br/><br/><br/>";
	// echo "$extra<br/><br/><br/>";
	
	// echo "Hello";
?>
<!DOCTYPE html>
<html>
<head>
    <title>
        
    </title>
    <script src="js/autobahn.js"></script>
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
            ICON Comes here
        </div>
        <div id="logos">
            Social Media Icons
        </div>
        <div id="login-icon">
          Login
        </div>
        <div id="signup-icon">
          Signup
        </div>
    </div>
    <div id="title-header">
         <div id="title">
             Favourable News
         </div>
    </div>

    <div class = "container">
        <div id = "firstSection">
        </div>
        <div id = "secondSection">
        	<h3>
        		<?php echo $headline; ?>
        	</h3>
        	<h6>
        		<?php echo $extra; ?>
        	</h6>
        	<p>
        		<?php echo $content; ?>
        	</p>
        	<p>
        		<?php echo $urlToImg; ?>
        	</p>
        </div>
        <div id = "thirdSection">
        </div>
    </div>
</body>
</html>
