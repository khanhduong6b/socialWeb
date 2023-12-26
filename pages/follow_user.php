<?php
session_start();
include("../includes/db.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $userId = $_POST['userId'];
    // Get the current user's ID from the session
    $currentUser = $_SESSION['user_id'];
    // Check if the current user is not already following the target user
    $checkFollow = "SELECT * FROM follows WHERE FollowerID = $currentUser AND FollowingID = $userId";
    $result = $conn->query($checkFollow);

    if ($result && $result->num_rows > 0) {
        echo "User is already being followed.";
    } else {
        // Ensure that FollowerID and FollowingID are treated as integers
        $insertFollow = "INSERT INTO follows (FollowerID, FollowingID) VALUES ($userId, $currentUser)";
        if ($conn->query($insertFollow) === TRUE) {
            echo "User followed successfully.";
        } else {
            echo "Error following user: " . $conn->error;
        }
    }
} else {
    // Handle other request methods if needed
    echo "Invalid request method.";
}

$conn->close();
?>
