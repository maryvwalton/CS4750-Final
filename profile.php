<?php
  require("connect-db.php");
  require("recipe-db.php");

  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
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
  <meta name="description" content="Profile Page">

  <title>Profile Page</title>

  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->
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
  

  <!-- main page content -->
  <div class="container-fluid">

    <!-- Welcome user display -->
    <?php
      echo "Welcome, " . $_SESSION['username'] . "!";
    ?>
    <!-- end display -->

    <!-- create recipe button -->
    <div class="text-center">
      <button class="btn btn-primary" id="createRecipeBtn">
        Create Recipe
      </button>
    </div>
    <!-- end button -->

    <!-- User Recipe List -->
    <h2>My Recipes</h2>
    <div class="row">
      <?php
        global $db;
        $userId = $_SESSION['user_id'];
        $query = "SELECT r.recipe_id, r.title, r.description FROM created_by cb
                  JOIN recipe r ON cb.recipe_id = r.recipe_id
                  WHERE cb.user_id = :user_id";

        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if (isset($results) && !empty($results)) {
          foreach ($results as $result) {
            ?>
            <div class="col-md-4 mb-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title"><?php echo $result['title']; ?></h5>
                  <p class="card-text"><?php echo $result['description']; ?></p>
                  <a href="recipe_details.php?recipe_id=<?php echo $result['recipe_id']; ?>" class="btn btn-primary">View Recipe</a>
                </div>
              </div>
            </div>
            <?php
          }
        }
      ?>
    </div>
    <!-- End Recipe List -->

  </div>
  <!-- end -->


  <!-- Copyright Footer KEEP -->
  <footer class="text-center text-lg-start" style="background-color: #AFCFFF">
    <div class="text-center p-3">
      © 2023 Copyright: Chef Your Way
    </div>
  </footer>
  <!-- end footer -->


</body>

<script>
  // create recipe redirect
  const createRecipeBtn = document.getElementById("createRecipeBtn");
  createRecipeBtn.addEventListener("click", createRecipeRedirect);

  function createRecipeRedirect() {
    window.location.href = "createRecipe.php";
  }
  // end redirect
</script>

</html>