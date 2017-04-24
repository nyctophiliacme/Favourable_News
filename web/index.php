<?php
  shell_exec("php push-server.php");
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
      <link rel="shortcut icon" href="imgs/title.jpg" />
      <script type="text/javascript" src="js/jquery.js"></script>
      <script type="text/javascript" src="js/jqueryForm.js"></script>
      <link rel="stylesheet" type="text/css" href="css/styles.css" /> 
      <script type="text/javascript">
      
        var newIndex = 0,curIndex = 0,temp = 0;
        // autoSlider();
        // hoverHandler();
      
        var autoHelper = function()
        {
            changeImage(1);
        }

        function changeImage(n)
        {
            if(n>0)
                {
                    $('#right-button').css("pointer-events","none");
                    rightImage(newIndex += n);
                }
            else
                {
                    $('#left-button').css("pointer-events","none");
                    leftImage(newIndex += n);
                }
        }

        function rightImage(n)
        {
            // alert("afasdf");
            // console.log("called");
        
            var x = $(".images");
            // console.log(x);
            if( n >= x.length)
            {
                newIndex = 0;
            }
            curIndex = newIndex - 1;
            if(curIndex < 0)
            {
                curIndex = x.length-1;
            }
            // console.log(slideIndex+"  "+prevIndex);              

            x.eq(curIndex).css({left: 0});
            x.eq(newIndex).css({left: "100%"});

            x.eq(curIndex).animate({left: "-100%"},1000);
            x.eq(newIndex).animate({left: 0},1000);

            setTimeout(function()
                {
                    $('#right-button').css("pointer-events","auto");
                },1000);

        }
        var interval = setInterval(autoHelper,4000);
        

        function leftImage(n)
        {
            // alert("afasdf");
        
            var x = $(".images");
            // console.log(x);
            if(n < 0)
            {
                newIndex = x.length - 1;
            }
            curIndex = newIndex + 1;
            if(curIndex >= x.length)
            {
                curIndex = 0;
            }
            // console.log(slideIndex+"  "+prevIndex);              

            x.eq(curIndex).css({left:0});
            x.eq(newIndex).css({left:"-100%"});

            x.eq(curIndex).animate({left: "100%"},1000);
            x.eq(newIndex).animate({left: 0},1000);
            setTimeout(function()
                {
                    $('#left-button').css("pointer-events","auto");
                },1000);
        }
        $(document).ready(function() {
                $('.images').hover(function()
                {
                    clearInterval(interval);
                    // console.log("hover");
                },function(){
                    interval = setInterval(autoHelper,4000);
                });
                $('.left-right-buttons').hover(function()
                {
                    clearInterval(interval);
                },function()
                {
                    interval = setInterval(autoHelper,4000);
                });


                $('#login-icon').click(function()
                {
                     $('#login').css("display","block");
                     $('#coverup').css("display","block");
                     $('#closeButton').css("display","block");
                     $('#username').val('');
                     $('#password').val('');
                });
                $('#signup-icon').click(function()
                {
                     $('#signup').css("display","block");
                     $('#coverup').css("display","block");
                     $('#closeButton').css("display","block");
                     $('#username2').val('');
                     $('#password2').val('');

                });
                $('#closeButton').click(function()
                {
                     $('#login').css("display","none");
                     $('#coverup').css("display","none");
                     $('#closeButton').css("display","none");
                     $('#errorMessage').css("visibility","hidden");
                     $('#signup').css("display","none");
                     $('#errorMessage2').css("visibility","hidden");
                });

                $('#logout').click(function()
                {
                      $.ajax({
                        type: "POST",
                        url: "logout.php",
                        success: function(result)
                        {
                           // alert("asdfa");
                           console.log(result);
                        },
                        error : function(error)
                        {
                           console.log("error");
                        }
                     });
                      location.reload();
                })

                $(window).scroll(function(event)
                {
                    var scroll = $(window).scrollTop();
                    // console.log(scroll);
                    if(scroll>=38)
                    {
                        // console.log("Hurray");
                        $('#title-header').css("position","fixed");
                        $('#title-header').css("top","0");
                        $('#category-header').css("position","fixed");
                        $('#category-header').css("top","38px");
                    }
                    else
                    {
                        $('#title-header').css("position","absolute");
                        $('#category-header').css("position","absolute");
                        $('#title-header').css("top","38px");
                        $('#category-header').css("top","78px");
                    }
                });

        });
        function signupAjax()
        {
            // console.log("Called asdfasdfasdfasfasf");
            var username = $('#username2').val();
            var password = $('#password2').val();
            var dataString = 'username='+username+'&password='+password;
            if(username == "")
            {
               // alert("Please enter the Username");
               $('#errorMessage2').text('Please enter the Username');
               $('#errorMessage2').css("visibility","visible");
               document.getElementById("username2").focus();
            }
            else if(password == "")
            {
               $('#errorMessage2').text('Please enter the Password');
               $('#errorMessage2').css("visibility","visible");
               // alert("Please enter a valid Password");
               document.getElementById("password2").focus();
            }
            else
            {
               $.ajax({
                  type: "POST",
                  url: "signup.php",
                  data : dataString,
                  success: function(result)
                  {
                     // alert("asdfa");
                     console.log(result);
                     if(result=="alreadyExists")
                     {
                        console.log("Already Exists");
                        $('#errorMessage2').text("The above Username already exists");
                        $('#errorMessage2').css("visibility","visible");
                     }
                     if(result=="success")
                     {
                        console.log("success");
                        $('#signup').css("display","none");
                        $('#coverup').css("display","none");
                        $('#closeButton').css("display","none");
                        $('#success').text("Successfully Signed Up");
                        $('#success').css("display","block");
                        $('#errorMessage2').css("visibility","hidden");
                        $('#success').delay(0).fadeOut(5000);
                     }
                  },
                  error : function(error)
                  {
                     console.log("error");
                  }

               });
            }
        }

        function loginAjax()
        {
            // console.log("Called asdfasdfasdfasfasf");
            var username = $('#username').val();
            var password = $('#password').val();
            var dataString = {"username":username,"password":password};
            var dataJson = JSON.stringify(dataString);
            if(username == "")
            {
               // alert("Please enter the Username");
               $('#errorMessage').text('Please enter the Username');
               $('#errorMessage').css("visibility","visible");
               document.getElementById("username").focus();
            }
            else if(password == "")
            {
               // alert("Please enter a valid Password");
               $('#errorMessage').text('Please enter the Password');
               $('#errorMessage').css("visibility","visible");
               document.getElementById("password").focus();
            }
            else
            {
               $.ajax({
                  type: "POST",
                  url: "login.php",
                  data : dataJson,
                  success: function(result)
                  {
                     // alert("asdfa");
                     console.log(result);
                     if(result=="wrong")
                     {
                        console.log("Wrong");
                        $('#errorMessage').css("visibility","visible");
                     }
                     if(result=="success")
                     {
                        console.log("success");
                        $('#login').css("display","none");
                        $('#coverup').css("display","none");
                        $('#closeButton').css("display","none");
                        $('#success').text("Successful!");
                        $('#success').css("display","block");
                        $('#errorMessage').css("visibility","hidden");
                        $('#success').delay(0).fadeOut(5000);
                        location.reload();

                     }
                  },
                  error : function(error)
                  {
                     console.log("error");
                  }

               });
            }
        }
        function siteSelector(n)
           {
                // console.log("Called   "+n);
                var newsSite, fileName;
                switch(n)
                {
                    case "1": newsSite = "the-hindu";
                              // fileName = "theHinduScrap.php";
                              fileName = "tnTheHindu.php"
                              // console.log("The Hindu");
                              break;

                    case "2": newsSite = "the-times-of-india";
                              // fileName = "timesOfIndiaScrap.php";
                              fileName = "tnTheTimes.php";
                              break;

                    case "3": newsSite = "bbc-news";
                              // fileName = "bbcScrap.php";
                              // fileName = "temp.php";
                              fileName = "tnBbc.php";
                              break;
                              
                    case "4": newsSite = "cnn";
                              // fileName = "cnnScrap.php";
                              fileName = "tnCnn.php";
                              break;
                              
                    case "5": newsSite = "espn-cric-info";
                              // fileName = "espnCricInfoScrap.php";
                              fileName = "tnEspn.php";
                              break;
                           
                }              
                var res;
                var key = 'd8c9f0a3c5944d11bd7172f62e4aab6e';
                $.ajax({
                    type: 'GET',
                    url: 'https://newsapi.org/v1/articles?source='+newsSite+'&sortBy=top&apiKey='+key,
                    dataType: 'json',
                    global: false,
                    async: false,
                    success: function (result) {
                        res = result;
                    }
                });
                console.log(res);
                // url = res['results'][0]['geometry']['location']['lat'];
                console.log(res['articles'].length);
                url = res['articles'][0];

                var arr=Array();
                var urlToImage = Array();
                var arrDesc = Array();
                // var strUrl;

                for(var i =0;i<res['articles'].length;i++)
                {
                    url = res['articles'][i]['url'];
                    console.log(url);
                    arr.push(url);
                    img = res['articles'][i]['urlToImage'];
                    urlToImage.push(img);

                    desc = res['articles'][i]['description'];
                    arrDesc.push(desc);
                    // strUrl += url;
                }
                console.log(arr);
                // console.log(strUrl);
                // for(var i=0;i<arr.length;i++)
                // {
                // console.log(arr[i]);
                // }

                // var temp = {"url": strUrl};

                var json = JSON.stringify(arr);
                var imgJson = JSON.stringify(urlToImage);
                var descJson = JSON.stringify(arrDesc);
                console.log(json);
                // console.log("URLs TO IMages************************");
                console.log(descJson);
                // window.open('temp.php', '_blank');
                // fileName = "thumbnails.php";
                window.open(fileName, '_blank');
                
                // window.location.replace("hoverHandlerines.php");

                // $.redirectPost("headlines.php", {url : json});
                
                // var f = document.createElement('form');
                // f.id = 'idForm';
                // f.action=fileName;
                // f.method='POST';
                // f.target='_blank';

                // $("#idForm").prop("target", "_blank");
                // $("#idForm").prop("action", "serverMaster.php");
                // $("#idForm").prop("method", "POST");
                 
                var i=document.createElement('input');
                i.type='hidden';
                i.name='url';
                i.value=json;

                var j = document.createElement('input');
                j.type = 'hidden';
                j.name = 'newsPaper';
                j.value = newsSite;

                var k = document.createElement('input');
                k.type = 'hidden';
                k.name = 'imgs';
                k.value = imgJson;

                var l = document.createElement('input');
                l.type = 'hidden';
                l.name = 'desc';
                l.value = descJson;
                // f.appendChild(i);

                document.getElementById("idForm").appendChild(i);
                document.getElementById("idForm").appendChild(j);
                document.getElementById("idForm").appendChild(k);
                document.getElementById("idForm").appendChild(l);                

                // document.body.appendChild(f);
                // f.submit();
                // $("#idForm").ajaxSubmit({url: 'temp.php', type: 'post'});
                $("#idForm").ajaxSubmit({url: 'serverMaster.php', type: 'post'});
                // $("#idForm").submit();
                
                // f.ajaxSubmit({url: 'temp.php', type: 'post'});
                // <?php
                //   shell_exec("php temp.php $json");
                // ?>
                
                
                // shell_exec("php push-server.php");
                // shell_exec("php tempBBC.php $json"); 

            }    
      </script>
      <title>
          Proton News
      </title>
</head>
<body>
    <form id = "idForm" method = "POST" ></form>
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
        <?php
          if(isset($_SESSION['uname']))
          {?>
            <div id = "logged-in">
              Welcome
            <?php echo $_SESSION['uname']; ?>
            </div>
          <?php
            }
              else
              { ?>
                <div id="login-icon">
                  Login
                </div>
                <div id="signup-icon">
                  Signup
                </div>
              <?php } ?>
   
    </div>
    <div id="title-header">
         <div id="title">
             Your daily source for Positive, Uplifting and Inspiring Stories from around the world!
         </div>
         <?php if(isset($_SESSION['uname']))
         {?>
            <div id="logout">
              Logout
            </div>

         <?php } ?>
    </div>
    <div id="category-header">
    <ul>
       <li id="1" onclick="siteSelector(this.id)">The Hindu</li><!--
       --><li id="2" onclick="siteSelector(this.id)">Times of India</li><!--
       --><li id="3" onclick="siteSelector(this.id)">BBC</li><!--
       --><li id="4" onclick="siteSelector(this.id)">CNN</li><!--
       --><li id="5" onclick="siteSelector(this.id)">ESPN CricInfo</li>   
    </ul>
    </div>
    <div class = "image-container">
         <img src="imgs/img1.jpg" class="images" id="image1">
         <img src="imgs/img2.jpg" class="images" id="image2">
         <img src="imgs/img3.jpg" class="images" id="image3">
    </div>
    <div class="left-right-buttons" onclick="changeImage(-1)" id="left-button">
        <div>
            &#10094;
        </div>
    </div>
    <div class="left-right-buttons" onclick="changeImage(1)" id="right-button">
        <div>
            &#10095;
        </div>
    </div>


    <div id="login" class="login-signup">
        <h4 style="text-align: center;">Login</h4>
          <!-- <form name="loginForm" onsubmit="loginAjax()"> -->
              <table>
                  <tr>
                    <td>Username</td>
                    <td><input type="text" id="username" placeholder="Enter your username"></td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td><input type="password" id="password" placeholder="Enter your Password"></td>
                  </tr>
              </table>
              <div id="errorMessage">Wrong Username or Password</div>
              <div id="submit-button"><input type="button" value="Submit" onclick="loginAjax()"></div>    
          </form>
    </div>


<!-- ...................................................................................-->
    <div id="signup" class="login-signup">
        <h4 style="text-align: center;">Signup</h4>
          <!-- <form action="index.php" method="post"> -->
              <table>
                  <tr>
                    <td>Username</td>
                    <td><input type="text" id="username2" placeholder="Enter the username"></td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td><input type="password" id="password2" placeholder="Enter Password"></td>
                  </tr>
              </table>
              <div id="errorMessage2">Wrong Username or Password</div>
              <div id="submit-button"><input type="button" value="Submit" onclick="signupAjax()"></div>
                  
          </form>
    </div>
    <div id="coverup"></div>
    <div id="closeButton"></div>
    <div class='login-message' id='success'>Successful!</div>
</body>
</html>