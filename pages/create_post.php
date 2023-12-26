<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  session_start();
    include("../includes/db.php");
    // Handle the form submission
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($conn, $_POST["content"]);

    // Insert the post into the database
    $insertPostQuery = "INSERT INTO posts (userID, content) VALUES ($user_id, '$content')";
    $result = $conn->query($insertPostQuery);

    if ($result) {
      header("Location: ../index.php");
    } else {
        echo "Error creating post: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>

        .container {
            text-align: center;
        }

        form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        label, textarea, button {
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Create a Post</h2>

        <form action="./pages/create_post.php" method="post">
            <label for="content">Post Content:</label>
            <br>
            <textarea name="content" rows="4" cols="50" required></textarea>
            <br>
            <button type="submit">Create Post</button>
        </form>
    </div>

</body>
</html>
