<?php
$apiKey = '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';
$config = include 'config.php';
include 'sendgrid-php/sendgrid-php.php';
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
 * Mail
 */
$attachments = [];
if (file_exists('attachments') && is_dir('attachments')) {
    $dir = opendir('attachments');
    $i = 0;
    while (($file = readdir($dir)) !== false) {
        if (substr($file, 0, 1) == '.') {
            continue;
        }
        $i++;
        $fileName = 'attachments' . DIRECTORY_SEPARATOR . $file;
        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($fInfo, $fileName, FILEINFO_MIME_TYPE);
        $attachment = [
            'filename' => $file,
            'type' => $mime,
            'content' => base64_encode(file_get_contents($fileName)),
        ];
        $attachments[] = $attachment;
    }
}
if ($attachments) {
    $sendBody['attachments'] = $attachments;
}
//$request_body = json_encode($sendBody);
$sg = new \SendGrid($apiKey);
try {
    $response = $sg->client->mail()->send()->post($sendBody);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
//    echo $request_body . "\n";
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
