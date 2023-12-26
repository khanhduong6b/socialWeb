<?php
  include("./includes/db.php");
  $sql = "SELECT Posts.PostID, Users.Username, Posts.Content
          FROM Posts
          INNER JOIN Users ON Posts.UserID = Users.UserID
          ORDER BY Posts.PostedAt DESC";

  $result = $conn->query($sql);          
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '<div class="w3-row w3-padding-64">
        <div class="w3-twothird w3-container">
        <h1 class="w3-text-teal">'.$row["Username"].'</h1>
                      <p>'.$row["Content"].'</p>
                    </div>
                  </div>';
      }
    } else {
      echo "No posts found.";
      }
      $conn->close();
?>