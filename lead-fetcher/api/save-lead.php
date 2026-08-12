<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response([
        'success' => false,
        'message' => 'Method not allowed.'
    ], 405);
}

$input = json_decode(
    file_get_contents('php://input'),
    true
);

if (!is_array($input)) {
    json_response([
        'success' => false,
        'message' => 'Invalid JSON request.'
    ], 422);
}

$lead = $input['lead'] ?? null;

if (!is_array($lead) || empty(trim($lead['name'] ?? ''))) {
    json_response([
        'success' => false,
        'message' => 'Invalid lead data.'
    ], 422);
}

$placeId = trim($lead['place_id'] ?? '');
$name = trim($lead['name'] ?? '');
$category = trim($lead['category'] ?? '');
$address = trim($lead['address'] ?? '');
$phone = trim($lead['phone'] ?? '');
$website = trim($lead['website'] ?? '');
$photo = trim($lead['photo_url'] ?? '');
$maps = trim($lead['google_maps_url'] ?? '');

$rating = is_numeric($lead['rating'] ?? null)
    ? (float)$lead['rating']
    : null;

$reviews = (int)($lead['review_count'] ?? 0);

if ($placeId !== '') {
    $stmt = mysqli_prepare(
        $conn,
        'SELECT id FROM leads WHERE place_id = ? LIMIT 1'
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $placeId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            mysqli_stmt_close($stmt);

            json_response([
                'success' => true,
                'message' => 'Lead already saved.',
                'already_saved' => true
            ]);
        }

        mysqli_stmt_close($stmt);
    }
}

$sql = "
    INSERT INTO leads
    (
        place_id,
        name,
        category,
        address,
        phone,
        website,
        photo_url,
        rating,
        review_count,
        google_maps_url,
        status,
        created_at,
        updated_at
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', NOW(), NOW())
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    json_response([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($conn)
    ], 500);
}

mysqli_stmt_bind_param(
    $stmt,
    'sssssssdis',
    $placeId,
    $name,
    $category,
    $address,
    $phone,
    $website,
    $photo,
    $rating,
    $reviews,
    $maps
);

if (!mysqli_stmt_execute($stmt)) {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    json_response([
        'success' => false,
        'message' => 'Could not save lead: ' . $error
    ], 500);
}

$id = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);

json_response([
    'success' => true,
    'message' => 'Lead saved successfully.',
    'id' => $id
]);