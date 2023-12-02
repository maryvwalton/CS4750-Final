<!DOCTYPE html>
<html>
<head>
    <title>Edit Recipe</title>
    <!-- Bootstrap CSS (you can replace this with your preferred CSS framework or your own styles) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
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
    </style>
</head>
<body>
</body>
</html>

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

        // ...

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
                // If the update didn't work, you can debug by checking for errors here:
                // var_dump($statementUpdateInstruction->errorInfo());
                $statementUpdateInstruction->closeCursor();
            }
        }
// ...


        // Update ingredients and their amounts
        if (
            isset($_POST['ingredient_ids']) &&
            isset($_POST['ingredient_names']) &&
            isset($_POST['ingredient_amounts']) &&
            isset($_POST['ingredient_units']) &&
            isset($_POST['recipe_id'])
        ) {
            $ingredientIds = $_POST['ingredient_ids'];
            $newIngredientNames = $_POST['ingredient_names'];
            $newIngredientAmounts = $_POST['ingredient_amounts'];

            foreach ($ingredientIds as $index => $ingredientId) {
                $newIngredientName = $newIngredientNames[$index];
                $newIngredientAmount = $newIngredientAmounts[$index];

                // Update ingredient name
                $updateIngredientNameQuery = "UPDATE recipe_ingredients SET ingredient_name = :ingredient_name WHERE ingredient_id = :ingredient_id";
                $statementUpdateIngredientName = $db->prepare($updateIngredientNameQuery);
                $statementUpdateIngredientName->bindValue(':ingredient_name', $newIngredientName);
                $statementUpdateIngredientName->bindValue(':ingredient_id', $ingredientId);
                $statementUpdateIngredientName->execute();
                $statementUpdateIngredientName->closeCursor();

                // Update ingredient amount and unit
                $updateAmountQuery = "UPDATE ingredients_amounts SET value = :value, unit = :unit WHERE ingredient_id = :ingredient_id AND recipe_id = :recipe_id";
                $statementUpdateAmount = $db->prepare($updateAmountQuery);
                $statementUpdateAmount->bindValue(':value', $newIngredientAmount);
                $statementUpdateAmount->bindValue(':unit', $newIngredientUnit);
                $statementUpdateAmount->bindValue(':ingredient_id', $ingredientId);
                $statementUpdateAmount->bindValue(':recipe_id', $recipeId);
                $statementUpdateAmount->execute();
                $statementUpdateAmount->closeCursor();
            }
        }

// ...

        // Update tags
        // ... (Update tags code, similar to instructions and ingredients)
        // Update tags
    if (isset($_POST['tag_ids']) && isset($_POST['tag_names']) && isset($_POST['tag_types'])) {
        $tagIds = $_POST['tag_ids'];
        $newTagNames = $_POST['tag_names'];
        $newTagTypes = $_POST['tag_types'];

        foreach ($tagIds as $index => $tagId) {
            $newTagName = $newTagNames[$index];
            $newTagType = $newTagTypes[$index];

            updateTag($tagId, $newTagName, $newTagType); // Call function to update tag
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
<head>
    <!-- Add your head content here -->
</head>
<body>
    <!-- Navigation bar -->
    <!-- Add your navigation bar code here -->

    <div class="container mt-4">
        <h2>Edit Recipe</h2>
        <form method="POST" action="editrecipe.php?recipe_id=<?php echo $recipeId; ?>">
            <!-- Recipe Title -->
            <div class="mb-3">
                <label for="recipe_title" class="form-label">Recipe Title:</label>
                <input type="text" class="form-control" id="recipe_title" name="recipe_title" value="<?php echo htmlspecialchars($currentRecipe['title']); ?>">
            </div>
            <!-- Recipe Description -->
            <div class="mb-3">
                <label for="recipe_description" class="form-label">Recipe Description:</label>
                <textarea class="form-control" id="recipe_description" name="recipe_description"><?php echo htmlspecialchars($currentRecipe['description']); ?></textarea>
            </div>

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

            <!-- Edit ingredients -->
            <h3>Edit Ingredients</h3>
            <?php
            $ingredients = getIngredientsForRecipe($recipeId);
            $ingredientAmounts = getIngredientAmountsForRecipe($recipeId); 
            foreach ($ingredients as $ingredient) {
                $currentAmount = getIngredientAmountsForRecipe($ingredient['ingredient_id'], $ingredientAmounts);
                ?>
                <div class="mb-3">
                    <label for="ingredient_name" class="form-label">Ingredient Name:</label>
                    <input type="text" class="form-control" name="ingredient_names[]" value="<?php echo htmlspecialchars($ingredient['ingredient_name']); ?>">
                    <!-- Adding input field for ingredient amounts -->
                    <label for="amount">Amount:</label>
                    <input type="number" class="form-control" name="ingredient_amounts[]" value="<?php echo htmlspecialchars($currentAmount['value']); ?>">

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
                    <input type="hidden" name="ingredient_ids[]" value="<?php echo $ingredient['ingredient_id']; ?>">
                </div>
                <?php
            }
            ?>

            <!-- Edit tags -->
            <!-- ... (Edit tags code, similar to instructions and ingredients) -->
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

    <!-- Footer -->
    <!-- Add your footer code here -->

    <!-- Add your script includes here -->
</body>
</html>
