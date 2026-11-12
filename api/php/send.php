<?php
// 1. CORS-Konfiguration für Astro & Live
$allowed_origins = [
    "https://fahrradschmiede-berlin.de",
    "http://localhost",
    "http://localhost:4321"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Preflight-Request (OPTIONS) direkt abfangen
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // A) Honeypot (Bot-Schutz)
    if (!empty($_POST['botcheck'])) {
        exit(json_encode([
            "status" => "success", 
            "message" => "Vielen Dank für Ihre Nachricht!"
        ]));
    }

    // B) .ENV Loader (Lädt die Konfiguration aus dem übergeordneten Ordner)
    $directory = dirname(__DIR__);
    $path = $directory . '/.env';
    if (!file_exists($path)) {
        $path = '.env';
    }

    if (!file_exists($path)) {
        http_response_code(500);
        exit(json_encode([
            "status" => "error", 
            "message" => "Konfigurationsdatei (.env) wurde auf dem Server nicht gefunden."
        ]));
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name, " \t\n\r\0\x0B\"'")] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }

    $mailUser = $_ENV['MAIL_USER'] ?? '';
    $mailPass = $_ENV['MAIL_PASS'] ?? '';
    $mailPort = intval($_ENV['MAIL_PORT'] ?? 465);
    $mailHost = $_ENV['MAIL_HOST'] ?? 'smtp.strato.de';

    if (empty($mailUser) || empty($mailPass)) {
        http_response_code(500);
        exit(json_encode([
            "status" => "error", 
            "message" => "E-Mail-Zugangsdaten in der .env sind unvollständig."
        ]));
    }

    // C) Daten bereinigen
    $name         = strip_tags(trim($_POST['name'] ?? 'Unbekannt'));
    $email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject_user = str_replace(["\r", "\n"], '', strip_tags(trim($_POST['subject'] ?? 'Kein Betreff')));
    $message      = htmlspecialchars(trim($_POST['message'] ?? 'Keine Nachricht'), ENT_QUOTES, 'UTF-8');

    // D) Validierung der E-Mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        exit(json_encode([
            "status" => "error", 
            "message" => "Die angegebene E-Mail-Adresse ist ungültig. Bitte überprüfen."
        ]));
    }

    // E) PHPMailer einbinden (liegt im api-Ordner)
    $phpmailerPath = dirname(__DIR__) . '/phpmailer/src/';

    require $phpmailerPath . 'Exception.php';
    require $phpmailerPath . 'PHPMailer.php';
    require $phpmailerPath . 'SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server-Einstellungen für Strato SMTP
        $mail->isSMTP();
        $mail->Host       = $mailHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailUser;
        $mail->Password   = $mailPass;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $mailPort;
        $mail->CharSet    = 'UTF-8';

        // Absender und Empfänger (Strato-Regel: setFrom muss $mailUser sein)
        $mail->setFrom($mailUser, 'Fahrradschmiede Berlin');
        $mail->addAddress($mailUser, 'Fahrradschmiede Berlin');
        $mail->addReplyTo($email, $name);

        // Nachricht an den Administrator
        $mail->Subject = "Kontaktformular: " . $subject_user;
        $mail->Body    = "NEUE NACHRICHT VOM KONTAKTFORMULAR\n" .
                         "==================================\n" .
                         "VON:      $name\n" .
                         "E-MAIL:   $email\n" .
                         "BETREFF:  $subject_user\n" .
                         "==================================\n\n" .
                         "NACHRICHT:\n$message\n";

        $mail->send();

        // F) Automatische Bestätigungsmail an den Sender
        $mail->clearAddresses();
        $mail->addAddress($email, $name);
        $mail->Subject = "Vielen Dank für Ihre Nachricht an die Fahrradschmiede Berlin!";
        $mail->Body    = "Hallo $name,\n\n" .
                         "vielen Dank für Ihre Nachricht! Wir haben Ihre Anfrage erhalten und werden uns schnellstmöglich bei Ihnen melden.\n\n" .
                         "Hier ist eine Kopie Ihrer Nachricht:\n" .
                         "--------------------------------------------------\n" .
                         "$message\n" .
                         "--------------------------------------------------\n\n" .
                         "Viele Grüße,\n" .
                         "Ihr Fahrradschmiede Berlin Team\n" .
                         "https://fahrradschmiede-berlin.de";
        @$mail->send();

        // G) Erfolgreiche JSON-Antwort für die grüne Box im Frontend
        echo json_encode([
            "status" => "success", 
            "message" => "Vielen Dank, $name! Deine Nachricht wurde erfolgreich versendet. Wir melden uns in Kürze bei dir."
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "status" => "error", 
            "message" => "Die Nachricht konnte leider nicht gesendet werden. Bitte versuche es später noch einmal."
        ]);
    }

} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error", 
        "message" => "Ungültige Anfrage-Methode."
    ]);
}
?>