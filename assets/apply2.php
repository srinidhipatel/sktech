<?php
if (isset($_FILES) && (bool) $_FILES) {

    $allowedExtensions = array("pdf", "doc", "docx", "gif", "jpeg", "jpg", "png");

    $files = array();
    foreach ($_FILES as $name => $file) {
        $file_name = $file['name'];
        $temp_name = $file['tmp_name'];
        $file_type = $file['type'];
        $path_parts = pathinfo($file_name);
        $ext = $path_parts['extension'];
        if (!in_array($ext, $allowedExtensions)) {
            die("File $file_name has the extensions $ext which is not allowed");
        }
        array_push($files, $file);
    }

    // email fields: to, from, subject, and so on
    $to = "srinidhi.test@gmail.com";
//    $from = $_POST['email'];
//    $from = "krishna@rathorji.in";
//    $subject = $_POST['sub'];
//    $message = $_POST['msg'];
   
     $from = $_POST['email']; // this is the sender's Email address
    $name = $_POST['name'];
    $resume = $_POST['resume'];
//    $number = $_POST['number'];
    $headers = "from: $from";
    
    $subject = "Resume";
    
    $message = $name . " Have sent the follwing details :" ."\n\n" . "Name :" . $_POST['name'] ."\n\n". "Email :" . $_POST['email'] ."\n\n" ."\n\n". "Resume :" . $_POST['resume'];//. "Phone Number :". $_POST['number'] 
    

    // boundary 
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    // multipart boundary 
    $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
    $message .= "--{$mime_boundary}\n";

    // preparing attachments
    for ($x = 0; $x < count($files); $x++) {
        $file = fopen($files[$x]['tmp_name'], "rb");
        $data = fread($file, filesize($files[$x]['tmp_name']));
        fclose($file);
        $data = chunk_split(base64_encode($data));
        $name = $files[$x]['name'];
        $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$name\"\n" .
                "Content-Disposition: attachment;\n" . " filename=\"$name\"\n" .
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
        $message .= "--{$mime_boundary}\n";
    }
    // send

    $ok = mail($to, $subject, $message, $headers);
    if ($ok) {
        header("Location: index.html");
//        echo '<script type ="text/JavaScript">';  
//        echo 'alert("JavaScript Alert Box by PHP")';  
//        echo '</script>';  
    } else {
        echo "<p>mail could not be sent!</p>";
    }
}
?>
