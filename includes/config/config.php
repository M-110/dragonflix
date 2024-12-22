<?php

$currentPage = basename($_SERVER['PHP_SELF']);
$baseURL = ($currentPage === 'index.php') ? '' : '../';

session_start();

// Load .env configuration
loadEnv(__DIR__ . '/../../.env');


// SQLite connection
$dbPath = $_ENV['DB_PATH'] ?? __DIR__ . '/../../dragonflix_db';
$conn = null;

try {
    $conn = new PDO("sqlite:$dbPath");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    exit('Database connection failed');
}

function loadEnv(string $filePath): void
{
    if (!file_exists($filePath)) {
        error_log(".env file not found at: $filePath");
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
        $_SERVER[trim($key)] = trim($value);
    }

}

function handleExceptions($exception)
{
    global $baseURL;
    http_response_code(500);
    error_log($exception->getMessage());
    $currentPage = 'Error';
    $pageTitle = 'Error';
    $error = "An unexpected error has occurred.";
    include "{$baseURL}includes/views/error-view.php";
}

set_exception_handler('handleExceptions');
