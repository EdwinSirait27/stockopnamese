@extends('layouts.error')

@section('title', '404')

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
        
        <div class="error-code">404</div>
        
        <div class="error-title">Pages Not Found</div>
        
        <div class="error-description">
            Halaman yang anda cari tidak ditemukan.
        </div>
        
        <div class="countdown-container">
            <div class="countdown-text">Redirect otomatis dalam:</div>
            <div class="countdown-timer" id="countdown">00:05</div>
        </div>
        
        <div class="redirect-info">
            Anda akan diarahkan kembali ke halaman sebelumnya
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Libraries -->
<script>
let timeLeft = 5;
const countdownElement = document.getElementById('countdown');

function updateCountdown() {
    const minutes = Math.floor(timeLeft / 5);
    const seconds = timeLeft % 5;
    
    const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    countdownElement.textContent = formattedTime;
    
    if (timeLeft > 0) {
        timeLeft--;
    } else {
       window.history.back(); 
    }
}

// Update countdown setiap detik
const countdownInterval = setInterval(updateCountdown, 1000);

// Redirect setelah 60 detik
setTimeout(function () {
    clearInterval(countdownInterval);
  window.history.back(); 
}, 5000);

// Initialize countdown
updateCountdown();
</script>
@endpush