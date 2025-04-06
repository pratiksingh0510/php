<?php

// Enable CORS
$allowed_origins = [
    "https://www.marvellousmilestone.com",
    "https://marvellousmilestone.com",
	"http://localhost:5173/"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Replace with your Web App URL
$WEB_APP_URL = "https://script.google.com/macros/s/AKfycbxXUIVZ8me3ruTsi_g_EPQ9H7-dtMH71ghqjqmdmYHvGZCEsuVn7ejLEXcAGox9uK-3/exec";

try {
    // Read JSON input
    $inputJSON = file_get_contents("php://input");
    $data = json_decode($inputJSON, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON input"]);
        exit;
    }

    // Log received data
    error_log("Appending Name: " . json_encode($data));

    // Send data to Google Sheets Web App
    $options = [
        "http" => [
            "header"  => "Content-Type: application/json",
            "method"  => "POST",
            "content" => json_encode($data)
        ]
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents($WEB_APP_URL, false, $context);

    if ($response === FALSE) {
        error_log("Error occurred while appending data!");
        http_response_code(500);
        echo json_encode(["error" => "An error occurred while sending data"]);
        exit;
    }

    error_log("Data appended successfully!");
    echo json_encode(["Success" => "Data appended successfully!"]);

} catch (Exception $e) {
    error_log("Error occurred: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "An error occurred", "details" => $e->getMessage()]);
}
?>
