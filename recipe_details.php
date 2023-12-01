<?php
    require("connect-db.php"); // Include your database connection file
 
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    session_start(); // Start the session

    if (!isset($_SESSION['user_id'])) {
        // User is not logged in, redirect to login page
        header("Location: index.html");
        exit();
    }

    // Check if the recipe_id is provided in the URL
    if (!isset($_GET['recipe_id'])) {
        // Redirect to a page with an error message or go back to the recipe list
        header("Location: profile.php");
        exit();
    }

    // Fetch recipe details based on the provided recipe_id
    $recipeId = $_GET['recipe_id'];
    $query = "SELECT * FROM recipe WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();
    $recipeDetails = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    // Check if the recipe is found
    if (!$recipeDetails) {
        // Redirect to a page with an error message or go back to the recipe list
        header("Location: profile.php");
        exit();
    }

    echo $recipeId;
    echo "before deletion";

    // Check if the delete button is clicked
    if (isset($_POST['delete_recipe'])) {
        // Perform deletion here
        $deleteQuery = "
        DELETE FROM `created_by` WHERE `recipe_id` = ;
        DELETE FROM `ingredients_amounts` WHERE `recipe_id` = :recipe_id;
        DELETE FROM `recipe_directions` WHERE `recipe_id` = :recipe_id;
        DELETE FROM `recipe_ingredients` WHERE `recipe_id` = :recipe_id;
        DELETE FROM `tags` WHERE `recipe_id` = :recipe_id;
        DELETE FROM recipe WHERE recipe_id = :recipe_id";
        $deleteStatement = $db->prepare($deleteQuery);
        $deleteStatement->bindValue(':recipe_id', $recipeId);
        $deleteStatement->execute();
        $deleteStatement->closeCursor();

        // Redirect to the user's profile page after deletion
        header("Location: profile.php");
        exit();
    }

    echo "after delete";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recipeDetails['title']; ?> - Recipe Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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

    <!-- Recipe Details -->
    <div class="container mt-4">
        <h1><?php echo $recipeDetails['title']; ?></h1>
        <p><?php echo $recipeDetails['description']; ?></p>

        <!-- Delete Recipe Button -->
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRecipeModal">
            Delete Recipe
        </button>
    </div>

    <!-- Delete Recipe Modal -->
    <div class="modal" id="deleteRecipeModal" tabindex="-1" aria-labelledby="deleteRecipeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRecipeModalLabel">Confirm Deletion</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this recipe?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_recipe" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->


    <!-- Copyright Footer KEEP -->
    <footer class="text-center text-lg-start fixed-bottom" style="background-color: #AFCFFF">
        <div class="text-center p-3">
            © 2023 Copyright: Chef Your Way
        </div>
    </footer>
    <!-- end footer -->

    <script>
    </script>
</body>
</html>
