<?php
session_start();
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    // Retrieve user input
    $newFullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $newBio = mysqli_real_escape_string($conn, $_POST['bio']);

    // Check if the new username is not already in use by another user
    $checkUsernameSql = "SELECT * FROM users WHERE Username = '$newUsername' AND UserID != $user_id";
    $result = $conn->query($checkUsernameSql);

    if ($result && $result->num_rows > 0) {
        echo "Error: The username '$newUsername' is already in use. Please choose a different username.";
    } else {
        if (isset($_FILES['avatarFile']) && $_FILES['avatarFile']['error'] == 0) {
            $targetDir = "../avatars/"; // Directory where you want to save the avatars
            $targetFolder = "./avatars/".basename($_FILES['avatarFile']['name']);
            $targetFile = $targetDir . basename($_FILES['avatarFile']['name']);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
            // Check if the file is an actual image
            $check = getimagesize($_FILES['avatarFile']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
    
            // Check if file already exists
            
            // Check file size (max 2MB)
            if ($_FILES['avatarFile']['size'] > 2 * 1024 * 1024) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
    
            // Allow certain file formats
            $allowedFormats = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedFormats)) {
                echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                $uploadOk = 0;
            }
    
            // If everything is ok, try to upload file
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES['avatarFile']['tmp_name'], $targetFile)) {
                    $avatarURL = $targetFolder;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            // If no new avatar file is uploaded, keep the existing avatar URL
            $avatarURL = $row['AvatarURL'];
        }
        $updateSql = "UPDATE users 
        SET FullName = '$newFullName', 
            Username = '$newUsername', 
            Email = '$newEmail', 
            Bio = '$newBio', 
            AvatarURL = '$avatarURL'
        WHERE UserID = $user_id";

if ($conn->query($updateSql) === TRUE) {
    header("Location: ../index.php?view=profile");
} else {
echo "Error updating profile: " . $conn->error;
}
}
} else {
// Redirect to the profile page if accessed directly without a POST request
header("Location: ../index.php?view=profile");
exit();
}

$conn->close();
?>