<?php
    require("connect-db.php");
    require("recipe-db.php");

    session_start(); // Start the session

    if (!isset($_SESSION['user_id'])) {
        // User is not logged in, redirect to login page
        header("Location: index.html");
        exit();
    }

    // Check if the recipe_id is set in the URL
    if(isset($_GET['recipe_id'])) {
        $recipeId = $_GET['recipe_id'];

        // Fetch the details of the selected recipe including all instructions
        $query = "SELECT * FROM recipe 
                  LEFT JOIN recipe_directions ON recipe.recipe_id = recipe_directions.recipe_id
                  WHERE recipe.recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':recipe_id', $recipeId);
        $statement->execute();
        $recipeDetails = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
    } else {
        // Redirect to the search page if recipe_id is not set
        header("Location: search.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Mary Walton">
    <meta name="description" content="Recipe Details Page">
    <title>Recipe Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
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

    <div class="container-fluid">

        <!-- Display recipe details -->
        <?php if(isset($recipeDetails) && !empty($recipeDetails)) { ?>
            <h1><?php echo $recipeDetails[0]['title']; ?></h1>
            <p><?php echo $recipeDetails[0]['description']; ?></p>

            <h2>Instructions:</h2>
            <ol class="list-group list-group-numbered">
                <?php foreach ($recipeDetails as $instruction) { ?>
                    <li class="list-group-item"><?php echo $instruction['instruction']; ?></li>
                <?php } ?>
            </ol>
                
        <?php } else { ?>
            <p>No recipe details found.</p>
        <?php } ?>
        <!-- End display recipe details -->

    </div>

    <!-- Copyright Footer KEEP -->
    <footer class="text-center text-lg-start fixed-bottom" style="background-color: #AFCFFF">
        <div class="text-center p-3">
            © 2023 Copyright: Chef Your Way
        </div>
    </footer>
    <!-- end footer -->

</body>

</html>
