<?php
require_once 'protection.php';
require_once 'db.php';

// --- HTTP SICHERHEITS-HEADER ---
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// --- CORS-LOGIK (MUSS GANZ OBEN STEHEN) ---
$allowed_origins = ['https://bikeparkberlin.de'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: https://bikeparkberlin.de");
}

header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Wir erlauben beide Header-Namen, um maximale Kompatibilität zu sichern
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, X-API-KEY, X-Auth-Token");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization, X-API-KEY");
// --- PREFLIGHT-CHECK ---
// Browser schicken OPTIONS ohne Key. Wenn wir hier nicht beenden, gibt es einen 403-Fehler.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// -------------------------------------------------------------
// EINSTELLUNGEN
// -------------------------------------------------------------

// true = Testmodus (Prüfung AUS)
// false = Livemodus (Prüfung AN)
$isTesting = false; 

// -------------------------------------------------------------
// API-KEY PRÜFUNG
// -------------------------------------------------------------

// Ab hier erwarten wir JSON-Antworten
header("Content-Type: application/json; charset=UTF-8");

// --- BOT-CHECK ---
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$bot_keywords = [
    'curl', 'python', 'guzzle', 'go-http', 'java', 'wget', 'postman', 
    'axios', 'node-fetch', 'php', 'http-client', 'libwww', 
    'selenium', 'headless', 'puppeteer', 'playwright', 
    'nmap', 'sqlmap', 'nikto', 'dirbuster'
];

foreach ($bot_keywords as $bot) {
    if (stripos($user_agent, $bot) !== false) {
        http_response_code(403);
        exit(json_encode(["error" => "Bots are not allowed."]));
    }
}

// --- RATE LIMITING ---
try {
    // WICHTIG: Verbindung über die Funktion aus db.php holen
    $pdo = getDBConnection(); 
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $now = time();
    $limit = 6;      
    $timespan = 60; // Erhöht auf 60 Sek für ein realistisches Minuten-Limit

    // Tabellen-Reinigung (Auto-Cleanup)
    $countStmt = $pdo->query("SELECT COUNT(*) FROM api_access_log");
    if ($countStmt->fetchColumn() > 5000) {
        $pdo->exec("DELETE FROM api_access_log");
    }

    // Status der IP prüfen
    $stmt = $pdo->prepare("SELECT last_access, request_count FROM api_access_log WHERE ip_address = ?");
    $stmt->execute([$ip]);
    $access = $stmt->fetch();

    if ($access) {
        if ($now - $access['last_access'] < $timespan) {
            if ($access['request_count'] >= $limit) {
                http_response_code(429);
                exit(json_encode(["error" => "Too many requests. Slow down!"]));
            }
            $pdo->prepare("UPDATE api_access_log SET request_count = request_count + 1 WHERE ip_address = ?")
                ->execute([$ip]);
        } else {
            $pdo->prepare("UPDATE api_access_log SET last_access = ?, request_count = 1 WHERE ip_address = ?")
                ->execute([$now, $ip]);
        }
    } else {
        $pdo->prepare("INSERT INTO api_access_log (ip_address, last_access, request_count) VALUES (?, ?, 1)")
            ->execute([$ip, $now]);
    }
} catch (PDOException $e) {
    error_log("Rate Limit Error: " . $e->getMessage());
    // Wir lassen das Skript bei DB-Fehler im Log weiterlaufen, um die API nicht zu blockieren
}

if ($isTesting) {
    return;
}

// --- API-KEY VERIFIZIERUNG ---
// Wir nutzen die Funktion aus der protection.php
$clientKey = trim(getRequestApiKey());
$expectedKey = trim($_ENV['X_API_KEY'] ?? '');

if (empty($clientKey) || $clientKey !== $expectedKey) {
    http_response_code(403);
    exit(json_encode([
        "status" => "error",
        "message" => "Zugriff verweigert.",
        "debug_hint" => "Check Headers or .env",
        "received_len" => strlen($clientKey)
    ]));
}