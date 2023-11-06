<?php

// Check for empty fields
if (empty($_POST['name']) ||
    empty($_POST['Email']) ||
    empty($_POST['Phone']) ||
    empty($_POST['message']) ||
    !filter_var($_POST['Email'], FILTER_VALIDATE_Email)) {
    echo "No arguments Provided!";
    return false;
}

$name = strip_tags(htmlspecialchars($_POST['name']));
$Email_address = strip_tags(htmlspecialchars($_POST['Email']));
$Phone = strip_tags(htmlspecialchars($_POST['Phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));

// Create the Email and send the message
$to = 'yourname@yourdomain.com'; // Add your Email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$Email_subject = "Website Contact Form:  $name";
$Email_body = "You have received a new message from your website contact form.\n\n" . "Here are the details:\n\nName: $name\n\nEmail: $Email_address\n\nPhone: $Phone\n\nMessage:\n$message";
$headers = "From: noreply@yourdomain.com\n"; // This is the Email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $Email_address";
mail($to, $Email_subject, $Email_body, $headers);
return true;
?>
