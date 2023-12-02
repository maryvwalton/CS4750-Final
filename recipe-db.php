<?php
function isUsernameTaken($username)
{
    global $db;

    $query = "SELECT COUNT(*) FROM user WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();

    $count = $statement->fetchColumn();
    $statement->closeCursor();

    return $count > 0;
}

function createUser($username, $password, $email) 
{
    global $db;

    // Check if the username already exists
    if (isUsernameTaken($username)) {
        return "Username is already taken.";
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO user (username, password, email) VALUES (:username, :password, :email)";

    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $hashedPassword);
    $statement->bindValue(':email', $email);
    $statement->execute();

    // Return the user ID of the created user
    $userId = $db->lastInsertId();
    
    $statement->closeCursor();

    // Log in the user immediately after creating the account
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;

    // Redirect to the user page
    header("Location: profile.php");
    exit();
}


function userLogin()
{
    global $db; // Assuming $db is your database connection

    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve hashed password and user ID from the database based on the username
    $query = "SELECT user_id, password FROM user WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();

    // Fetch the hashed password and user ID from the result
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Verify the password using password_verify function
    if ($result && password_verify($password, $result['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $username;
        
        // Redirect to the user page
        header("Location: profile.php");
        exit();
    } else {
        // Invalid credentials, handle accordingly (e.g., display an error message)
        echo "Invalid username or password";
    }

    $statement->closeCursor();
}

function createRecipe($title, $description) 
{
  global $db;

  $query = "INSERT INTO recipe (title, description) VALUES (:title, :description)";

  $statement = $db->prepare($query);
  $statement->bindValue(':title', $title);
  $statement->bindValue(':description', $description);
  $statement->execute();
  
  // Retrieve the last inserted recipe ID
  $recipeId = $db->lastInsertId();

  $statement->closeCursor();

  return $recipeId;
}

function insertIngredient($recipeId, $ingredientName)
{
    global $db;

    $query = "INSERT INTO recipe_ingredients (recipe_id, ingredient_name) VALUES (:recipeId, :ingredientName)";

    $statement = $db->prepare($query);
    $statement->bindValue(':recipeId', $recipeId);
    $statement->bindValue(':ingredientName', $ingredientName);
    $statement->execute();

    // Retrieve the last inserted ingredient ID
    $ingredientId = $db->lastInsertId();

    $statement->closeCursor();

    return $ingredientId;
}


function insertInstruction($recipeId, $instruction)
{
    global $db;
    $query = "INSERT INTO recipe_directions (recipe_id, instruction) VALUES (:recipeID, :instruction)";

    $statement = $db->prepare($query);
    $statement->bindValue(':recipeID', $recipeId);
    $statement->bindValue(':instruction', $instruction);
    $statement->execute();

    $statement->closeCursor();
}

function createdBy ($recipeId, $userId) {
    global $db;
    $query = "INSERT INTO created_by (recipe_id, user_id) VALUES (:recipeID, :userID)";

    $statement = $db->prepare($query);
    $statement->bindValue(':recipeID', $recipeId);
    $statement->bindValue(':userID', $userId);
    $statement->execute();

    $statement->closeCursor();
}

function insertTag($recipeId, $tagName, $tagType) {
    global $db;

    $query = "INSERT INTO `tags` (`recipe_id`, `tag_name`, `type`) VALUES (:recipe_id, :tag_name, :type)";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->bindValue(':tag_name', $tagName);
    $statement->bindValue(':type', $tagType);
    $statement->execute();

    $statement->closeCursor();
}

function updateRecipe($recipeId, $recipeTitle, $recipeDescription) {
    global $db;

    $query = "UPDATE recipe SET title = :title, description = :description WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->bindValue(':title', $recipeTitle);
    $statement->bindValue(':description', $recipeDescription);
    $statement->execute();

    $statement->closeCursor();
}

function getRecipeById($recipeId)
{
    global $db;

    $query = "SELECT * FROM recipe WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();

    $recipe = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $recipe;
}

function updateIngredient($ingredientId, $ingredientName)
{
    global $db;

    $query = "UPDATE recipe_ingredients SET ingredient_name = :ingredientName WHERE ingredient_id = :ingredientId";

    $statement = $db->prepare($query);
    $statement->bindValue(':ingredientId', $ingredientId);
    $statement->bindValue(':ingredientName', $ingredientName);
    $statement->execute();

    $statement->closeCursor();
}

function updateInstruction($instructionId, $newInstruction)
{
    global $db;
    $query = "UPDATE recipe_directions SET instruction = :newInstruction WHERE instruction_id = :instructionId";

    $statement = $db->prepare($query);
    $statement->bindValue(':newInstruction', $newInstruction);
    $statement->bindValue(':instructionId', $instructionId);
    $statement->execute();

    $statement->closeCursor();
}

function updateIngredientAmounts($ingredientAmountId, $newAmount)
{
    global $db;

    $query = "UPDATE ingredients_amounts SET value = :newAmount WHERE amount_id = :ingredientAmountId";

    $statement = $db->prepare($query);
    $statement->bindValue(':ingredientAmountId', $ingredientAmountId);
    $statement->bindValue(':newAmount', $newAmount);
    $statement->execute();

    $statement->closeCursor();
}

function updateTag($tagId, $newTagName, $newTagType)
{
    global $db;

    $query = "UPDATE `tags` SET `tag_name` = :newTagName, `type` = :newTagType WHERE `tag_id` = :tagId";
    $statement = $db->prepare($query);
    $statement->bindValue(':tagId', $tagId);
    $statement->bindValue(':newTagName', $newTagName);
    $statement->bindValue(':newTagType', $newTagType);
    $statement->execute();

    $statement->closeCursor();
}

// In recipe-db.php

function getInstructionsForRecipe($recipeId)
{
    global $db;

    $query = "SELECT * FROM recipe_directions WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();

    $instructions = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $instructions;
}

function getIngredientsForRecipe($recipeId)
{
    global $db;

    $query = "SELECT * FROM recipe_ingredients WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();

    $ingredients = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $ingredients;
}

function getIngredientAmountsForRecipe($recipeId)
{
    global $db;

    $query = "SELECT ia.ingredient_id, ia.value, ia.unit
              FROM ingredients_amounts ia
              JOIN recipe_ingredients ri ON ia.ingredient_id = ri.ingredient_id
              WHERE ri.recipe_id = :recipe_id";

    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();

    $ingredientAmounts = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $ingredientAmounts;
}

function getTagsForRecipe($recipeId)
{
    global $db;

    $query = "SELECT * FROM tags WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipeId);
    $statement->execute();

    $tags = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    return $tags;
}

?>