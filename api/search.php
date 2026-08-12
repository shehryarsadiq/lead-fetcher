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

$keyword = trim($_GET['keyword'] ?? '');
$location = trim($_GET['location'] ?? '');
$pageToken = trim($_GET['page_token'] ?? '');

if ($keyword === '' || $location === '') {
    json_response([
        'success' => false,
        'message' => 'Keyword and location are required.'
    ], 422);
}

$apiKey = get_google_api_key();

if ($apiKey === '') {
    json_response([
        'success' => false,
        'message' => 'Google Places API key is not configured.'
    ], 500);
}

$query = $keyword . ' in ' . $location;

$url = GOOGLE_PLACES_API_BASE . '/places:searchText';

$payload = [
    'textQuery' => $query,
    'languageCode' => 'en',
    'pageSize' => 20
];

if ($pageToken !== '') {
    $payload['pageToken'] = $pageToken;
}

$fieldMask = implode(',', [
    'places.id',
    'places.name',
    'places.displayName',
    'places.types',
    'places.formattedAddress',
    'places.nationalPhoneNumber',
    'places.internationalPhoneNumber',
    'places.websiteUri',
    'places.googleMapsUri',
    'places.rating',
    'places.userRatingCount',
    'places.photos',
    'places.currentOpeningHours',
    'nextPageToken'
]);

$google = curl_post_json(
    $url,
    $payload,
    [
        'X-Goog-Api-Key: ' . $apiKey,
        'X-Goog-FieldMask: ' . $fieldMask
    ]
);

if (!$google['success']) {
    json_response([
        'success' => false,
        'message' => 'Could not connect to Google Places API.',
        'error' => $google['error'] ?? 'Unknown cURL error.',
        'http_code' => $google['http_code'] ?? 0
    ], 502);
}

$data = $google['data'];
$httpCode = $google['http_code'] ?? 0;

if ($httpCode < 200 || $httpCode >= 300) {
    json_response([
        'success' => false,
        'message' => 'Google Places API returned an error.',
        'error' => $data['error']['message'] ?? 'Unknown Google API error.',
        'google_status' => $data['error']['status'] ?? '',
        'http_code' => $httpCode
    ], 502);
}

$results = [];

foreach (($data['places'] ?? []) as $place) {
    $placeId = $place['id'] ?? '';

    $name = $place['displayName']['text']
        ?? $place['name']
        ?? '';

    $category = 'Business';

    if (!empty($place['types'][0])) {
        $category = ucwords(
            str_replace('_', ' ', $place['types'][0])
        );
    }

    $photoUrl = '';

    if (!empty($place['photos'][0]['name'])) {
        $photoUrl =
            'https://places.googleapis.com/v1/' .
            $place['photos'][0]['name'] .
            '/media?' .
            http_build_query([
                'maxWidthPx' => 900,
                'key' => $apiKey
            ]);
    }

    $openingHours = null;

    if (isset($place['currentOpeningHours']['openNow'])) {
        $openingHours =
            (bool)$place['currentOpeningHours']['openNow'];
    }

    $results[] = [
        'place_id' => $placeId,
        'name' => $name,
        'category' => $category,
        'address' => $place['formattedAddress'] ?? '',
        'phone' =>
            $place['internationalPhoneNumber']
            ?? $place['nationalPhoneNumber']
            ?? '',
        'website' => $place['websiteUri'] ?? '',
        'photo_url' => $photoUrl,
        'rating' => $place['rating'] ?? null,
        'review_count' => $place['userRatingCount'] ?? 0,
        'google_maps_url' => $place['googleMapsUri'] ?? '',
        'opening_hours' => $openingHours
    ];
}

json_response([
    'success' => true,
    'results' => $results,
    'count' => count($results),
    'next_page_token' => $data['nextPageToken'] ?? '',
    'query' => $query,
    'http_code' => $httpCode
]);