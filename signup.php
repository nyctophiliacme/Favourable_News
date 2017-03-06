<?php
      $con = mysqli_connect("localhost","root","NyCtOpHiLiAc","hsdb");
       if (!$con)
       {
          die('Could not connect: ' . mysql_error());
       }
      $username = $_POST['username'];
      // echo $username;

      $password = $_POST['password'];
      // echo $password;

      $query = "select * from loginDetails where username like '$username'";
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) 
      {
         echo "alreadyExists"; 
         exit;
      }
      else 
      {
         $query = "insert into loginDetails(username,password) values ('$username','$password')";
         mysqli_query($con, $query);
         echo "success";
      }
      mysqli_close($con);
?>