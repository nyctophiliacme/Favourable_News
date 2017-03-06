<!DOCTYPE html>
<html>
<head>
      <script type="text/javascript" src="js/jquery.js"></script>
      <link rel="stylesheet" type="text/css" href="styles.css" /> 
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
            console.log("called");
        
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
                        $('#category-header').css("top","76px");
                    }
                })

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
                console.log("Called   "+n);
                var newsSite, fileName;
                switch(n)
                {
                    case "1": newsSite = "the-hindu";
                              fileName = "theHinduScrap.php";
                              console.log("The Hindu");
                              break;

                    case "2": newsSite = "the-times-of-india";
                              fileName = "timesOfIndiaScrap.php";
                              break;

                    case "3": newsSite = "bbc-news";
                              fileName = "bbcScrap.php";
                              break;
                              
                    case "4": newsSite = "cnn";
                              fileName = "cnnScrap.php";
                              break;
                              
                    case "5": newsSite = "espn-cric-info";
                              fileName = "espnCricInfoScrap.php";
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
                // var strUrl;

                for(var i =0;i<res['articles'].length;i++)
                {
                    url = res['articles'][i]['url'];
                    console.log(url);
                    arr.push(url);
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
                console.log(json);
                // window.location.replace("headlines.php");

                // $.redirectPost("headlines.php", {url : json});
                var f = document.createElement('form');
                f.action=fileName;
                f.method='POST';
                f.target='_blank';

                var i=document.createElement('input');
                i.type='hidden';
                i.name='url';
                i.value=json;
                f.appendChild(i);

                document.body.appendChild(f);
                f.submit();           
            }    
      </script>
</head>
<body>

    <div id="icon-header">
        <div id="icon">
            ICON Comes here
        </div>
        <div id="logos">
            Images come here
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
             TItle Comes Here
         </div>
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