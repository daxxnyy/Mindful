<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'C:/Users/BOGDAN/OneDrive/Desktop/PHPMailer-master/src/Exception.php';
require 'C:/Users/BOGDAN/OneDrive/Desktop/PHPMailer-master/src/PHPMailer.php';
require 'C:/Users/BOGDAN/OneDrive/Desktop/PHPMailer-master/src/SMTP.php';

// Function to generate a verification code
function generateVerificationCode($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)))),1,$length);
}

// Connect to the database (example using PDO)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // reCAPTCHA validation
    $recaptchaSecret = '6LekOQQqAAAAACg7ttVDfvluqZ6U9Whm91oeTyxf';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if(intval($responseKeys["success"]) !== 1) {
        die("Please complete the CAPTCHA.");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get the user's IP address

    // Rate limiting: Allow max 3 registrations per IP per hour
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registration_attempts WHERE ip_address = ? AND timestamp > NOW() - INTERVAL 1 HOUR");
    $stmt->execute([$ipAddress]);
    $attemptCount = $stmt->fetchColumn();

    if ($attemptCount >= 3) {
        die("You have reached the maximum number of registration attempts. Please try again later.");
    }

    // Log the registration attempt
    $stmt = $pdo->prepare("INSERT INTO registration_attempts (ip_address) VALUES (?)");
    $stmt->execute([$ipAddress]);

    // Validate the data (additional validations should be performed in a real scenario)
    $profilePic = $_FILES['profilePic']['name'];
    $targetDir = "uploads/";
    $targetFilePath = $targetDir . $profilePic;
    move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedEmail = hash('sha256', $email); // Hash the email
    $verificationCode = generateVerificationCode();

    session_start();
    $_SESSION['username'] = $username;

    $sql = "INSERT INTO users (username, password, profile_pic, email, verification_code, verified, ip_address) VALUES (?, ?, ?, ?, ?, 0, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashedPassword, $targetFilePath, $hashedEmail, $verificationCode, $ipAddress]);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'iuoanbogdancioroiu@gmail.com';
        $mail->Password = 'gfzg nqyr qzcy hwhm';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('iuoanbogdancioroiu@gmail.com', 'Mindful Support Team');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Cod de Verificare pentru Înregistrare';

        $mail->Body = '
        <html>
        <body>
            <h2>Bun venit la Mindful, ' . $username . '!</h2>
            <p>Îți mulțumim că te-ai înregistrat la Mindful. Pentru a-ți activa contul, te rugăm să introduci următorul cod de verificare în pagina de verificare:</p>
            <p><strong style="font-size: 18px;">Codul tău de verificare este: <span style="color: #FF5733;">' . $verificationCode . '</span></strong></p>
            <p>Acest cod este valabil pentru o perioadă limitată de timp.</p>
            <p>Dacă nu ai solicitat acest cod, te rugăm să ignori acest email.</p>
            <p>Cu stimă,<br>Mindful Support Team</p>
        </body>
        </html>';

        $mail->send();
        echo 'Emailul de verificare a fost trimis cu succes!';
    } catch (Exception $e) {
        echo "Mesajul tău nu a putut fi trimis. Eroare: {$mail->ErrorInfo}";
    }

    header("Location: verify.html");
    exit();
}
?>
