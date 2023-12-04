<?php
    require("connect-db.php");
    require("recipe-db.php");

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.html");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_GET['recipe_id'])) {
            $recipeId = $_GET['recipe_id'];
            
            $recipe_title = $_POST["recipe_title"];
            $recipe_description = $_POST["recipe_description"];

            // Update recipe details
            $updateRecipeQuery = "UPDATE recipe SET title = :title, description = :description WHERE recipe_id = :recipe_id";
            $statementUpdateRecipe = $db->prepare($updateRecipeQuery);
            $statementUpdateRecipe->bindValue(':title', $recipe_title);
            $statementUpdateRecipe->bindValue(':description', $recipe_description);
            $statementUpdateRecipe->bindValue(':recipe_id', $recipeId);
            $statementUpdateRecipe->execute();
            $statementUpdateRecipe->closeCursor();


            // Update instructions
            if (isset($_POST['direction_ids']) && isset($_POST['instructions'])) {
                $instructionIds = $_POST['direction_ids'];
                $newInstructions = $_POST['instructions'];

                foreach ($instructionIds as $index => $instructionId) {
                    $newInstruction = $newInstructions[$index];
                    $updateInstructionQuery = "UPDATE recipe_directions SET instruction = :instruction WHERE direction_id = :direction_id";
                    $statementUpdateInstruction = $db->prepare($updateInstructionQuery);
                    $statementUpdateInstruction->bindValue(':instruction', $newInstruction);
                    $statementUpdateInstruction->bindValue(':direction_id', $instructionId);
                    $statementUpdateInstruction->execute();
                    $statementUpdateInstruction->closeCursor();
                }
            }
            // Update ingredients
            if (
                isset($_POST['ingredient_ids']) &&
                isset($_POST['ingredient_names'])) {
                $ingredientIds = $_POST['ingredient_ids'];                
                $newIngredientNames = $_POST['ingredient_names'];

                foreach ($ingredientIds as $index => $ingredientId) {
                    $newIngredientName = $newIngredientNames[$index];
                    updateIngredient($ingredientId, $newIngredientName);

                }
            }

            // Update ingredients amounts
            if (
                isset($_POST['ingredient_ids']) &&
                isset($_POST['ingredient_amounts']) 
            ) {
                $ingredientIds = $_POST['ingredient_ids'];
                $newIngredientAmounts = $_POST['ingredient_amounts'];

                foreach ($ingredientIds as $index => $ingredientId) {
                    $newIngredientAmount = $newIngredientAmounts[$index];
                    updateIngredientAmount($ingredientId, $newIngredientAmount);
                }
            }

            // Update ingredients units
            if (
                isset($_POST['ingredient_ids']) &&
                isset($_POST['ingredient_units']) 
            ) {
                $ingredientIds = $_POST['ingredient_ids'];
                $newIngredientUnits = $_POST['ingredient_units']; // Retrieve the posted units

                foreach ($ingredientIds as $index => $ingredientId) {
                    $newIngredientUnit = $newIngredientUnits[$index]; // Get the corresponding unit for the ingredient
                    updateIngredientUnit($ingredientId, $newIngredientUnit);
                }
            }
            

        // Update tags
        if (isset($_POST['tag_ids']) && isset($_POST['tag_names']) && isset($_POST['tag_types'])) {
            $tagIds = $_POST['tag_ids'];
            $newTagNames = $_POST['tag_names'];
            $newTagTypes = $_POST['tag_types'];

            foreach ($tagIds as $index => $tagId) {
                $newTagName = $newTagNames[$index];
                $newTagType = $newTagTypes[$index];
                updateTag($tagId, $newTagName, $newTagType); 
            }
        }

        header("Location: recipe_details.php?recipe_id=" . $recipeId);
        exit();
        }
    }

    if (isset($_GET['recipe_id'])) {
        $recipeId = $_GET['recipe_id'];
        $currentRecipe = getRecipeById($recipeId);

        if (!$currentRecipe) {
            echo "Recipe not found.";
            exit();
        }
    } else {
        echo "Recipe ID not provided.";
        exit();
    }
?>


<!DOCTYPE html>
<html>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 20px;
    }
    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        color: #333;
    }
    label {
        font-weight: bold;
    }
    .mb-3 {
        margin-bottom: 15px;
    }
    .form-control {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .banner {
        background: url("https://s3-alpha-sig.figma.com/img/d0c1/3ace/f719ec8806ea906f47143c2b20b269d5?Expires=1702252800&Signature=SHCBG4KokAtTlU6tjr4b-ZUx1tbqkBTKSrC93an5KN0LmKCoWgLaLPE-8CjDnScl1e8iVvP74Ajd6rKthGHaCw34et4TqoVAdYaDcb3BYbRHNM~9vcUVY1Vsy1goatiPE-VJVdMsBfx--nre2Oh~WPPqgF0DSrpUFsgzrRKTEUj2aieFRPu3xj5mGcCiWSaSMoXXg-y62J1ZTncHNs-MYbnOy-Kpe9VMcoFcF5BOOYZRBdnWTDQJXyLGwKsSYGJIrLV0XVFEEUuP1mnCIEhR33J7ogt3loIoGlYoBYgiCus7TWc9hbZnqM5fBcWHs31PhZXYJSDC2KdoDo9tF613gg__&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4") 50%;
        background-size: cover;
        display: flex;
        height: 200px;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
    }
</style>
    
<head>
    <title>Edit Recipe</title>
    <!-- Bootstrap CSS (you can replace this with your preferred CSS framework or your own styles) -->   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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

    
    <!-- edit form -->
    <div class="container mt-4">
        <h2>Edit Recipe</h2>
        <form method="POST" action="editrecipe.php?recipe_id=<?php echo $recipeId; ?>">
            <!-- Recipe Title -->
            <div class="mb-3">
                <label for="recipe_title" class="form-label">Recipe Title:</label>
                <input type="text" class="form-control" id="recipe_title" name="recipe_title" value="<?php echo htmlspecialchars($currentRecipe['title']); ?>">
            </div>
            <!-- end title -->

            <!-- Recipe Description -->
            <div class="mb-3">
                <label for="recipe_description" class="form-label">Recipe Description:</label>
                <textarea class="form-control" id="recipe_description" name="recipe_description"><?php echo htmlspecialchars($currentRecipe['description']); ?></textarea>
            </div>
            <!-- end description -->

            <!-- Edit instructions -->
            <h3>Edit Instructions</h3>
            <?php
            $instructions = getInstructionsForRecipe($recipeId);
            foreach ($instructions as $instruction) {
                ?>
                <div class="mb-3">
                    <label for="instruction" class="form-label">Instruction:</label>
                    <textarea class="form-control" name="instructions[]"><?php echo htmlspecialchars($instruction['instruction']); ?></textarea>
                    <input type="hidden" name="direction_ids[]" value="<?php echo $instruction['direction_id']; ?>">
                </div>
                <?php
            }
            ?>
            <!-- end instructions -->

            <!-- Edit ingredients -->
            <h3>Edit Ingredients</h3>
            <?php
            $ingredients = getIngredientsForRecipe($recipeId);
            $ingredientAmounts = getIngredientAmountsForRecipe($recipeId); 
            foreach ($ingredients as $ingredient) {
                $currentAmount = null;
                $currentUnit = null;

                // Find the corresponding ingredient amount from fetched amounts based on ingredient ID
                foreach ($ingredientAmounts as $amount) {
                    if ($amount['ingredient_id'] === $ingredient['ingredient_id']) {
                        $currentAmount = $amount;
                        $currentUnit = $amount['unit']; 
                        break;
                    }
                }
               
                ?>
                <div class="mb-3">
                    <label for="ingredient_name" class="form-label">Ingredient Name:</label>
                    <input type="text" class="form-control" name="ingredient_names[]" value="<?php echo htmlspecialchars($ingredient['ingredient_name']); ?>">
                    <!-- Input field for ingredient amounts -->
                    <label for="amount">Amount:</label>
                    <input type="number" class="form-control" name="ingredient_amounts[]" value="<?php echo $currentAmount ? htmlspecialchars($currentAmount['value']) : ''; ?>">

                    <label for="unit">Unit:</label>
                    <select name="ingredient_units[]">
                    <?php
                    // Define an array of units to loop through
                    $units = array(
                        'grams', 'kilograms', 'ounces', 'pounds', 'milliliters', 'liters', 'fluid ounces',
                        'gallons', 'quarts', 'pints', 'cups', 'tablespoons', 'teaspoons', 'pieces', 'slices',
                        'pinch', 'dash', 'bunch', 'whole', 'half', 'quarter'
                    );

                    foreach ($units as $unit) {
                        $selected = ($currentUnit === $unit) ? 'selected' : '';
                        echo "<option value='$unit' $selected>$unit</option>";
                    }
                    ?>
                </select>

                    <input type="hidden" name="ingredient_ids[]" value="<?php echo $ingredient['ingredient_id']; ?>">
                </div>
                <?php
            }
            ?>

            <!-- Edit tags -->
            <h3>Edit Tags</h3>
            <?php
            $tags = getTagsForRecipe($recipeId);
            foreach ($tags as $tag) {
                ?>
                <div class="mb-3">
                    <label for="tag_name" class="form-label">Tag Name:</label>
                    <input type="text" class="form-control" name="tag_names[]" value="<?php echo htmlspecialchars($tag['tag_name']); ?>">
                    <!-- Dropdown for tag types -->
                    <label for="tag_type" class="form-label">Tag Type:</label>
                    <select class="form-control" name="tag_types[]">
                        <option value="dietary restrictions" <?php if ($tag['type'] === 'dietary restrictions') echo 'selected'; ?>>Dietary Restrictions</option>
                        <option value="country of origin" <?php if ($tag['type'] === 'country of origin') echo 'selected'; ?>>Country of Origin</option>
                        <option value="category" <?php if ($tag['type'] === 'category') echo 'selected'; ?>>Category</option>
                        <!-- Add other tag types here -->
                    </select>
                    <!-- Hidden input for tag ID -->
                    <input type="hidden" name="tag_ids[]" value="<?php echo $tag['tag_id']; ?>">
                </div>
                <?php
            }
            ?>

            <!-- Add input fields for other recipe details you want to edit -->

            <button type="submit" class="btn btn-primary">Update Recipe</button>
        </form>
    </div>
    <!-- end edit form -->


    <!-- Copyright Footer KEEP -->
    <br>
    <footer class="text-center text-lg-start" style="background-color: #AFCFFF">
        <div class="text-center p-3">
        © 2023 Copyright: Chef Your Way
        </div>
    </footer>
    <!-- end footer -->
</body>
</html>
