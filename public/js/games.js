// Games Engines for vera.bet demo games

// Shareable variables
let activeBetId = null;
let currentBetAmount = 0;

/* --- AVIATOR ENGINE --- */
let aviatorInterval = null;
let aviatorProgress = 0;
let aviatorMultiplier = 1.00;
let aviatorRunning = false;
let aviatorCrashed = false;
let aviatorCrashPoint = 0;

function resetAviator() {
    stopAviatorEngine();
    aviatorProgress = 0;
    aviatorMultiplier = 1.00;
    aviatorRunning = false;
    aviatorCrashed = false;
    
    document.getElementById('aviatorMultiplierText').textContent = '1.00x';
    document.getElementById('aviatorMultiplierText').style.color = '#fff';
    document.getElementById('aviatorMessage').textContent = 'FAÇA SUA APOSTA';
    
    const planeWrapper = document.getElementById('aviatorPlaneWrapper');
    planeWrapper.style.bottom = '20px';
    planeWrapper.style.left = '20px';
    
    const actionBtn = document.getElementById('aviatorActionButton');
    actionBtn.textContent = 'APOSTAR';
    actionBtn.disabled = false;
    actionBtn.className = 'game-action-btn color-aviator';
}

function startAviatorRound() {
    const actionBtn = document.getElementById('aviatorActionButton');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!aviatorRunning && !aviatorCrashed) {
        // Place Bet
        const inputAmount = document.getElementById('aviatorBetAmount').value;
        currentBetAmount = parseFloat(inputAmount);

        actionBtn.disabled = true;
        actionBtn.textContent = 'PROCESSANDO...';

        fetch(window.Laravel.routes.gameBet, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                game: 'aviator',
                bet_amount: currentBetAmount
            })
        })
        .then(response => {
            if (!response.ok) return response.json().then(e => { throw e; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                activeBetId = data.bet_id;
                updateBalanceUI(data.balance_formatted);
                
                // Start round
                aviatorRunning = true;
                actionBtn.disabled = false;
                actionBtn.textContent = `SACAR R$ ${currentBetAmount.toFixed(2)}`;
                actionBtn.className = 'game-action-btn bg-gold';
                document.getElementById('aviatorMessage').textContent = 'DECOLANDO...';
                
                // Determine crash point in secret (between 1.05 and 10.00)
                aviatorCrashPoint = parseFloat((Math.random() * 8 + 1.05).toFixed(2));
                
                runAviatorEngine();
            }
        })
        .catch(error => {
            alert(error.message || 'Erro ao realizar aposta.');
            resetAviator();
        });

    } else if (aviatorRunning && !aviatorCrashed) {
        // Cashout
        actionBtn.disabled = true;
        
        fetch(window.Laravel.routes.gamePayout, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                bet_id: activeBetId,
                multiplier: aviatorMultiplier,
                status: 'win'
            })
        })
        .then(response => {
            if (!response.ok) return response.json().then(e => { throw e; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                stopAviatorEngine();
                updateBalanceUI(data.balance_formatted);
                
                document.getElementById('aviatorMessage').innerHTML = `<span class="text-green">VOCÊ SACOU R$ ${data.payout_formatted}!</span>`;
                document.getElementById('aviatorMultiplierText').style.color = '#00e676';
                actionBtn.textContent = 'APOSTAR';
                actionBtn.className = 'game-action-btn color-aviator';
                actionBtn.disabled = true;
                
                setTimeout(() => {
                    resetAviator();
                }, 3000);
            }
        })
        .catch(error => {
            alert('Erro ao processar saque.');
            resetAviator();
        });
    }
}

function runAviatorEngine() {
    aviatorInterval = setInterval(() => {
        aviatorProgress += 0.04;
        
        // Multiplier curves up
        aviatorMultiplier = parseFloat((1.00 + Math.pow(aviatorProgress, 2)).toFixed(2));
        document.getElementById('aviatorMultiplierText').textContent = `${aviatorMultiplier.toFixed(2)}x`;
        
        // Update Action Button Payout Value
        const currentPayout = currentBetAmount * aviatorMultiplier;
        document.getElementById('aviatorActionButton').textContent = `SACAR R$ ${currentPayout.toFixed(2)}`;

        // Move Plane along curve
        const planeWrapper = document.getElementById('aviatorPlaneWrapper');
        const screen = document.getElementById('aviatorScreen');
        
        const maxX = screen.clientWidth - 100;
        const maxY = screen.clientHeight - 100;
        
        const posX = Math.min(aviatorProgress * 80, maxX);
        const posY = Math.min(Math.pow(aviatorProgress, 1.8) * 45, maxY);
        
        planeWrapper.style.left = `${20 + posX}px`;
        planeWrapper.style.bottom = `${20 + posY}px`;

        // Check crash
        if (aviatorMultiplier >= aviatorCrashPoint) {
            triggerAviatorCrash();
        }
    }, 100);
}

function triggerAviatorCrash() {
    stopAviatorEngine();
    aviatorCrashed = true;
    aviatorRunning = false;
    
    document.getElementById('aviatorMultiplierText').textContent = 'FLEW AWAY!';
    document.getElementById('aviatorMultiplierText').style.color = '#ff3d00';
    document.getElementById('aviatorMessage').textContent = `Crashed @ ${aviatorMultiplier.toFixed(2)}x`;
    
    const actionBtn = document.getElementById('aviatorActionButton');
    actionBtn.disabled = true;
    actionBtn.textContent = 'PERDEU';
    actionBtn.className = 'game-action-btn';

    // Fly plane off screen
    const planeWrapper = document.getElementById('aviatorPlaneWrapper');
    planeWrapper.style.transition = 'all 0.8s ease-in';
    planeWrapper.style.left = '120%';
    planeWrapper.style.bottom = '120%';

    // Report loss to backend
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(window.Laravel.routes.gamePayout, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            bet_id: activeBetId,
            multiplier: 0,
            status: 'lose'
        })
    }).catch(err => console.log('Erro ao reportar perda.'));

    setTimeout(() => {
        planeWrapper.style.transition = 'none';
        resetAviator();
    }, 4000);
}

function stopAviatorEngine() {
    if (aviatorInterval) {
        clearInterval(aviatorInterval);
        aviatorInterval = null;
    }
}


/* --- MINES ENGINE --- */
let minesMines = [];
let minesRevealedCount = 0;
let minesActive = false;
let minesCount = 3;
let minesMultiplier = 1.00;

function resetMines() {
    minesActive = false;
    minesRevealedCount = 0;
    minesMultiplier = 1.00;
    minesMines = [];

    document.getElementById('minesMultiplierText').textContent = '1.00x';
    document.getElementById('minesPossiblePayout').textContent = 'R$ 0,00';
    
    const board = document.getElementById('minesBoard');
    board.innerHTML = '';
    // Generate 25 face-down tiles
    for (let i = 0; i < 25; i++) {
        const tile = document.createElement('div');
        tile.className = 'mines-tile';
        tile.dataset.index = i;
        tile.innerHTML = '<i class="fa-solid fa-question text-muted"></i>';
        tile.addEventListener('click', () => clickMinesTile(tile));
        board.appendChild(tile);
    }

    const actionBtn = document.getElementById('minesActionButton');
    actionBtn.textContent = 'COMEÇAR JOGO';
    actionBtn.disabled = false;
    actionBtn.className = 'game-action-btn color-mines';
}

function startMinesRound() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const actionBtn = document.getElementById('minesActionButton');

    if (!minesActive) {
        // Place Bet
        const inputAmount = document.getElementById('minesBetAmount').value;
        currentBetAmount = parseFloat(inputAmount);
        
        const minesSelect = document.getElementById('minesCount');
        minesCount = parseInt(minesSelect.value);

        actionBtn.disabled = true;
        actionBtn.textContent = 'PROCESSANDO...';

        fetch(window.Laravel.routes.gameBet, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                game: 'mines',
                bet_amount: currentBetAmount
            })
        })
        .then(response => {
            if (!response.ok) return response.json().then(e => { throw e; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                activeBetId = data.bet_id;
                updateBalanceUI(data.balance_formatted);
                
                // Generate Mines locations
                minesMines = [];
                while (minesMines.length < minesCount) {
                    const randPos = Math.floor(Math.random() * 25);
                    if (!minesMines.includes(randPos)) {
                        minesMines.push(randPos);
                    }
                }

                minesActive = true;
                minesRevealedCount = 0;
                minesMultiplier = 1.00;
                
                actionBtn.disabled = false;
                actionBtn.textContent = `SACAR R$ ${currentBetAmount.toFixed(2)}`;
                actionBtn.className = 'game-action-btn bg-gold';
                
                document.getElementById('minesMultiplierText').textContent = '1.00x';
                document.getElementById('minesPossiblePayout').textContent = `R$ ${currentBetAmount.toFixed(2)}`;
            }
        })
        .catch(error => {
            alert(error.message || 'Erro ao realizar aposta.');
            resetMines();
        });

    } else {
        // Cashout Mines
        actionBtn.disabled = true;

        fetch(window.Laravel.routes.gamePayout, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                bet_id: activeBetId,
                multiplier: minesMultiplier,
                status: 'win'
            })
        })
        .then(response => {
            if (!response.ok) return response.json().then(e => { throw e; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                minesActive = false;
                updateBalanceUI(data.balance_formatted);
                
                // Reveal everything
                revealAllMines(true);
                
                actionBtn.textContent = 'COMEÇAR JOGO';
                actionBtn.className = 'game-action-btn color-mines';
                actionBtn.disabled = true;

                setTimeout(() => {
                    resetMines();
                }, 3000);
            }
        })
        .catch(error => {
            alert('Erro ao processar saque.');
            resetMines();
        });
    }
}

function clickMinesTile(tile) {
    if (!minesActive) return;
    if (tile.classList.contains('revealed-star') || tile.classList.contains('revealed-mine')) return;

    const index = parseInt(tile.dataset.index);

    if (minesMines.includes(index)) {
        // Hit Mine - Explode!
        minesActive = false;
        tile.className = 'mines-tile revealed-mine';
        tile.innerHTML = '<i class="fa-solid fa-bomb text-red"></i>';
        
        revealAllMines(false);

        const actionBtn = document.getElementById('minesActionButton');
        actionBtn.disabled = true;
        actionBtn.textContent = 'EXPLODIU!';
        actionBtn.className = 'game-action-btn';

        // Report loss to backend
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(window.Laravel.routes.gamePayout, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                bet_id: activeBetId,
                multiplier: 0,
                status: 'lose'
            })
        }).catch(err => console.log('Erro ao reportar perda.'));

        setTimeout(() => {
            resetMines();
        }, 4000);

    } else {
        // Hit Star - Win progress!
        tile.className = 'mines-tile revealed-star';
        tile.innerHTML = '<i class="fa-solid fa-star text-gold"></i>';
        
        minesRevealedCount++;
        
        // Multiplier progression formula
        // More mines = higher risk = bigger multiplier growth
        const step = minesCount * 0.15 + 0.10;
        minesMultiplier = parseFloat((1.00 + (minesRevealedCount * step)).toFixed(2));
        
        document.getElementById('minesMultiplierText').textContent = `${minesMultiplier.toFixed(2)}x`;
        
        const possiblePayout = currentBetAmount * minesMultiplier;
        document.getElementById('minesPossiblePayout').textContent = possiblePayout.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        
        const actionBtn = document.getElementById('minesActionButton');
        actionBtn.textContent = `SACAR R$ ${possiblePayout.toFixed(2)}`;
    }
}

function revealAllMines(userWon) {
    const tiles = document.querySelectorAll('.mines-tile');
    tiles.forEach(tile => {
        const index = parseInt(tile.dataset.index);
        if (minesMines.includes(index)) {
            tile.className = 'mines-tile revealed-mine';
            tile.innerHTML = '<i class="fa-solid fa-bomb text-red"></i>';
        } else {
            if (!tile.classList.contains('revealed-star')) {
                tile.className = 'mines-tile revealed-star';
                tile.style.opacity = '0.5';
                tile.innerHTML = '<i class="fa-solid fa-star text-gold"></i>';
            }
        }
    });
}

function stopMinesEngine() {
    minesActive = false;
}


/* --- FORTUNE TIGER ENGINE --- */
const tigerEmojis = ['🍊', '🧧', '🪙', '🐯', '💰', '🥬'];
let tigerSpinning = false;

function resetTiger() {
    tigerSpinning = false;
    document.getElementById('tigerStatusBanner').textContent = 'BOA SORTE!';
    document.getElementById('tigerStatusBanner').style.background = '#ffd700';
    document.getElementById('tigerStatusBanner').style.color = '#5c0606';
    
    const spinBtn = document.getElementById('tigerSpinButton');
    spinBtn.disabled = false;
    spinBtn.textContent = 'GIRAR';
}

function spinTiger() {
    if (tigerSpinning) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const spinBtn = document.getElementById('tigerSpinButton');
    const inputAmount = document.getElementById('tigerBetAmount').value;
    currentBetAmount = parseFloat(inputAmount);

    spinBtn.disabled = true;
    spinBtn.textContent = 'GIRANDO...';
    tigerSpinning = true;
    document.getElementById('tigerStatusBanner').textContent = 'TORCENDO...';

    fetch(window.Laravel.routes.gameBet, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            game: 'fortune_tiger',
            bet_amount: currentBetAmount
        })
    })
    .then(response => {
        if (!response.ok) return response.json().then(e => { throw e; });
        return response.json();
    })
    .then(data => {
        if (data.success) {
            activeBetId = data.bet_id;
            updateBalanceUI(data.balance_formatted);

            // Animate spin reels
            animateTigerReels();

            // Randomly determine outcome (35% win probability)
            const outcome = Math.random() < 0.35 ? 'win' : 'lose';
            let multiplier = 0;
            let winSymbols = [];

            if (outcome === 'win') {
                multiplier = [2, 3, 5, 10][Math.floor(Math.random() * 4)];
                // Choose a random emoji for win rows
                const winningEmoji = tigerEmojis[Math.floor(Math.random() * tigerEmojis.length)];
                winSymbols = [winningEmoji, winningEmoji, winningEmoji];
            } else {
                // Generate non-matching reels
                const e1 = tigerEmojis[Math.floor(Math.random() * tigerEmojis.length)];
                let e2 = tigerEmojis[Math.floor(Math.random() * tigerEmojis.length)];
                let e3 = tigerEmojis[Math.floor(Math.random() * tigerEmojis.length)];
                while (e1 === e2 && e2 === e3) {
                    e2 = tigerEmojis[Math.floor(Math.random() * tigerEmojis.length)];
                }
                winSymbols = [e1, e2, e3];
            }

            // Backend payout record reporting
            setTimeout(() => {
                fetch(window.Laravel.routes.gamePayout, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        bet_id: activeBetId,
                        multiplier: multiplier,
                        status: outcome
                    })
                })
                .then(res => res.json())
                .then(payoutData => {
                    tigerSpinning = false;
                    spinBtn.disabled = false;
                    spinBtn.textContent = 'GIRAR';

                    // Stop reels at correct symbols
                    stopTigerReels(winSymbols);

                    if (outcome === 'win') {
                        updateBalanceUI(payoutData.balance_formatted);
                        document.getElementById('tigerStatusBanner').textContent = `GANHOU R$ ${payoutData.payout_formatted}!`;
                        document.getElementById('tigerStatusBanner').style.background = '#00e676';
                        document.getElementById('tigerStatusBanner').style.color = '#000';
                        document.querySelector('.tiger-bubble').textContent = `Uau! Você ganhou ${multiplier}x!`;
                    } else {
                        document.getElementById('tigerStatusBanner').textContent = 'TENTE NOVAMENTE!';
                        document.getElementById('tigerStatusBanner').style.background = '#ff3d00';
                        document.getElementById('tigerStatusBanner').style.color = '#fff';
                        document.querySelector('.tiger-bubble').textContent = 'Quase lá, gire novamente!';
                    }
                })
                .catch(err => {
                    alert('Erro ao processar resultado.');
                    resetTiger();
                });
            }, 1500); // spin lasts 1.5 seconds
        }
    })
    .catch(error => {
        alert(error.message || 'Erro ao realizar aposta.');
        resetTiger();
    });
}

let tigerReelIntervals = [];

function animateTigerReels() {
    tigerReelIntervals.forEach(interval => clearInterval(interval));
    tigerReelIntervals = [];

    // Spin speed intervals
    for (let r = 0; r < 3; r++) {
        const reelEl = document.getElementById(`reel${r}`);
        const interval = setInterval(() => {
            // Roll symbols
            const symbols = reelEl.querySelectorAll('.reel-symbol');
            symbols.forEach(sym => {
                const randEmoji = tigerEmojis[Math.floor(Math.random() * tigerEmojis.length)];
                sym.textContent = randEmoji;
            });
        }, 80);
        tigerReelIntervals.push(interval);
    }
}

function stopTigerReels(symbols) {
    tigerReelIntervals.forEach(interval => clearInterval(interval));
    tigerReelIntervals = [];

    // Set static outcome
    for (let r = 0; r < 3; r++) {
        const reelEl = document.getElementById(`reel${r}`);
        const symbolEls = reelEl.querySelectorAll('.reel-symbol');
        symbolEls.forEach(sym => {
            sym.textContent = symbols[r];
        });
    }
}


/* --- SHARED HELPERS --- */
function updateBalanceUI(formattedBalance) {
    const balEl = document.getElementById('headerUserBalance');
    if (balEl) {
        balEl.textContent = `R$ ${formattedBalance}`;
    }
    // Also update wallet value in game controls if shown anywhere
}

function adjustBet(inputId, amount) {
    const input = document.getElementById(inputId);
    if (input) {
        let val = parseInt(input.value) + amount;
        if (val < 1) val = 1;
        if (val > 5000) val = 5000;
        input.value = val;
    }
}
