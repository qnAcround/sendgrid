<?php
$apiKey = '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';
$config = include 'config.php';
$personalizations = [
    [
        'to' => [
            [
                "email" => $config['to_email'],
                "name" => $config['to_name'],
            ]
        ],
        'subject' => 'Hello, World!'
    ]
];
$from = [
    "email" => $config['from_email'],
    "name" => $config['from_name'],
];
$reply_to = [
    "email" => $config['reply_to_email'],
    "name" => $config['reply_to_name'],
];
$content = [
    [
        'type' => 'text/plain',
        'value' => 'Heya!'
    ]
];

$sendUrl = 'https://api.sendgrid.com/v3/mail/send';
$sendBody = [
    'personalizations' => $personalizations,
    'content' => $content,
    'from' => $from,
    'reply_to' => $reply_to,
];

/**
 * Curl
 */
$ch = curl_init($sendUrl);
curl_setopt($ch, CURLOPT_URL, $sendUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendBody));

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json",
));

$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
print_r([
    $statusCode,
    $response,
]);
