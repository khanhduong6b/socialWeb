<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Threads</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./assets/css/styles.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .users {
      padding: 25px 30px;
    }

    .users header,
    .users-list a {
      display: flex;
      align-items: center;
      padding-bottom: 20px;
      justify-content: space-between;
      border-bottom: 1px solid #e6e6e6;
    }

    .wrapper {
      padding-top: 100px;
      width: 500px;
    }

    .wrapper img {
      object-fit: cover;
      border-radius: 50%;
    }

    .users header .content,
    .users-list a .content {
      display: flex;
    }

    .users header .content img {
      height: 50px;
      width: 50px;
      border-radius: 50%;
      object-fit: cover;
    }

    .users header .details {
      margin-left: 15px;
    }

    .users header .details span {
      font-size: 18px;
      font-weight: 500;
    }

    .users .search {
      margin: 20px 0;
      display: flex;
      position: relative;
      align-items: center;
      justify-content: space-between;
    }

    .users .search .text {
      font-size: 18px;
    }

    .users .search input {
      position: absolute;
      height: 42px;
      width: calc(100%-50px);
      border: 1px solid #ccc;
      padding: 0 13px;
      font-size: 16px;
      border-radius: 5px 0 0 5px;
      outline: none;
    }

    .users .search button {
      width: 47px;
      height: 42px;
      border: none;
      outline: none;
      color: #fff;
      background: #333;
      font-size: 17px;
      border-radius: 0 5px 5px 0;
    }

    .users-list {}

    .users-list a .content img {
      height: 40px;
      width: 40px;
    }

    .chat-btn {
      width: 200px;
      padding: 8px 15px;
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
      height: 100%;
    }

    .chat-btn:hover {
      background-color: #2980b9;
    }

    .box-chat {

      width: 50%;
      box-sizing: border-box;
      padding: 20px;
      margin-left: 3rem;
      position: -webkit-sticky;
      position: sticky;
      bottom: 15px;
      display: flex;
      flex-flow: column;
      border-radius: 16px;
      border: 1px solid #ddd;
      box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px;
      background-color: #fff;
      height: 100%;
    }

    #box-chat--header,
    #box-chat--body,
    #box-chat--input {
      margin-bottom: 15px;
    }

    #box-chat--header {
      min-height: 75px;
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    #box-chat--body {

      padding: 20px;
      min-height: 200px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    #box-chat--input {
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    #input-message {
      width: calc(100% - 16px);
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    /* Style for sender messages */
    .box-chat .sender {
      float: right;
      text-align: right;
      background-color: #3498db;
      color: #fff;
      padding: 10px;
      margin: 10px 0;
      border-radius: 12px;
      font-size: 14px;
    }

    .box-chat .sender,
    .box-chat .receiver {
      margin-top: 10px;
      padding: 8px;
    }

    /* Style for receiver messages */
    .box-chat .receiver {
      text-align: left;
      background-color: #f1f1f1;
      color: #333;
      padding: 10px;
      margin: 10px 0;
      border-radius: 12px;
      font-size: 14px;
    }

    html,
    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-family: "Roboto", sans-serif;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .w3-sidebar {
      z-index: 3;
      width: 250px;
      top: 46px;
      bottom: 0;
      height: inherit;
      height: 100%;
    }

    .w3-main {
      flex: 1;
    }

    .container {
      height: 85vh;

    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <div class="w3-top">
    <div class="w3-bar w3-theme w3-top w3-left-align w3-large">
      <a class="w3-bar-item w3-button w3-right w3-hide-large w3-hover-white w3-large w3-theme-l1"
        href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
      <a href="./" class="w3-bar-item w3-button w3-theme-l1">
        <img src="./assets/img/logo.svg" alt="">
      </a>
      <?php
      if (!isset($_SESSION["username"])) {
        ?>
        <a href="./pages/register.php"
          class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white w3-right">Register</a>
        <a href="./pages/login.php"
          class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white w3-right">Login</a>
        <?php
      } else {

        echo '<a href="./pages/logout.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white w3-right">Logout</a>';
        echo '<a href="./pages/login.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white w3-right">' . $_SESSION["username"] . '</a>';
      }
      ?>
    </div>
  </div>

  <!-- Sidebar -->
  <nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-animate-left" id="mySidebar">
    <a href="javascript:void(0)" onclick="w3_close()"
      class="w3-right w3-xlarge w3-padding-large w3-hover-black w3-hide-large" title="Close Menu">
      <i class="fa fa-remove"></i>
    </a>
    <h4 class="w3-bar-item"><b>Menu</b></h4>
    <a class="w3-bar-item w3-button w3-hover-black" href="./index.php?view=profile">Profile</a>
    <a class="w3-bar-item w3-button w3-hover-black" href="./index.php?view=createPost">Create Post</a>
    <a class="w3-bar-item w3-button w3-hover-black" href="./index.php?view=users">Find User</a>
    <a class="w3-bar-item w3-button w3-hover-black" href="./index.php?view=chat">Chat</a>
  </nav>

  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu"
    id="myOverlay"></div>

  <!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
  <div style="margin-left:250px;height:80vh;">

    <?php
    $view = isset($_GET['view']) ? $_GET['view'] : 'home';
    // Include the corresponding content based on the 'view' parameter
    switch ($view) {
      case 'profile':
        if (isset($_SESSION["username"])) {
          include('./pages/profileDetail.php');
        } else
          echo '<div class="w3-row w3-padding-64">
        <div class="w3-twothird w3-container">
        <h1 class="w3-text-teal">Must Login</h1>
              
                    </div>
                  </div>';
        break;
      case 'users':
        include('./pages/display_users.php');
        break;
      case 'createPost':
        if (isset($_SESSION["username"])) {
          include('./pages/create_post.php');
        } else
          echo '<div class="w3-row w3-padding-64">
        <div class="w3-twothird w3-container">
        <h1 class="w3-text-teal">Must Login</h1>
              
                    </div>
                  </div>';
        break;
      case 'chat':
        if (isset($_SESSION["username"])) {
          include('./pages/list_chat.php');
        } else
          echo '<div class="w3-row w3-padding-64">
        <div class="w3-twothird w3-container">
        <h1 class="w3-text-teal">Must Login</h1>
              
                    </div>
                  </div>';
        break;
      default:
        include('./pages/get_posts.php');
        break;
    }
    ?>

    <footer id="myFooter">
      <div class="w3-container w3-theme-l2 w3-padding-32">
        <h4>Footer</h4>
      </div>
    </footer>

    <!-- END MAIN -->
  </div>

  <script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
      if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
      } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
      }
    }

    // Close the sidebar with the close button
    function w3_close() {
      mySidebar.style.display = "none";
      overlayBg.style.display = "none";
    }
  </script>

</body>

</html>