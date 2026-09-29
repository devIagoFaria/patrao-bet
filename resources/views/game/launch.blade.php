@extends('layouts.app')

@section('content')
<div class="game-launch-wrapper" style="width: 100%; height: calc(100vh - 70px); background: #000; position: relative;">
    <iframe src="{{ $gameUrl }}" 
            style="width: 100%; height: 100%; border: none;" 
            allowfullscreen 
            allow="autoplay; encrypted-media; fullscreen;">
    </iframe>
</div>

<script>
    // Update balance periodically if needed, but the iframe is now integrated into the layout
    setInterval(() => {
        fetch('{{ route("user.balance") }}')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const balanceEl = document.getElementById('headerUserBalance');
                    if (balanceEl) balanceEl.textContent = 'R$ ' + data.balance_formatted;
                }
            });
    }, 10000);
</script>
@endsection
