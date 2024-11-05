<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form inputs
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    
    // Directory where resumes will be saved
    $target_dir = "uploads/";
    
    // File details
    $file = $_FILES["resume"];
    $file_name = basename($file["name"]);
    $file_size = $file["size"];
    $file_tmp = $file["tmp_name"];
    $file_type = $file["type"];
    
    // Only allow certain file formats
    $allowed_extensions = ['pdf', 'doc', 'docx'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Check file size (limit to 5MB)
    if ($file_size > 5000000) {
        echo "Sorry, your file is too large.";
        exit();
    }

    // Check file extension
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
        exit();
    }

    // Generate unique name to avoid overwriting existing files
    $new_file_name = uniqid() . "." . $file_extension;
    
    // Full path where the file will be saved
    $target_file = $target_dir . $new_file_name;

    // Create the upload directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($file_tmp, $target_file)) {
        echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";

        // Optional: Send an email notification (you need to configure mail settings on the server)
        $to = "srinidhi.test@gmail.com";
        $subject = "New Resume Submission from " . $name;
        $message = "You have received a new resume submission.\n\nName: $name\nEmail: $email";
        $headers = "From: srinidhi.test@gmail.com";

        mail($to, $subject, $message, $headers);

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
