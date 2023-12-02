<?php
require("connect-db.php");
require("recipe-db.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start(); // Start the session

if (!isset($_SESSION['user_id'])) {
  // User is not logged in, redirect to login page
  header("Location: index.html");
  exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // add the recipe to the table and get the recipe id
  $recipeId = createRecipe($_POST['recipe_title'], $_POST['recipe_description']);



  // Process directions
  $instructions = $_POST['instruction'];

  foreach ($instructions as $index => $instruction) {
    insertInstruction($recipeId, $instruction);
  } 
  
  // Process ingredients
  $ingredientNames = $_POST['ingredient_name'];
  $amounts = $_POST['amount'];
  $units = $_POST['unit'];

  foreach ($ingredientNames as $index => $ingredientName) {
    // add ingredient to table and get id
    $ingredientID = insertIngredient($recipeId, $ingredientName);

    // Insert ingredient amounts into the 'ingredients_amounts' table
    $insertAmountQuery = "INSERT INTO `ingredients_amounts` (`recipe_id`, `ingredient_id`, `unit`, `value`) 
    VALUES (:recipe_id, :ingredient_id, :unit, :value)";
    $statement = $db->prepare($insertAmountQuery);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->bindValue(':ingredient_id', $ingredientID);
    $statement->bindValue(':unit', $units[$index]);
    $statement->bindValue(':value', $amounts[$index]);
    $statement->execute();
  }

  createdBy($recipeId, $_SESSION['user_id']);
  // Redirect to a success page or any other page you want
  header("Location: profile.php");
  exit();
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

    <!-- Recipe Creation Form -->
    <div class="text-center">
      <h2>Create Recipe</h2>
      <form action="createRecipe.php" method="post">
          <label for="recipe_title">Title:</label>
          <input type="text" name="recipe_title" required>
          <br>
          <br>

          <label for="recipe_description">Description:</label>
          <textarea name="recipe_description" rows="4" required></textarea>

      <!-- Ingredients section -->
      <div class="ingredient-section">
          <h3>Ingredients:</h3>
          <div class="ingredient-inputs">
            <label for="ingredient_name">Ingredient Name:</label>
            <input type="text" name="ingredient_name[]" required>
  
            <label for="amount">Amount:</label>
            <input type="text" name="amount[]" required>
  
            <label for="unit">Unit:</label>
            <select name="unit[]" required>
              <option value="grams">grams</option>
              <option value="kilograms">kilograms</option>
              <option value="ounces">ounces</option>
              <option value="pounds">pounds</option>
              <option value="milliliters">milliliters</option>
              <option value="liters">liters</option>
              <option value="fluid ounces">fluid ounces</option>
              <option value="gallons">gallons</option>
              <option value="quarts">quarts</option>
              <option value="pints">pints</option>
              <option value="cups">cups</option>
              <option value="tablespoons">tablespoons</option>
              <option value="teaspoons">teaspoons</option>
              <option value="pieces">pieces</option>
              <option value="slices">slices</option>
              <option value="pinch">pinch</option>
              <option value="dash">dash</option>
              <option value="bunch">bunch</option>
              <option value="whole">whole</option>
              <option value="half">half</option>
              <option value="quarter">quarter</option>
            </select>
            <button class="btn btn-primary" type="button" onclick="addIngredient()">Add Ingredient</button>
          </div>
      </div>
      <!-- end ingredients -->

      <!-- Directions section -->
      <div class="direction-section">
          <h3>Directions:</h3>
          <div class="direction-inputs">
            <label for="instruction">Instruction:</label>
            <textarea name="instruction[]" rows="3" required></textarea>
            <button class="btn btn-primary" type="button" onclick="addDirection()">Add Instruction</button>
          </div>
        </div>
      
      <!-- end directions -->

          <button class="btn btn-success" type="submit">Create Recipe</button>
      </form>
    </div>

    <script>
      function addIngredient() {
        // Clone the existing ingredient input fields and append them under the ingredient section
        const ingredientInputs = document.querySelector('.ingredient-section .ingredient-inputs');
        const newIngredientInputs = ingredientInputs.cloneNode(true);
        document.querySelector('.ingredient-section').appendChild(newIngredientInputs);
      }
  
      function addDirection() {
        // Clone the existing direction input fields and append them under the direction section
        const directionInputs = document.querySelector('.direction-section .direction-inputs');
        const newDirectionInputs = directionInputs.cloneNode(true);
        document.querySelector('.direction-section').appendChild(newDirectionInputs);
      }
    </script>

    <!-- end form -->


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