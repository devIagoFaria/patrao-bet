// App Script for vera.bet

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sliding Banner Carousel
    initBannersSlider();

    // 2. Real-time Winner Feed Stream
    initWinnerFeed();

    // 3. Scroll to top button toggler
    window.addEventListener('scroll', handleScrollTopBtn);

    // 4. Sidebar toggle (responsive collapsed on desktop, drawer on mobile)
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarLeft = document.getElementById('sidebarLeft');
    if (toggleBtn && sidebarLeft) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebarLeft.classList.remove('collapsed');
                sidebarLeft.classList.toggle('open');
            } else {
                sidebarLeft.classList.remove('open');
                sidebarLeft.classList.toggle('collapsed');
                
                // Trigger window resize event so that banners slider recalculates container width
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));
                }, 300); // match transition duration
            }
        });
    }

    // 5. AJAX Form Handlers (Auth)
    initAuthForms();

    // 6. Registration input masks (CPF and Phone)
    initRegistrationInputMasks();

    // 7. Password Strength Indicator Popover
    initPasswordStrengthPopover();
});

/* Carousel / 8-Banners Slider Logic */
let bannerIndex = 0;
let bannerTimer;

function initBannersSlider() {
    const track = document.getElementById('bannersTrack');
    if (!track) return;
    
    startBannerAutoPlay();
    
    // Recalculate on window resize
    window.addEventListener('resize', updateBannersPosition);
}

function getVisibleBannersCount() {
    const width = window.innerWidth;
    if (width <= 768) return 1; // mobile/tablet portrait shows 1 banner
    if (width <= 1200) return 2; // tablet shows 2 banners
    return 4; // desktop shows 4 banners
}

function updateBannersPosition() {
    const track = document.getElementById('bannersTrack');
    if (!track) return;
    
    const items = track.querySelectorAll('.banner-item');
    if (items.length === 0) return;
    
    const visibleCount = getVisibleBannersCount();
    const maxIndex = items.length - visibleCount;
    
    // Ensure index is within bounds
    if (bannerIndex > maxIndex) {
        bannerIndex = 0;
    }
    
    const itemWidth = items[0].getBoundingClientRect().width;
    const gap = window.innerWidth <= 768 ? 0 : (window.innerWidth <= 1200 ? 12 : 16);
    const shift = bannerIndex * (itemWidth + gap);
    
    track.style.transform = `translateX(-${shift}px)`;
}

function moveBanners(direction) {
    clearInterval(bannerTimer);
    
    const track = document.getElementById('bannersTrack');
    if (!track) return;
    const items = track.querySelectorAll('.banner-item');
    const visibleCount = getVisibleBannersCount();
    const maxIndex = items.length - visibleCount;
    
    bannerIndex += direction;
    if (bannerIndex < 0) {
        bannerIndex = maxIndex;
    } else if (bannerIndex > maxIndex) {
        bannerIndex = 0;
    }
    
    updateBannersPosition();
    startBannerAutoPlay();
}

function startBannerAutoPlay() {
    bannerTimer = setInterval(() => {
        const track = document.getElementById('bannersTrack');
        if (!track) return;
        const items = track.querySelectorAll('.banner-item');
        const visibleCount = getVisibleBannersCount();
        const maxIndex = items.length - visibleCount;
        
        bannerIndex++;
        if (bannerIndex > maxIndex) {
            bannerIndex = 0;
        }
        
        updateBannersPosition();
    }, 4500); // Transitions every 4.5 seconds
}

/* Modals Handler */
function openModal(modalId) {
    console.log('openModal called for ID:', modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        console.log('Modal display set to flex.');
    } else {
        console.error('Modal with ID not found:', modalId);
    }
}

function closeModal(modalId) {
    console.log('closeModal called for ID:', modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        // Clear errors
        const errorDiv = modal.querySelector('.error-message');
        if (errorDiv) errorDiv.textContent = '';
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

/* Auth forms AJAX submission */
function initAuthForms() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const errorDiv = document.getElementById('loginError');
            errorDiv.textContent = '';

            const formData = new FormData(loginForm);

            fetch(window.Laravel.routes.login, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().catch(() => {
                        throw new Error('Erro interno do servidor. Por favor, tente novamente mais tarde.');
                    }).then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.reload(); // Reload page to update UI auth state
                }
            })
            .catch(error => {
                errorDiv.textContent = error.message || 'Erro ao fazer login. Verifique as credenciais.';
            });
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const errorDiv = document.getElementById('registerError');
            errorDiv.textContent = '';

            const emailInput = document.getElementById('reg_email');
            const passwordInput = document.getElementById('reg_password');
            const nameHidden = document.getElementById('reg_name');
            const confirmHidden = document.getElementById('reg_password_confirmation');

            if (passwordInput) {
                const metCount = validatePasswordStrength(passwordInput.value);
                if (metCount < 5) {
                    errorDiv.textContent = 'A sua senha deve cumprir todos os 5 requisitos de segurança.';
                    passwordInput.focus();
                    return;
                }
            }

            if (emailInput && nameHidden && !nameHidden.value) {
                nameHidden.value = emailInput.value.split('@')[0] || 'User';
            }
            if (passwordInput && confirmHidden) {
                confirmHidden.value = passwordInput.value;
            }

            const formData = new FormData(registerForm);

            fetch(window.Laravel.routes.register, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().catch(() => {
                        throw new Error('Erro interno do servidor. Por favor, tente novamente mais tarde.');
                    }).then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                if (error.errors) {
                    const firstKey = Object.keys(error.errors)[0];
                    errorDiv.textContent = error.errors[firstKey][0];
                } else {
                    errorDiv.textContent = error.message || 'Erro ao realizar cadastro.';
                }
            });
        });
    }

    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const errorDiv = document.getElementById('forgotPasswordError');
            const submitBtn = document.getElementById('forgotPasswordSubmitBtn');
            errorDiv.textContent = '';
            submitBtn.disabled = true;

            const email = document.getElementById('forgot_email').value;
            const formData = new FormData(forgotPasswordForm);

            fetch(window.Laravel.routes.passwordForgot, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                submitBtn.disabled = false;
                if (data.success) {
                    document.getElementById('reset_email').value = email;
                    document.getElementById('resetPasswordEmailLabel').textContent = email;
                    closeModal('forgotPasswordModal');
                    openModal('resetPasswordModal');
                } else {
                    errorDiv.textContent = data.message || 'Erro ao enviar o código.';
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                errorDiv.textContent = 'Erro interno do servidor. Tente novamente mais tarde.';
            });
        });
    }

    const resetPasswordForm = document.getElementById('resetPasswordForm');
    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const errorDiv = document.getElementById('resetPasswordError');
            const submitBtn = document.getElementById('resetPasswordSubmitBtn');
            errorDiv.textContent = '';
            submitBtn.disabled = true;

            const formData = new FormData(resetPasswordForm);

            fetch(window.Laravel.routes.passwordReset, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                submitBtn.disabled = false;
                if (data.success) {
                    closeModal('resetPasswordModal');
                    openModal('passwordResetSuccessModal');
                } else {
                    errorDiv.textContent = data.message || 'Erro ao redefinir a senha.';
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                errorDiv.textContent = 'Erro interno do servidor. Tente novamente mais tarde.';
            });
        });
    }
}

/* Dynamic Live Winner Feed (Right Sidebar) */
const mockWinnerNames = ['BRUNO DI**', 'CLARA SI**', 'FELIPE NE**', 'LUISA GO**', 'THIAGO AN**', 'RENATA CO**', 'ALEX CA**', 'ISABELA ME**', 'JOÃO P**', 'NATALIA SO**', 'MARIANA PI**', 'RODRIGO MO**'];
// Populated server-side from our own registered games (see home.blade.php),
// so thumbnails always point at real, existing images.
const mockGames = (window.winFeedGamesPool && window.winFeedGamesPool.length)
    ? window.winFeedGamesPool.map(g => ({ name: g.title, type: g.class, img: g.image }))
    : [];

function initWinnerFeed() {
    const initialFeeds = document.querySelectorAll('.win-feed');
    if (initialFeeds.length === 0 || mockGames.length === 0) return;

    // Periodicamente insere um novo card de ganhador
    setInterval(() => {
        // Escolhe detalhes aleatórios
        const randUser = mockWinnerNames[Math.floor(Math.random() * mockWinnerNames.length)];
        const randGame = mockGames[Math.floor(Math.random() * mockGames.length)];
        const randVal = (Math.random() * 5000 + 50).toFixed(2);
        const randValFormatted = parseFloat(randVal).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

        const feeds = document.querySelectorAll('.win-feed');
        feeds.forEach(feed => {
            // Cria o elemento win-card para este feed específico
            const winCard = document.createElement('div');
            winCard.className = 'win-card';

            // Imagem real do jogo cadastrado (evita thumbnail quebrado)
            let iconHtml = `<div class="win-card-icon ${randGame.type}"><img src="${randGame.img}" class="win-game-thumb"></div>`;

            winCard.innerHTML = `
                ${iconHtml}
                <div class="win-card-details">
                    <span class="win-username">${randUser}</span>
                    <span class="win-game">${randGame.name}</span>
                    <span class="win-amount">${randValFormatted}</span>
                </div>
            `;

            // Adiciona ao final para ser "empurrado"
            feed.appendChild(winCard);

            // Lógica de "Empurrar para Cima": 
            // Remove o primeiro item com uma animação de margem negativa
            if (feed.children.length > 12) {
                const firstChild = feed.children[0];
                const height = firstChild.offsetHeight + 8; // Altura + Gap
                
                firstChild.style.transition = 'all 0.8s ease-in-out';
                firstChild.style.marginTop = `-${height}px`;
                firstChild.style.opacity = '0';
                firstChild.style.transform = 'scale(0.9)';

                setTimeout(() => {
                    if (firstChild.parentNode === feed) {
                        feed.removeChild(firstChild);
                    }
                }, 800);
            }
        });
    }, 3000); // Mais rápido para ser visível
}

/* Scroll-to-top Button toggler */
function handleScrollTopBtn() {
    const btn = document.getElementById('scrollTopBtn');
    if (!btn) return;
    if (window.scrollY > 300) {
        btn.classList.add('visible');
    } else {
        btn.classList.remove('visible');
    }
}

/* Game authentication checker and launcher */
function checkAuthAndPlay(gameName) {
    // Check auth status from header user balance presence
    const userWallet = document.getElementById('headerUserBalance');
    if (!userWallet) {
        openModal('loginModal');
    } else {
        openGameModal(gameName);
    }
}

function openGameModal(gameName) {
    const modalId = `${gameName}Modal`;
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        // Trigger game start/reset
        if (gameName === 'aviator') resetAviator();
        if (gameName === 'mines') resetMines();
        if (gameName === 'fortune_tiger') resetTiger();
    }
}

function launchExternalGame(slug, clickedEl) {
    const userWallet = document.getElementById('headerUserBalance');
    if (!userWallet) {
        openModal('loginModal');
        return;
    }

    // Guard against double-clicks/double-taps firing multiple launches
    // (each launch call opens a brand new provider session/token).
    if (clickedEl) {
        if (clickedEl.dataset.launching === '1') return;
        clickedEl.dataset.launching = '1';
        setTimeout(() => { clickedEl.dataset.launching = '0'; }, 4000);
    }

    // Single navigation, single server-side launch call. The launch route
    // already handles both success (renders the game) and failure
    // (redirects home with an error message) when it's not an AJAX request.
    window.location.href = `/game/launch/${slug}`;
}

function closeGameModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        // Terminate any ongoing loops
        if (modalId === 'aviatorModal') stopAviatorEngine();
        if (modalId === 'minesModal') stopMinesEngine();
    }
}

/* Utility to scroll to section */
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        const offset = 80; // height of header
        const bodyRect = document.body.getBoundingClientRect().top;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
}

/* Utility to scroll to layout type */
function scrollToLayout(layoutType) {
    const section = document.querySelector(`[data-layout="${layoutType}"]`);
    if (section) {
        scrollToSection(section.id);
    } else {
        // Fallback: if not found, scroll to top of main content
        const firstSection = document.querySelector('.home-section');
        if (firstSection) {
            scrollToSection(firstSection.id);
        }
    }
}

/* Search bar filter function */
function searchGames() {
    const input = document.getElementById('gameSearchInput');
    if (!input) return;
    const filter = input.value.toUpperCase();
    const gameCards = document.querySelectorAll('.games-grid .game-card');

    gameCards.forEach(card => {
        const titleEl = card.querySelector('.game-card-title') || card.querySelector('.game-name');
        if (!titleEl) return;
        const title = titleEl.textContent || titleEl.innerText;
        if (title.toUpperCase().indexOf(filter) > -1) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

/* Toggle like action helper */
function toggleLike(btn) {
    btn.classList.toggle('liked');
    const icon = btn.querySelector('i');
    if (btn.classList.contains('liked')) {
        icon.className = 'fa-solid fa-heart text-red';
    } else {
        icon.className = 'fa-regular fa-heart';
    }
}

/* Toggle password visibility helper */
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}

/* Simulate CPF validation helper */
function validateCPFAction() {
    const cpfInput = document.getElementById('reg_cpf');
    const errorDiv = document.getElementById('registerError');
    if (errorDiv) errorDiv.textContent = '';
    
    if (!cpfInput || !cpfInput.value.trim()) {
        alert('Por favor, digite seu CPF.');
        return;
    }
    
    const cleanCPF = cpfInput.value.replace(/\D/g, '');
    if (cleanCPF.length !== 11) {
        alert('CPF inválido. Por favor, insira um CPF com 11 dígitos.');
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const validateBtn = document.querySelector('.btn-input-action-validate');
    if (validateBtn) {
        validateBtn.textContent = 'Validando...';
        validateBtn.disabled = true;
    }
    
    fetch(window.Laravel.routes.cpfValidate, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ cpf: cleanCPF })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (validateBtn) {
                validateBtn.textContent = 'Validado!';
                validateBtn.style.background = '#00c853';
                validateBtn.style.color = '#fff';
                validateBtn.disabled = true;
            }
            cpfInput.style.borderColor = '#00c853';
            cpfInput.readOnly = true;
            
            // Set the hidden name field so that registration is done with the real name linked to the CPF
            const nameHidden = document.getElementById('reg_name');
            if (nameHidden) {
                nameHidden.value = data.name;
            }
        }
    })
    .catch(error => {
        if (validateBtn) {
            validateBtn.textContent = 'Validar';
            validateBtn.disabled = false;
        }
        cpfInput.style.borderColor = '#ff3d00';
        if (errorDiv) {
            errorDiv.textContent = error.message || 'CPF inválido ou inexistente.';
        } else {
            alert(error.message || 'CPF inválido ou inexistente.');
        }
    });
}

/* CPF and Telephone formatting masks */
function initRegistrationInputMasks() {
    const cpfInput = document.getElementById('reg_cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Keep only digits
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            // Format: 000.000.000-00
            if (value.length > 9) {
                e.target.value = `${value.slice(0, 3)}.${value.slice(3, 6)}.${value.slice(6, 9)}-${value.slice(9)}`;
            } else if (value.length > 6) {
                e.target.value = `${value.slice(0, 3)}.${value.slice(3, 6)}.${value.slice(6)}`;
            } else if (value.length > 3) {
                e.target.value = `${value.slice(0, 3)}.${value.slice(3)}`;
            } else {
                e.target.value = value;
            }
        });
    }

    const phoneInput = document.getElementById('reg_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Keep only digits
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            // Format: (00) 00000-0000
            if (value.length > 6) {
                e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
            } else if (value.length > 2) {
                e.target.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
            } else if (value.length > 0) {
                e.target.value = `(${value}`;
            } else {
                e.target.value = '';
            }
        });
    }
}

/* Password Strength Popover Functions */
function initPasswordStrengthPopover() {
    const passwordInput = document.getElementById('reg_password');
    const popover = document.getElementById('passwordPopover');
    const closeBtn = document.getElementById('closePopoverBtn');
    
    if (!passwordInput || !popover) return;

    // Show popover on focus
    passwordInput.addEventListener('focus', () => {
        popover.style.display = 'flex';
        // Force reflow for transitions
        popover.offsetHeight;
        popover.classList.add('show');
        // Initial validation run
        validatePasswordStrength(passwordInput.value);
    });

    // Validate on input
    passwordInput.addEventListener('input', () => {
        validatePasswordStrength(passwordInput.value);
    });

    // Close on click "Concluir"
    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            closePasswordPopover();
        });
    }

    // Close on click outside
    document.addEventListener('click', (e) => {
        if (!popover.classList.contains('show')) return;
        
        // Check if the click is outside the input and the popover
        const isClickInsideInput = passwordInput.contains(e.target);
        const isClickInsidePopover = popover.contains(e.target);
        const isClickToggleEye = e.target.closest('.password-toggle-eye');

        if (!isClickInsideInput && !isClickInsidePopover && !isClickToggleEye) {
            closePasswordPopover();
        }
    });
}

function closePasswordPopover() {
    const popover = document.getElementById('passwordPopover');
    if (popover && popover.classList.contains('show')) {
        popover.classList.remove('show');
        // Wait for transition to complete before hiding display
        setTimeout(() => {
            if (!popover.classList.contains('show')) {
                popover.style.display = 'none';
            }
        }, 200); // matches CSS transition duration
    }
}

function getPasswordStrengthMetCount(val) {
    const hasLetter = /[a-zA-Z]/.test(val);
    const hasNumber = /\d/.test(val);
    const hasSpecial = /[@$!%*#?&.\-]/.test(val);
    const hasMinLength = val.length >= 8;
    const hasOnlyAllowed = /^[a-zA-Z0-9@$!%*#?&.\-]*$/.test(val);

    return {
        hasLetter,
        hasNumber,
        hasSpecial,
        hasMinLength,
        hasOnlyAllowed,
        count: (hasLetter ? 1 : 0) + (hasNumber ? 1 : 0) + (hasSpecial ? 1 : 0) + (hasMinLength ? 1 : 0) + (hasOnlyAllowed ? 1 : 0)
    };
}

function validatePasswordStrength(val) {
    const result = getPasswordStrengthMetCount(val);

    setItemValid('req-letter', result.hasLetter);
    setItemValid('req-number', result.hasNumber);
    setItemValid('req-special', result.hasSpecial);
    setItemValid('req-length', result.hasMinLength);
    setItemValid('req-chars', result.hasOnlyAllowed);

    updateProgressBar(result.count);

    return result.count;
}

function setItemValid(elId, isValid) {
    const el = document.getElementById(elId);
    if (!el) return;
    const icon = el.querySelector('.req-icon i');
    if (isValid) {
        el.classList.remove('invalid');
        el.classList.add('valid');
        if (icon) {
            icon.className = 'fa-solid fa-check';
        }
    } else {
        el.classList.remove('valid');
        el.classList.add('invalid');
        if (icon) {
            icon.className = 'fa-solid fa-xmark';
        }
    }
}

function updateProgressBar(metCount) {
    const progressBar = document.getElementById('popoverProgressBar');
    if (!progressBar) return;
    
    // Calculate percentage (0 to 100%)
    const pct = metCount * 20;
    progressBar.style.width = `${pct}%`;

    // Remove all color classes
    progressBar.classList.remove('red', 'yellow', 'blue', 'green');

    // Add color class based on met rules
    if (metCount <= 1) {
        progressBar.classList.add('red');
    } else if (metCount === 2) {
        progressBar.classList.add('yellow');
    } else if (metCount === 3 || metCount === 4) {
        progressBar.classList.add('blue');
    } else if (metCount === 5) {
        progressBar.classList.add('green');
    }
}

/* Logged In Header Buttons Features */
function refreshUserBalance() {
    const refreshIcon = document.getElementById('balanceRefreshIcon');
    const balanceText = document.getElementById('headerUserBalance');
    if (!refreshIcon || !balanceText) return;

    // Start spin animation
    refreshIcon.style.transition = 'transform 0.4s ease';
    refreshIcon.style.transform = 'rotate(360deg)';
    
    fetch(window.Laravel.routes.userBalance)
        .then(response => {
            if (!response.ok) throw new Error();
            return response.json();
        })
        .then(data => {
            if (data.balance_formatted) {
                balanceText.textContent = `R$ ${data.balance_formatted}`;
                if (typeof updateBalanceColor === 'function') {
                    updateBalanceColor(`R$ ${data.balance_formatted}`);
                }
            }
        })
        .catch(err => console.log('Erro ao atualizar saldo.'))
        .finally(() => {
            // Reset rotate style after transition completes
            setTimeout(() => {
                refreshIcon.style.transition = 'none';
                refreshIcon.style.transform = 'none';
            }, 400);
        });
}

// Close quick-access dropdown when clicking outside
document.addEventListener('click', (e) => {
    const dropdown = document.getElementById('quickAccessDropdown');
    const wrapper  = document.getElementById('quickAccessWrapper');
    if (dropdown && dropdown.classList.contains('show')) {
        if (!wrapper || !wrapper.contains(e.target)) {
            dropdown.classList.remove('show');
            // restore badge dot
            const dot = document.getElementById('bellBadgeDot');
            if (dot) dot.style.display = '';
        }
    }
});

/* Quick Access Dropdown toggle functions */
function toggleQuickAccess() {
    const dropdown = document.getElementById('quickAccessDropdown');
    if (!dropdown) return;
    if (dropdown.classList.contains('show')) {
        closeQuickAccess();
    } else {
        dropdown.classList.add('show');
        // Hide the badge dot while dropdown is open
        const dot = document.getElementById('bellBadgeDot');
        if (dot) dot.style.display = 'none';
    }
}

function closeQuickAccess() {
    const dropdown = document.getElementById('quickAccessDropdown');
    if (dropdown) dropdown.classList.remove('show');
    const dot = document.getElementById('bellBadgeDot');
    if (dot) dot.style.display = '';
}

/* =====================================================
   PROFILE DRAWER FUNCTIONS
   ===================================================== */

function toggleProfileDrawer() {
    const drawer = document.getElementById('profileDrawer');
    if (!drawer) return;
    if (drawer.classList.contains('open')) {
        closeProfileDrawer();
    } else {
        openProfileDrawer();
    }
}

function openProfileDrawer() {
    const drawer = document.getElementById('profileDrawer');
    const overlay = document.getElementById('profileDrawerOverlay');
    if (!drawer) return;
    drawer.classList.add('open');
    if (overlay) overlay.classList.add('open');
    document.body.style.overflow = 'hidden'; // prevent background scroll
}

function closeProfileDrawer() {
    const drawer = document.getElementById('profileDrawer');
    const overlay = document.getElementById('profileDrawerOverlay');
    if (!drawer) return;
    drawer.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = ''; // restore scroll
}

// Close on ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeQuickAccess();        // close quick access dropdown
        hideLogoutConfirm();       // close confirm dialog first if open
        closeProfileDrawer();      // close profile drawer
    }
});


/* =====================================================
   LOGOUT CONFIRMATION DIALOG FUNCTIONS
   ===================================================== */

function showLogoutConfirm() {
    const overlay = document.getElementById('logoutConfirmOverlay');
    if (overlay) {
        overlay.classList.add('show');
    }
}

function hideLogoutConfirm() {
    const overlay = document.getElementById('logoutConfirmOverlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
}

function doLogout() {
    const form = document.getElementById('profileDrawerLogoutForm');
    if (form) {
        form.submit();
    }
}

