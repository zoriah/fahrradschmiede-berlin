<?php
# echo("Hi, ich bin protection.php!");

$test=false;

// 1. .ENV LOADER (Pfad ohne Schrägstrich am Ende)
$directory = dirname(__DIR__);
#echo "$directory";
if (!function_exists('loadEnv')) {
    function loadEnv($directory) {
        $path = $directory . '\.env';
#echo "$path";
        if (!file_exists($path)) {		
            return false;
	    echo ".env existiert nicht!";
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            // Kommentare und leere Zeilen überspringen
            if (empty($line) || strpos($line, '#') === 0) continue;

            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $key = trim($name, " \t\n\r\0\x0B\"'");
                // Entfernt Leerzeichen, Anführungszeichen und unsichtbare Umbrüche
                $val = trim($value, " \t\n\r\0\x0B\"'");

                $_ENV[$key] = $val;
                $_SERVER[$key] = $val;
                putenv("{$key}={$val}");
            }
        }
        return true;
    }
}

// Loader ausführen
loadEnv($directory);

if (!function_exists('getRequestApiKey')) {
    function getRequestApiKey() {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        
        // Kleinbuchstaben für verlässlichen Vergleich
        $cleanHeaders = [];
        foreach ($headers as $key => $value) {
            $cleanHeaders[strtolower($key)] = $value;
        }

        return $cleanHeaders['x-api-key'] 
            ?? $_SERVER['HTTP_X_API_KEY'] 
            ?? $_GET['api_key'] 
            ?? '';
    }
}
if($test){
 // Test-Ausgabe zur Kontrolle
 echo "HOST: " . ($_ENV['DB_HOST'] ?? 'Nicht gefunden') . "<br>";
 echo "DBNAME: " . ($_ENV['DB_NAME'] ?? 'Nicht gefunden') . "<br>";
 echo "USER: " . ($_ENV['DB_USER'] ?? 'Nicht gefunden') . "<br>";
 echo "PASSWORT: " . (isset($_ENV['DB_PASS']) ? '***** (geladen)' : 'Nicht gefunden') . "<br>";
 //echo "PUBLIC_X_API_KEY: " . (isset($_ENV['PUBLIC_X_API_KEY']) ? '***** (geladen)' : 'Nicht gefunden') . "<br>";
echo "HOST: " . ($_ENV['MAIL_HOST'] ?? 'Nicht gefunden') . "<br>";
echo "USER: " . ($_ENV['MAIL_USER'] ?? 'Nicht gefunden') . "<br>";
echo "PORT: " . ($_ENV['MAIL_PORT'] ?? 'Nicht gefunden') . "<br>";
echo "ENCRYPTION: " . ($_ENV['MAIL_ENCRYPTION'] ?? 'Nicht gefunden') . "<br>";
echo "PASSWORT: " . (isset($_ENV['MAIL_PASS']) && !empty($_ENV['MAIL_PASS']) ? '***** (geladen)' : 'Nicht gefunden') . "<br>";
}