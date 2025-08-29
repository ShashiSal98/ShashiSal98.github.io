<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Recipient email
$receiving_email_address = 'shashisalwathura@gmail.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shashisalwathura@gmail.com'; // your Gmail
        $mail->Password   = 'frqx oczo wwru uqqe';        // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipient
        $mail->setFrom('shashisalwathura@gmail.com', 'ShashiSalwathura - Contact Form');
        $mail->addAddress('shashisalwathura8@gmail.com'); 
        $mail->addReplyTo($email, $name); // so you can reply to the sender

        // Email body
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h3 style='color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;'>New message from ShashiSalwathura - Contact Form</h3>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding: 8px; width: 80px; font-weight: bold;'>Name:</td>
                    <td style='padding: 8px;'>$name</td>
                </tr>
                <tr>
                    <td style='padding: 8px; font-weight: bold;'>Email:</td>
                    <td style='padding: 8px;'>$email</td>
                </tr>
                <tr>
                    <td style='padding: 8px; font-weight: bold; vertical-align: top;'>Message:</td>
                    <td style='padding: 8px;'>" . nl2br(htmlspecialchars($message)) . "</td>
                </tr>
            </table>
        </div>";

        $mail->AltBody = "Name: $name\nEmail: $email\nMessage:\n$message";

        // Send email
        if($mail->send()) {
            echo "OK"; // must be exactly "OK" for validate.js
        } else {
            echo "Error sending email. Please try again later.";
        }

    } catch (Exception $e) {
        echo "Error: " . $mail->ErrorInfo; // detailed error for debugging
    }

} else {
    echo "Invalid request.";
}
?>
