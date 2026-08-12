<?php

date_default_timezone_set('Asia/Karachi');

$DB_HOST = "sql308.infinityfree.com";
$DB_USER = "if0_42633298";
$DB_PASS = "uR2MZBdd7ees9j9";
$DB_NAME = "if0_42633298_leadfetcher";

$conn = mysqli_connect(
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");