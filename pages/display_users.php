<?php
include("./includes/db.php");
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Fetch all users from the database
    $sql = "SELECT * FROM users WHERE username != '$username'";
    $result = $conn->query($sql);

    // Fetch the list of users that the logged-in user is already following
    $loggedInUserId = $_SESSION["user_id"];
    $followerQuery = "SELECT FollowerID FROM follows WHERE FollowingID = $loggedInUserId";
    $followerResult = $conn->query($followerQuery);
    $followerUsers = [];

    if ($followerResult && $followerResult->num_rows > 0) {
        while ($followerRow = $followerResult->fetch_assoc()) {
            $followerUsers[] = $followerRow["FollowerID"];
        }
    }
} else {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $followerUsers = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        .user-list-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            margin-top: 46px;
            margin-right: auto;
            overflow: auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .user-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .user-avatar img {
            max-width: 50px;
            max-height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            margin-left: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="user-list-container">
        <h2>User List</h2>
        <div style="text-align: center; margin-bottom: 20px;">
            <label for="searchUser">Search Users:</label>
            <input type="text" id="searchUser" oninput="searchUsers()" placeholder="Enter username">
        </div>

        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userId = $row["UserID"];
                $isFollowing = in_array($userId, $followerUsers);
                ?>
                <div class="user-card">
                    <div class="user-avatar">
                        <img src="<?php echo $row["AvatarURL"]; ?>" alt="User Avatar">
                    </div>
                    <div class="user-info">
                        <div><strong>Username:</strong><label class="username">
                                <?php echo $row["Username"]; ?>
                            </label></div>
                        <div><strong>Email:</strong>
                            <?php echo $row["Email"]; ?>
                        </div>
                        <div><strong>Full Name:</strong>
                            <?php echo $row["FullName"]; ?>
                        </div>
                        <?php
                        if ($isFollowing) {
                            // If already following, show a different button or disable the button
                            ?>
                            <button id="follow-btn-<?php echo $userId; ?>" class="follow-btn" disabled>Following</button>
                            <?php
                        } else {
                            // If not following, show the regular "Follow" button
                            ?>
                            <button id="follow-btn-<?php echo $userId; ?>" class="follow-btn"
                                onclick="followUser(<?php echo $userId; ?>)">Follow</button>
                            <?php
                        }

                        ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No users found.";
        }
        ?>

    </div>

</body>

</html>
<script>
    function followUser(userId) {
        // Create an XMLHttpRequest object
        console.log(userId);
        var xhr = new XMLHttpRequest();

        // Define the AJAX request
        xhr.open("POST", "./pages/follow_user.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Set up a callback function to handle the response
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response from the server
                console.log(xhr.responseText);
                // You can update the UI or perform other actions based on the response
                if (xhr.responseText === "User followed successfully.") {
                    // Example: Change button text and disable the button
                    document.getElementById('follow-btn-' + userId).innerText = "Following";
                    document.getElementById('follow-btn-' + userId).disabled = true;
                } else {
                    // Handle other response scenarios if needed
                }
            }
        };

        // Send the request with the user ID
        xhr.send("userId=" + encodeURIComponent(userId));
    }
    function searchUsers() {
        var input, filter, userCards, user, username;
        input = document.getElementById("searchUser");
        filter = input.value.toUpperCase();
        userCards = document.querySelectorAll(".user-card");
        for (var i = 0; i < userCards.length; i++) {
            user = userCards[i];
            username = user.querySelector(".username").textContent.toUpperCase();
            console.log(username.toString().textContent);
            if (username.indexOf(filter) > -1) {
                user.style.display = "";
            } else {
                user.style.display = "none";
            }
        }
    }

</script>


<?php
$conn->close();
?>