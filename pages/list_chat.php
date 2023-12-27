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
        $followerQuery = "SELECT FollowingID FROM follows WHERE FollowerID = $loggedInUserId";
        $followerResult = $conn->query($followerQuery);
        $followerUsers = [];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    if ($followerResult && $followerResult->num_rows > 0) {
        while ($followerRow = $followerResult->fetch_assoc()) {
            $followerUsers[] = $followerRow["FollowingID"];
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

            margin-right: auto;
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

        .chat-btn {
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <div class="w3-main" style="display:flex;width:100%;height:100%;margin-top:46px;align-items:flex-end;">
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
                    if ($isFollowing) {
                        ?>
                        <div class="user-card">
                            <div class="user-avatar">
                                <img src="<?php echo $row["AvatarURL"]; ?>" alt="User Avatar">
                            </div>
                            <div class="user-info">
                                <div><strong>Username:</strong><span id="username-<?php echo $userId; ?>">
                                        <?php echo $row["Username"]; ?>
                                    </span>
                                </div>
                                <div><strong>Email:</strong>
                                    <?php echo $row["Email"]; ?>
                                </div>
                                <div><strong>Full Name:</strong>
                                    <?php echo $row["FullName"]; ?>
                                </div>
                                <button class="chat-btn" onclick="ChatUser(<?php echo $userId; ?>)">Chat</button>
                            </div>
                        </div>
                    <?php }
                }
            } else {
                echo "No users found.";
            }

            ?>
        </div>

        <div class="box-chat">
            <div id="box-chat--header">
                <p id="receiverId" hidden></p>
                <p id="receiver"></p>
            </div>
            <div id="box-chat--body ">
                <p id="message">

                </p>
            </div>

            <div id="box-chat--input" style="display:  flex;">
                <input type="text" id="input-message" placeholder="Enter Message">
                <button class="chat-btn" style="width: 80px" onclick="sendMessage()"> Send</button>
            </div>
        </div>
    </div>
</body>


</html>
<script>
    function ChatUser(userId) {
        document.getElementById('receiverId').innerText = userId;
        document.getElementById('receiver').innerText = document.getElementById('username-' + userId).innerText;
        // Create an XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Define the AJAX request
        xhr.open("POST", "./pages/chat.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response from the server
                console.log(xhr.responseText);
                // You can update the UI or perform other actions based on the response
                if (xhr.responseText === "No message available") {
                    // Example: Change button text and disable the button

                } else {
                    document.getElementById('message').innerHTML = xhr.responseText;
                }
            }
        };

        // Send the request with the user ID
        xhr.send("userId=" + encodeURIComponent(userId));
    }

    function sendMessage() {
        // Retrieve SenderID and ReceiverID from the page
        var senderId = <?php echo $loggedInUserId; ?>;
        var receiverId = document.getElementById('receiverId').innerText;
        var messageInput = document.getElementById('input-message').value;

        // Create an XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Define the AJAX request
        xhr.open("POST", "./pages/insert_chat.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response from the server
                console.log(xhr.responseText);
                // You can update the UI or perform other actions based on the response
                if (xhr.responseText === "Message sent successfully") {
                    // Example: Clear the input field after sending
                    document.getElementById('input-message').value = '';
                }
            }
        };

        // Send the request with SenderID, ReceiverID, and the message input
        xhr.send("receiverId=" + encodeURIComponent(receiverId) +
            "&message=" + encodeURIComponent(messageInput));
    }

    setInterval(() => {
        var xhr = new XMLHttpRequest();

        // Define the AJAX request
        xhr.open("POST", "./pages/chat.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response from the server
                console.log(xhr.responseText);
                // You can update the UI or perform other actions based on the response
                if (xhr.responseText === "No message available") {
                    // Example: Change button text and disable the button

                } else {
                    document.getElementById('message').innerHTML = xhr.responseText;
                }
            }
        };
        if (document.getElementById('receiverId').innerText != "")
            // Send the request with the user ID
            xhr.send("userId=" + encodeURIComponent(document.getElementById('receiverId').innerText));
        else console.log("Chua chon nguoi chat")
    }, 500);
</script>


<?php
$conn->close();
?>