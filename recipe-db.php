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

?>