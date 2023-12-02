<?php
require("connect-db.php"); // Include your database connection file
require("recipe-db.php"); 

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start(); // Start the session

if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    if (isset($_GET['recipe_id'])) {
        $recipeId = $_GET['recipe_id'];
    } else {
        // Retrieve current recipe details from the database
        if (isset($_GET['recipe_id'])) {
            $recipeId = $_GET['recipe_id'];
            $currentRecipe = getRecipeById($recipeId);
    
            // Check if the recipe exists
            if ($currentRecipe) {
                $current_title = $currentRecipe['title'];
                $current_description = $currentRecipe['description'];
            } else {
                // Handle case where the recipe is not found
                echo "Recipe not found.";
                exit();
            }
        } else {
            // Handle case where recipe_id is not set
            echo "Recipe ID not provided.";
            exit();
        }
    }
    
    $recipe_title = $_POST["recipe_title"];
    $recipe_description = $_POST["recipe_description"];

    updateRecipe($recipeId, $recipe_title, $recipe_description);
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

  <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="author" content="Mary Walton">
  <meta name="description" content="Create Recipe Page">

  <title>Create Recipe Page</title>

  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>
   <!-- Navigation bar KEEP -->
   <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand text-black">Chef Your Way</a>
        <a class=nav-link href="search.php">Search</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"> 
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
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
    <h2>Edit Recipe</h2>
  </div>
  <!-- end main page content -->


  <!-- Copyright Footer KEEP -->
  <br>
  <footer class="text-center text-lg-start" style="background-color: #AFCFFF">
    <div class="text-center p-3">
      © 2023 Copyright: Chef Your Way
    </div>
  </footer>
  <!-- end footer -->


</body>

<script>
</script>

</html>