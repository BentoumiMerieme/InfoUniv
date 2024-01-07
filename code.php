<?php
session_start();

// Replace these with your actual credentials
$validname = 'demo';
$validPassword = 'password';

if ((1===1) || ($_SERVER['REQUEST_METHOD'] === 'POST' )) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($name === $validname && $password === $validPassword) {
        $_SESSION['name'] = $name;
        header('Location: welcome.php');
        exit();
    } else {
        $errorMessage = 'Invalid name or password';
    }
}

/*
$name = $_POST['name'];
$visitor_email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$email_form = 'info@yourwebsite.com';

$email_subject = 'New Form Submission';

$email_body = "User Name: $name.\n".
              "User Email: $visitor_email.\n".
              "Subject: $subject.\n".
              "User Message: $message.\n";
$to = 'll@gmail.com';

$headers = "Form: $email_form \r\n";

$headers .= "Reply-To: $visitor_email \r\n";

mail($to, $email_subject, $email_body, $headers);

header("Location: contact.html");*/




?>