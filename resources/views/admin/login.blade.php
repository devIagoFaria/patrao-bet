<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('site_name', 'Vorex') }} - Login Administrativo</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? asset(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-login-body">
    <!-- Canvas de Partículas -->
    <canvas id="particle-canvas" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none;"></canvas>

    <div class="admin-login-container" style="position: relative; z-index: 10;">
        <div class="admin-login-card">
            <div class="admin-login-header">
                <div class="admin-logo" style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-bottom: 15px;">
                    @if(\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 60px; max-width: 100%; object-fit: contain; margin-bottom: 5px;">
                    @else
                        <span class="logo-text">{{ strtoupper(\App\Models\Setting::get('site_name', 'Vorex')) }}</span>
                    @endif
                    <span class="logo-sub">PAINEL ADMIN</span>
                </div>
                <p>Acesse sua conta administrativa com segurança</p>
            </div>

            @if ($errors->any())
                <div class="admin-login-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-triangle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="admin-login-form">
                @csrf
                <div class="admin-form-group">
                    <label for="email"><i class="fa-regular fa-envelope"></i> E-mail do Administrador</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="ex: admin@vorex.com">
                </div>

                <div class="admin-form-group">
                    <label for="password"><i class="fa-solid fa-lock"></i> Senha de Acesso</label>
                    <div class="admin-password-wrapper">
                        <input type="password" id="password" name="password" required placeholder="Digite sua senha">
                        <button type="button" class="admin-password-toggle" onclick="togglePassword()">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label for="2fa_code"><i class="fa-solid fa-key"></i> Código Autenticador (2FA)</label>
                    <input type="text" id="2fa_code" name="2fa_code" required placeholder="Digite o código 2FA">
                    <p class="admin-form-help">Insira o código 2FA configurado no arquivo .env (ou o token do aplicativo Authenticator).</p>
                </div>

                <button type="submit" class="admin-btn-login">
                    <span>ENTRAR NO PAINEL</span> <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-regular', 'fa-eye');
                eyeIcon.classList.add('fa-solid', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-solid', 'fa-eye-slash');
                eyeIcon.classList.add('fa-regular', 'fa-eye');
            }
        }

        // Particle System
        (function() {
            const canvas = document.getElementById('particle-canvas');
            const ctx = canvas.getContext('2d');
            
            let particles = [];
            const colors = ['rgba(248, 204, 27, 0.4)', 'rgba(16, 185, 129, 0.4)', 'rgba(255, 255, 255, 0.25)'];
            const lineColors = 'rgba(248, 204, 27, 0.03)';
            
            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = (Math.random() - 0.5) * 0.4;
                    this.vy = (Math.random() - 0.5) * 0.4;
                    this.radius = Math.random() * 2 + 1;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                }
                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    
                    if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                }
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    ctx.fillStyle = this.color;
                    ctx.fill();
                }
            }

            function init() {
                const count = Math.min(Math.floor((canvas.width * canvas.height) / 12000), 100);
                particles = [];
                for (let i = 0; i < count; i++) {
                    particles.push(new Particle());
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                // Draw connection lines
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        
                        if (dist < 150) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(248, 204, 27, ${0.05 * (1 - dist / 150)})`;
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }

                // Update & draw particles
                particles.forEach(p => {
                    p.update();
                    p.draw();
                });

                requestAnimationFrame(animate);
            }

            init();
            animate();
            
            // Re-init on resize to keep particle density balanced
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    resize();
                    init();
                }, 100);
            });
        })();
    </script>
</body>
</html>
