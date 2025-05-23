{{-- @extends('layouts.error')

@section('title', '429')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="page-error">
        <div class="page-inner">
            <h1>429</h1>
            <div class="page-description">
                Too Many Attempts login.
            </div>
            <div class="mt-3">
                <a>Please wait 1 minute. You will be redirected to the login page...</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- JS Libraries -->
    <script>
        setTimeout(function () {
            window.location.href = "{{ route('login') }}";
        }, 60000); // 60 detik = 1 menit
    </script>
@endpush --}}
{{-- @extends('layouts.error')

@section('title', '429')

@push('style')
<!-- CSS Libraries -->
<style>
    .modern-error-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
        position: relative;
    }

    .modern-error-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="60" r="1" fill="white" opacity="0.1"/><circle cx="70" cy="30" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    }

    .error-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 90%;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .error-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .error-icon::after {
        content: '⏱️';
        font-size: 48px;
        color: white;
    }

    .error-code {
        font-size: 72px;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .error-title {
        font-size: 28px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .error-description {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .countdown-container {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .countdown-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }
        100% {
            left: 100%;
        }
    }

    .countdown-text {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .countdown-timer {
        font-size: 36px;
        font-weight: 800;
        font-family: 'Courier New', monospace;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 15px;
    }

    .progress-fill {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 3px;
        width: 0%;
        animation: progressAnimation 60s linear;
    }

    @keyframes progressAnimation {
        from {
            width: 0%;
        }
        to {
            width: 100%;
        }
    }

    .redirect-info {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7f8c8d;
        font-size: 14px;
        gap: 8px;
    }

    .redirect-info::before {
        content: '🔄';
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .floating-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }

    .floating-shape {
        position: absolute;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }

    .floating-shape:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .floating-shape:nth-child(2) {
        top: 60%;
        right: 10%;
        animation-delay: 2s;
    }

    .floating-shape:nth-child(3) {
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    @media (max-width: 768px) {
        .error-container {
            padding: 40px 20px;
            margin: 20px;
        }
        
        .error-code {
            font-size: 56px;
        }
        
        .error-title {
            font-size: 24px;
        }
        
        .countdown-timer {
            font-size: 28px;
        }
    }
</style>
@endpush

@section('main')
<div class="modern-error-page">
    <div class="floating-shapes">
        <div class="floating-shape">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="30" fill="white"/>
            </svg>
        </div>
        <div class="floating-shape">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="8" fill="white"/>
            </svg>
        </div>
        <div class="floating-shape">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="25,5 45,35 5,35" fill="white"/>
            </svg>
        </div>
    </div>
    
    <div class="error-container">
        <div class="error-icon"></div>
        
        <div class="error-code">429</div>
        
        <div class="error-title">Too Many Attempts</div>
        
        <div class="error-description">
            Anda telah melakukan terlalu banyak percobaan login. Silakan tunggu sebentar sebelum mencoba lagi.
        </div>
        
        <div class="countdown-container">
            <div class="countdown-text">Redirect otomatis dalam:</div>
            <div class="countdown-timer" id="countdown">01:00</div>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>
        
        <div class="redirect-info">
            Anda akan diarahkan ke halaman login secara otomatis
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Libraries -->
<script>
let timeLeft = 60;
const countdownElement = document.getElementById('countdown');

function updateCountdown() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    
    const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    countdownElement.textContent = formattedTime;
    
    if (timeLeft > 0) {
        timeLeft--;
    } else {
        window.location.href = "{{ route('login') }}";
    }
}

// Update countdown setiap detik
const countdownInterval = setInterval(updateCountdown, 1000);

// Redirect setelah 60 detik
setTimeout(function () {
    clearInterval(countdownInterval);
    window.location.href = "{{ route('login') }}";
}, 60000);

// Initialize countdown
updateCountdown();
</script>
@endpush --}}
@extends('layouts.error')

@section('title', '429')

@push('style')
<!-- CSS Libraries -->
<style>
    .modern-error-page {
        min-height: 100vh;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .error-container {
        background: white;
        border-radius: 12px;
        padding: 48px 32px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        max-width: 400px;
        width: 90%;
        border: 1px solid #e2e8f0;
    }

    .error-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        background: #fef3f2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 32px;
    }

    .error-code {
        font-size: 48px;
        font-weight: 700;
        color: #dc2626;
        margin-bottom: 16px;
    }

    .error-title {
        font-size: 24px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .error-description {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 32px;
        line-height: 1.5;
    }

    .countdown-container {
        background: #f3f4f6;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .countdown-text {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .countdown-timer {
        font-size: 24px;
        font-weight: 600;
        color: #dc2626;
        font-family: 'Courier New', monospace;
    }

    .redirect-info {
        color: #6b7280;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .error-container {
            padding: 32px 24px;
            margin: 20px;
        }
        
        .error-code {
            font-size: 40px;
        }
        
        .error-title {
            font-size: 20px;
        }
    }
</style>
@endpush

@section('main')
<div class="modern-error-page">
    <div class="error-container">
        <div class="error-icon">
            ⏱️
        </div>
        
        <div class="error-code">429</div>
        
        <div class="error-title">Too Many Attempts</div>
        
        <div class="error-description">
            Anda telah melakukan terlalu banyak percobaan login. Silakan tunggu sebentar sebelum mencoba lagi.
        </div>
        
        <div class="countdown-container">
            <div class="countdown-text">Redirect otomatis dalam:</div>
            <div class="countdown-timer" id="countdown">01:00</div>
        </div>
        
        <div class="redirect-info">
            Anda akan diarahkan ke halaman login secara otomatis
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Libraries -->
<script>
let timeLeft = 60;
const countdownElement = document.getElementById('countdown');

function updateCountdown() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    
    const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    countdownElement.textContent = formattedTime;
    
    if (timeLeft > 0) {
        timeLeft--;
    } else {
        window.location.href = "{{ route('login') }}";
    }
}

// Update countdown setiap detik
const countdownInterval = setInterval(updateCountdown, 1000);

// Redirect setelah 60 detik
setTimeout(function () {
    clearInterval(countdownInterval);
    window.location.href = "{{ route('login') }}";
}, 60000);

// Initialize countdown
updateCountdown();
</script>
@endpush