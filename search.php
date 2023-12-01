<?php
    require("connect-db.php");
    require("recipe-db.php");

    session_start(); // Start the session

    if (!isset($_SESSION['user_id'])) {
        // User is not logged in, redirect to login page
        header("Location: index.html");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchBtn'])) {
        $searchTerm = $_POST['searchTerm'];
    
        // Your SQL query to search for recipes with a matching title
        $query = "SELECT * FROM recipe WHERE title LIKE :searchTerm";
        $statement = $db->prepare($query);
        $statement->bindValue(':searchTerm', '%' . $searchTerm . '%');
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
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
  <meta name="description" content="Search Page">

  <title>Search Page</title>

  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.25.0/font/bootstrap-icons.css" rel="stylesheet">

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

        <!-- Search form -->
        <div class="text-center">
            <form name="searchForm" action="" method="post">
                <div class="row mb-3 mx-3">
                    <h2>Search Recipe Title:</h2>
                    <input type="text" class="form-control" name="searchTerm" required />
                </div>
                <div class="row mb-3 mx-3">
                    <input type="submit" value="Search" name="searchBtn" class="btn btn-primary" />
                </div>
            </form>
        </div>
        <!-- End search form -->

        <!-- Display search results -->
        <?php
            if (isset($results) && !empty($results)) {
                echo "<h3>Search Results:</h3>";
                foreach ($results as $result) {
                    ?>
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $result['title']; ?></h5>
                            <p class="card-text"><?php echo $result['description']; ?></p>
                            <a href="searched_recipe.php?recipe_id=<?php echo $result['recipe_id']; ?>" class="btn btn-primary">View Recipe</a>
                        </div>
                    </div>
                    <?php
                }
            }
        ?>
        <!-- End display search results -->

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
  // create recipe redirect
  const createRecipeBtn = document.getElementById("createRecipeBtn");
  createRecipeBtn.addEventListener("click", createRecipeRedirect);

  function createRecipeRedirect() {
    window.location.href = "createRecipe.php";
  }
  // end redirect
  
</script>

</html>
