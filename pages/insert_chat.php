<?php
  include("../includes/db.php");
    session_start();
$sender = $_SESSION["user_id"];
$receiver = mysqli_real_escape_string($conn, $_POST['receiverId']);
$message = mysqli_real_escape_string($conn, $_POST['message']);
$sql = "INSERT INTO messages (SenderID, ReceiverID, Content)
VALUES ({$sender}, {$receiver}, '{$message}')";
$result = $conn->query($sql);
if ($result=== TRUE ) {
    echo "Message sent successfully";
} else echo "Error";

$conn->close();