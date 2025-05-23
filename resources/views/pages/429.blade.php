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
            <div class="page-search">
                <form>
                    <div class="form-group floating-addon floating-addon-not-append">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            <input type="text"
                                class="form-control"
                                placeholder="Search">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush --}}
@extends('layouts.error')

@section('title', '429')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .page-error {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .page-error::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="%23ffffff" opacity="0.03"/><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.04"/><circle cx="25" cy="75" r="1" fill="%23ffffff" opacity="0.02"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.06"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .page-inner {
            text-align: center;
            color: white;
            z-index: 2;
            position: relative;
            max-width: 600px;
            padding: 2rem;
        }

        .error-number {
            font-size: 8rem;
            font-weight: 900;
            margin: 0;
            background: linear-gradient(45deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease-in-out infinite, bounce 2s ease-in-out infinite;
            text-shadow: 0 0 50px rgba(255, 255, 255, 0.3);
            position: relative;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
            color: #feca57;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        .page-description {
            font-size: 1.5rem;
            margin: 2rem 0;
            font-weight: 300;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .page-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
            opacity: 0.8;
            animation: fadeInUp 1s ease-out 0.7s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-container {
            margin: 3rem 0;
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .search-box {
            position: relative;
            max-width: 400px;
            margin: 0 auto;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input:focus {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
            z-index: 3;
        }

        .action-buttons {
            margin-top: 3rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 1.1s both;
        }

        .btn-modern {
            padding: 0.8rem 2rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatRandom 15s linear infinite;
        }

        .floating-element:nth-child(1) {
            width: 20px;
            height: 20px;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 30px;
            height: 30px;
            left: 20%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 15px;
            height: 15px;
            left: 70%;
            animation-delay: 4s;
        }

        .floating-element:nth-child(4) {
            width: 25px;
            height: 25px;
            left: 80%;
            animation-delay: 6s;
        }

        .floating-element:nth-child(5) {
            width: 35px;
            height: 35px;
            left: 90%;
            animation-delay: 8s;
        }

        @keyframes floatRandom {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .error-number {
                font-size: 5rem;
            }
            
            .page-description {
                font-size: 1.2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-modern {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="page-error">
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        
        <div class="page-inner">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h1 class="error-number">429</h1>
            
            <div class="page-description">
                Terlalu Banyak Percobaan Login
            </div>
            
            <div class="page-subtitle">
                Akun Anda telah diblokir sementara karena terlalu banyak percobaan login yang gagal. 
                Silakan tunggu beberapa menit sebelum mencoba lagi.
            </div>
            
            <div class="search-container">
                <form>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               class="search-input" 
                               placeholder="Cari halaman atau konten...">
                    </div>
                </form>
            </div>
            
            <div class="action-buttons">
                <a href="{{ url('/') }}" class="btn-modern">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('login') }}" class="btn-modern">
                    <i class="fas fa-sign-in-alt"></i> Coba Login Lagi
                </a>
                <a href="#" class="btn-modern" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Halaman Sebelumnya
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script>
        // Auto focus pada search input setelah 2 detik
        setTimeout(function() {
            document.querySelector('.search-input').focus();
        }, 2000);

        // Tambahkan efek ketik pada placeholder
        const searchInput = document.querySelector('.search-input');
        const originalPlaceholder = searchInput.placeholder;
        
        function typewriterEffect() {
            let i = 0;
            searchInput.placeholder = '';
            
            function typing() {
                if (i < originalPlaceholder.length) {
                    searchInput.placeholder += originalPlaceholder.charAt(i);
                    i++;
                    setTimeout(typing, 100);
                }
            }
            typing();
        }
        
        // Jalankan efek ketik setelah 3 detik
        setTimeout(typewriterEffect, 3000);

        // Tambahkan efek ripple pada tombol
        document.querySelectorAll('.btn-modern').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        // CSS untuk animasi ripple
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    <!-- Page Specific JS File -->
@endpush