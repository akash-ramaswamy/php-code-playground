<?php
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST' || $path !== '/run') {
    http_response_code(404);
    header('Content-Type: text/plain');
    echo "Not Found";
    exit;
}

$code = file_get_contents('php://input');
if (empty($code)) {
    http_response_code(400);
    header('Content-Type: text/plain');
    echo "No PHP code provided";
    exit;
}

$tmp = tempnam(sys_get_temp_dir(), 'php_');
file_put_contents($tmp, $code);

// Capture and flush output
ob_start();
passthru("php $tmp", $status);
$output = ob_get_clean();

// remove temporary file after execution
unlink($tmp);

// Send proper headers
header('Content-Type: text/plain');
header('Content-Length: ' . strlen($output));

// Flush to client
echo $output;
flush();
exit;
