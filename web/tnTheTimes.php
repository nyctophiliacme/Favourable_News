<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="imgs/title.jpg" />
    <title>
        The Times of India
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="js/autobahn.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="css/headStyles.css" /> 
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
        var conn = new ab.Session('ws://localhost:8084',
            function() {
                console.log("client");
                // conn.subscribe('newsPaper', function(topic, data) {
                //     console.log("**************");
                //     $("#topic").append(data.paper+"<br/>");
                     
                // });
                conn.subscribe('theTimesNews', function(topic, data) {
                    console.log("In news");

                    // $("#main").append(data.url + "<br/><br/>" + data.headlines + "<br/><br/>" + data.content + "<br/><br/>" + data.extra+"<br/><br/><br/>-------------------------<br/><br/><br/>")

                    $("#secondSection").append("<div class='thumbs'>"+ data.headlines+"<div class='content'>"+data.content+"</div> <div class='extra'>"+data.extra+"</div><div class = 'headline' > "+data.headlines+"</div><div class='urlToImg'>"+data.urlToImg+"</div><div class='desc'>"+data.desc+"</div></div>" );

                    console.log(data.url+"\n\n"+data.headlines + "\n\n" + data.content + "\n\n" + data.extra+"\n\n\n-------------------------\n\n\n");
                });
            },
            function() {
                console.warn('WebSocket connection closed');
            },
            {'skipSubprotocolCheck': true}
        );
    </script>
</head>
<body>
    <form id = "idForm" method = "POST" action="article.php" target="_blank">
        <input type="hidden" name="headline" id="headline">
        <input type="hidden" name="content" id="content">
        <input type="hidden" name="extra" id="extra">
        <input type="hidden" name="urlToImg" id="urlToImg">
        <input type="hidden" name="desc" id="desc">
        
    </form>
    </form>
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
        </div>
        <div id = "thirdSection">
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
        // $("#secondSection").append("<div class=\"thumbs\"> Hello How do you do!<div class='headline'>Hello How do you do!Hello How do you do!Hello How do you do!Hello How do you do!Hello How do you do!Hello How do you do!Hello How do you do!Hello How do you do!</div> </div>");

        // $("#secondSection").append("<div class=\"thumbs\"> Hello How do you do!<div class='content'>asdfasdfdo!Hello How do you do!Hello How do you do!Hello How do you do!Hello How do you do!</div> </div>");

        console.log("script Runnning");

        $('body').on('click', '.thumbs', function() 
        {
            console.log($(this).children(".desc").html());
            console.log("Hello");

            document.getElementById("headline").value = $(this).children(".headline").html();
            document.getElementById("content").value = $(this).children(".content").html();
            document.getElementById("extra").value = $(this).children(".extra").html();
            document.getElementById("urlToImg").value = $(this).children(".urlToImg").html();
            document.getElementById("desc").value = $(this).children(".desc").html();            

            $("#idForm").submit();
            // document.getElementById("idForm").appendChild(j);

        });

    // $(".thumbs").click(function(){
    //         console.log($(this).children(".content").html());
    //         console.log("Hello");
    //     });

    </script>