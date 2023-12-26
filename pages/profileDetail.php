<?php
include("./includes/db.php");
$username = $_SESSION['username'];

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE Username = '$username'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $_SESSION["user_id"]=$row["UserID"];
    $userId = $row["UserID"];
    $followersQuery = "SELECT u.Username FROM users u
                      JOIN follows f ON u.UserID = f.FollowingID
                      WHERE f.FollowerID = $userId";

    $followersResult = $conn->query($followersQuery);
    $followers = [];

    while ($follower = $followersResult->fetch_assoc()) {
        $followers[] = $follower["Username"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        .profile-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .profile-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .profile-value {
            margin-bottom: 15px;
        }

        #avatar img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 50%;
        }

        .editable-input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        .update-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #avatar img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 50%;
    object-fit: cover; /* Ensures the image covers the entire container */
}

    </style>
</head>
<body>
<div style="display: inline-flex;">
    <div class="profile-container" style="Width: 1000px;">
        <h2>User Profile</h2>

        <form action="pages/update_profile.php" method="post" enctype="multipart/form-data">
            <div class="profile-value" style="text-align: center;" id="avatar">
                <img src="<?php echo $row["AvatarURL"]; ?>" alt="User Avatar">
                <br>
                <label for="avatarFile">Change Avatar:</label>
                <input type="file" id="avatarFile" name="avatarFile">
            </div>

            <div class="profile-label">User ID:</div>
            <div class="profile-value" id="userId"><?php echo $row["UserID"]; ?></div>

            <div class="profile-label">Full Name:</div>
            <div class="profile-value" id="fullName">
                <input type="text" class="editable-input" name="fullName" value="<?php echo $row["FullName"]; ?>">
            </div>

            <div class="profile-label">Username:</div>
            <div class="profile-value" id="username">
                <input type="text" class="editable-input" name="username" value="<?php echo $row["Username"]; ?>">
            </div>

            <div class="profile-label">Email:</div>
            <div class="profile-value" id="email">
                <input type="email" class="editable-input" name="email" value="<?php echo $row["Email"]; ?>">
            </div>

            <div class="profile-label">Bio:</div>
            <div class="profile-value" id="bio">
                <textarea class="editable-input" name="bio"><?php echo $row["Bio"]; ?></textarea>
            </div>

            <div class="profile-label">Join Date:</div>
            <div class="profile-value" id="joinDate"><?php echo $row["JoinDate"]; ?></div>

            <button type="submit" class="update-btn">Update Profile</button>
        </form>

        
    </div>

    <div class="profile-container" style="margin-left:50px; width:500px">
        <div class="profile-label">Followers:</div>
        <div class="profile-value" id="followers">
            <?php
            if (empty($followers)) {
                echo "No followers yet.";
            } else {
                echo implode(', ', $followers);
            }
            ?>
        </div>
    </div>
    </div>


</body>
</html>

<?php
} 
$conn->close();
?>
