<?php
  include("../includes/db.php");
    session_start();
$sender = $_SESSION["user_id"];
$receiver = mysqli_real_escape_string($conn, $_POST['userId']);

$sql = "SELECT * FROM messages WHERE (SenderID = $sender && ReceiverID =$receiver)
OR (SenderID = $receiver && ReceiverID =$sender) ORDER BY MessageID ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messageClass = ($row['SenderID'] == $sender) ? 'sender' : 'receiver';
    
        echo '<div class="box-chat ' . $messageClass . '">
                <h1>
                    ' . $row['Content'] . '
                </h1>
              </div>';
    }
}

$conn->close();