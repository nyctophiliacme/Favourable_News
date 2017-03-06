<?php
      // header("Content-Type: application/json; charset=UTF-8");
      // $obj = json_decode($_POST["x"],false);

      // $obj = file_get_contents('php://input');
      $data = json_decode(file_get_contents('php://input'),true);
      // echo $data;
      // echo $data["username"];
      // echo $obj;
      $username = $data["username"];
      $password = $data["password"];

      // echo $username;
      // echo $password;
      $con = mysqli_connect("localhost","root","NyCtOpHiLiAc","hsdb");
       if (!$con)
       {
          die('Could not connect: ' . mysql_error());
       }
      

      // $username = $_POST['username'];
      // echo $username;

      // $password = $_POST['password'];
      // echo $password;

      $query = "select * from loginDetails where username like '$username' and password like '$password'";
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) 
      {
         echo "success"; 
      } 
      else 
      {
         echo "wrong";
      }
      mysqli_close($con);
?>