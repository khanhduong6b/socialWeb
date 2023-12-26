<?php
include("./includes/db.php");
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Fetch all users from the database

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    $searchCondition = !empty($searchTerm) ? "AND username LIKE '%$searchTerm%'" : '';

    $sql = "SELECT * FROM users WHERE username != '$username' $searchCondition";
    $result = $conn->query($sql);

    // Fetch the list of users that the logged-in user is already following
    $loggedInUserId = $_SESSION["user_id"];
    try {
        $followerQuery = "SELECT FollowerID FROM follows WHERE FollowingID = $loggedInUserId";
        $followerResult = $conn->query($followerQuery);
        $followerUsers = [];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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
            width: 45%;
            box-sizing: border-box;
            padding: 0 20px;
        }

        .box-chat {
            width: 50%;
            min-height: 600px;
            height: 100%;
            box-sizing: border-box;
            padding: 20px;
            margin-left: 3rem;
            position: -webkit-sticky;
            position: sticky;
            bottom: 15px;
            display: flex;
            flex-flow: column;
            border-radius: 16px;
            border: 1px solid black;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
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
            display: flex;
        }

        .user-avatar img {
            max-width: 50px;
            max-height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            margin-left: 10px;
            display: flex;
            flex-direction: column;
        }

        .follow-btn {
            margin-top: 10px;
        }

        #box-chat--header {
            height: 60px;
            width: 100%;
            background-color: #fff;
            padding: 8px;
            border: 1px solid gray;
            border-radius: 16px;
        }

        #box-chat--body {
            margin-top: 10px;
            height: 100%;
            width: 100%;
            padding: 30px;
            min-height: 480px;
            background-color: #fff;
            border: 1px solid gray;
            border-radius: 16px;
        }

        .chat-text {
            border: 1px solid gray;

        }
    </style>

</head>

<body>
    <div style="display:flex;width:100%;height:100%;margin-top:46px;align-items:flex-end;">
        <div class="user-list-container">
            <h2>User List</h2>
            <form method="GET" action="find_users.php">
                <label for="search">Search Users:</label>
                <input type="text" id="search" name="search" placeholder="Enter username">
                <button type="submit">Search</button>
            </form>
            <br>

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
                            <div><strong>Username:</strong>
                                <?php echo $row["Username"]; ?>
                            </div>
                            <div><strong>Email:</strong>
                                <?php echo $row["Email"]; ?>
                            </div>
                            <div><strong>Full Name:</strong>
                                <?php echo $row["FullName"]; ?>
                            </div>
                            <button id="follow-btn-<?php echo $userId; ?>" class="follow-btn"
                                onclick="followUser(<?php echo $userId; ?>)">Chat</button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No users found.";
            }
            ?>
        </div>

        <div class="box-chat">
            <div id="box-chat--header">
                USERNAME
            </div>
            <div id="box-chat--body">
                <p class="chat-text">
                    CONTENT
                </p>
            </div>

        </div>
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
</script>


<?php
$conn->close();
?>