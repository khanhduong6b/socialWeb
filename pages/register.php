<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password], input[type=email] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<h2>Login Form</h2>

<form action="register.php" method="post" enctype="multipart/form-data">>

  <div class="container">
  <label for="avatar"><b>Avatar:</b></label>
    <input type="file" name="avatar" accept="image/*" />

  </br>
  <label for="username"><b>Username:</b></label>
        <input type="text" name="username" required />

        <label for="email"><b>Email:</b></label>
        <input type="email" name="email" required />
        <label for="password"><b>Password:</b></label>
        <input type="password" name="password" required />

        <label for="confirm_password"><b>Confirm Password:</b></label>
        <input type="password" name="confirm_password" required />
        
        <button type="submit">Register</button>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <a href="../index.php">Cancel</a>
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include your database connection file
    include('../includes/db.php');

    // Handle registration logic
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirmPassword = $_POST['confirm_password'];
    $avatarURL;
    // Check if the avatar file is uploaded
    if ($_FILES['avatar']['error'] == 0) {
      // Handle avatar upload logic
      $avatarName = $_FILES['avatar']['name'];
      $avatarTmpName = $_FILES['avatar']['tmp_name'];
      $avatarPath = '../avatars/' . $avatarName;  // Specify your desired path for storing avatars
      $avatarURL = './avatars/' . $avatarName;
      // Move the uploaded file to the specified path
      move_uploaded_file($avatarTmpName, $avatarPath);
  } else {
      $avatarURL = './avatars/default.jpg'; // Set a default avatar or handle accordingly
  }


    // Perform additional validation (e.g., password match, unique username, unique email)

    if ($_POST['password'] !== $_POST['confirm_password']) {
        echo "Error: Passwords do not match.";
    } else {
        // Check if the username or email already exists in the database
        $checkExistingUser = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($checkExistingUser);

        if ($result->num_rows > 0) {
            echo "Error: Username or email already exists.";
        } else {
            // Insert user data into the database
            $insertUser = "INSERT INTO users (username, email, passwordhash, AvatarURL) VALUES ('$username', '$email', '$password', '$avatarURL')";

            if ($conn->query($insertUser) === TRUE) {
                session_start();
                $_SESSION['username'] = $username;
                header("Location: ../index.php");
            } else {
                echo "Error: " . $insertUser . "<br>" . $conn->error;
            }
        }
    }
    $sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result) {
    $_SESSION['user_id']=$row["UserID"];
}
    $conn->close();
}
?>
