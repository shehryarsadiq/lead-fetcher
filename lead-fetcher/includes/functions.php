<?php

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function json_response($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}

function get_google_api_key()
{
    if (defined('GOOGLE_PLACES_API_KEY')) {
        return trim(GOOGLE_PLACES_API_KEY);
    }

    return '';
}

function curl_post_json($url, $payload = [], $headers = [], $timeout = 30)
{
    $ch = curl_init();

    if ($ch === false) {
        return [
            'success' => false,
            'error' => 'Could not initialize cURL.',
            'http_code' => 0
        ];
    }

    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json',
        'User-Agent: LeadFetcher/1.0'
    ];

    $allHeaders = array_merge(
        $defaultHeaders,
        $headers
    );

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => 10,

        // Local WAMP SSL issue
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,

        CURLOPT_HTTPHEADER => $allHeaders
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($response === false) {
        return [
            'success' => false,
            'error' => $error ?: 'Unknown cURL error.',
            'http_code' => $httpCode
        ];
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        return [
            'success' => false,
            'error' => 'Invalid JSON response received.',
            'http_code' => $httpCode,
            'raw' => $response
        ];
    }

    return [
        'success' => true,
        'data' => $data,
        'http_code' => $httpCode
    ];
}

function curl_get_json($url, $timeout = 30)
{
    $ch = curl_init();

    if ($ch === false) {
        return [
            'success' => false,
            'error' => 'Could not initialize cURL.',
            'http_code' => 0
        ];
    }

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => 10,

        // Local WAMP SSL issue
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,

        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'User-Agent: LeadFetcher/1.0'
        ]
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($response === false) {
        return [
            'success' => false,
            'error' => $error ?: 'Unknown cURL error.',
            'http_code' => $httpCode
        ];
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        return [
            'success' => false,
            'error' => 'Invalid JSON response received.',
            'http_code' => $httpCode,
            'raw' => $response
        ];
    }

    return [
        'success' => true,
        'data' => $data,
        'http_code' => $httpCode
    ];
}