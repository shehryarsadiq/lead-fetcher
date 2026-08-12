<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response([
        'success' => false,
        'message' => 'Method not allowed.'
    ], 405);
}

$placeId = trim($_GET['place_id'] ?? '');

if ($placeId === '') {
    json_response([
        'success' => false,
        'message' => 'Place ID is required.'
    ], 422);
}

$apiKey = get_google_api_key();

if ($apiKey === '') {
    json_response([
        'success' => false,
        'message' => 'Google Places API key is not configured.'
    ], 500);
}

$url =
    'https://maps.googleapis.com/maps/api/place/details/json?' .
    http_build_query([
        'place_id' => $placeId,
        'fields' =>
            'name,formatted_address,formatted_phone_number,' .
            'international_phone_number,website,rating,' .
            'user_ratings_total,photos,url,opening_hours',
        'key' => $apiKey
    ]);

$google = curl_get_json($url);

if (!$google['success']) {
    json_response([
        'success' => false,
        'message' => 'Could not connect to Google Places API.',
        'error' => $google['error'] ?? ''
    ], 502);
}

$data = $google['data'];

if (($data['status'] ?? '') !== 'OK') {
    json_response([
        'success' => false,
        'message' =>
            $data['error_message'] ??
            $data['status'] ??
            'Unable to fetch place details.',
        'google_status' => $data['status'] ?? ''
    ], 502);
}

$result = $data['result'] ?? [];

$photoUrl = '';

if (!empty($result['photos'][0]['photo_reference'])) {
    $photoUrl =
        'https://maps.googleapis.com/maps/api/place/photo?' .
        http_build_query([
            'maxwidth' => 1200,
            'photo_reference' => $result['photos'][0]['photo_reference'],
            'key' => $apiKey
        ]);
}

json_response([
    'success' => true,
    'result' => [
        'name' => $result['name'] ?? '',
        'address' => $result['formatted_address'] ?? '',
        'phone' =>
            $result['international_phone_number'] ??
            $result['formatted_phone_number'] ??
            '',
        'website' => $result['website'] ?? '',
        'rating' => $result['rating'] ?? null,
        'review_count' => $result['user_ratings_total'] ?? 0,
        'google_maps_url' => $result['url'] ?? '',
        'photo_url' => $photoUrl,
        'opening_hours' => $result['opening_hours'] ?? null
    ]
]);