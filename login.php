<?php
  require("connect-db.php");
  require("recipe-db.php");
  
  session_start();

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loginBtn'])) {
    userLogin();
  }
?>



<!-- 1. create HTML5 doctype -->
<!DOCTYPE html>
<html>
<style>
  .banner {
    background: url("https://s3-alpha-sig.figma.com/img/d0c1/3ace/f719ec8806ea906f47143c2b20b269d5?Expires=1702252800&Signature=SHCBG4KokAtTlU6tjr4b-ZUx1tbqkBTKSrC93an5KN0LmKCoWgLaLPE-8CjDnScl1e8iVvP74Ajd6rKthGHaCw34et4TqoVAdYaDcb3BYbRHNM~9vcUVY1Vsy1goatiPE-VJVdMsBfx--nre2Oh~WPPqgF0DSrpUFsgzrRKTEUj2aieFRPu3xj5mGcCiWSaSMoXXg-y62J1ZTncHNs-MYbnOy-Kpe9VMcoFcF5BOOYZRBdnWTDQJXyLGwKsSYGJIrLV0XVFEEUuP1mnCIEhR33J7ogt3loIoGlYoBYgiCus7TWc9hbZnqM5fBcWHs31PhZXYJSDC2KdoDo9tF613gg__&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4") 50%;
    background-size: cover;
    display: flex;
    height: 200px;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
  }

  .center {
    padding: 70px 0;
    text-align: center;
  }
</style>

<head>
  <meta charset="UTF-8">

  <!-- 2. meta tag to ensure proper rendering and touch zooming -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="author" content="Mary Walton">
  <meta name="description" content="Login Page">

  <title>Login Page</title>

  <!-- 3. link bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
  <!-- Navigation bar KEEP -->
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand text-black">Chef Your Way</a>
    </div>
  </nav>
  <!-- end navigation bar -->


  <!-- Banner KEEP -->
  <div class="banner">
    <div class="text-center">
      <h1 class="text-white">Find delicious recipes for any occasion!</h1>
    </div>
  </div>
  <!-- end banner -->
  

  <!-- main page content -->
  <div class="container-fluid">
    <h2>Login</h2>
    <!-- Login Form -->
    <form name="loginForm" action="" method="post">
      <div class="row mb-3 mx-3">
        Username:
        <input type="text" class="form-control" name="username" required />
      </div>  
      <div class="row mb-3 mx-3">
        Password:
        <input type="password" class="form-control" name="password" required />
      </div>  
      <div class="row mb-3 mx-3">
        <input type="submit" value="Login" name="loginBtn" class="btn btn-primary" title="Login to your account" />
      </div>  
    </form>
    <!-- End Login Form -->
  </div>
  <!-- end -->


  <!-- Copyright Footer KEEP -->
  <footer class="text-center text-lg-start fixed-bottom" style="background-color: #AFCFFF">
    <div class="text-center p-3">
      © 2023 Copyright: Chef Your Way
    </div>
  </footer>
  <!-- end footer -->


</body>

<script>
</script>

</html>