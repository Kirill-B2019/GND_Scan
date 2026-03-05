@extends('layouts.explorer')

@section('title', 'Адрес ' . Str::limit($address, 20))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Адрес</h1>
    <p class="font-mono explorer-text-muted-4 text-xs sm:text-sm break-all mb-4 sm:mb-6">{{ $address }}</p>

    @if($balanceError)
        <div class="rounded-xl explorer-alert border px-4 py-2 mb-6 text-sm">{{ $balanceError }}</div>
    @endif

    {{-- Balance --}}
    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden mb-6 sm:mb-8">
        <h2 class="px-3 sm:px-4 py-3 border-b explorer-border text-base sm:text-lg font-semibold explorer-text">Баланс</h2>
        <div class="p-3 sm:p-4">
            @if($balance && isset($balance['address']))
                @php $balances = $balance['balances'] ?? []; @endphp
                @forelse($balances as $b)
                    <div class="flex justify-between items-center py-2 text-sm">
                        <span class="explorer-text-muted">{{ $b['symbol'] ?? $b['token_address'] ?? 'GND' }}</span>
                        <span class="font-mono explorer-primary break-all">{{ $b['balance'] ?? '0' }}</span>
                    </div>
                @empty
                    <p class="explorer-text-muted">Нет данных о балансе</p>
                @endforelse
            @else
                <p class="explorer-text-muted">Нет данных о балансе</p>
            @endif
        </div>
    </div>

    <h2 class="text-base sm:text-lg font-semibold explorer-text mb-3">Транзакции (последние)</h2>
    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden">
        @forelse($transactions as $tx)
            <a href="{{ route('explorer.transaction.show', ['hash' => $tx['hash'] ?? '']) }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-3 sm:px-4 py-3 border-b explorer-divider last:border-0 explorer-row-hover transition-colors min-h-[44px] sm:min-h-0">
                <span class="font-mono text-xs sm:text-sm explorer-primary break-all">{{ $tx['hash'] ?? '—' }}</span>
                <span class="explorer-text-muted text-xs sm:text-sm">{{ $tx['value'] ?? '0' }} GND · {{ $tx['status'] ?? '—' }}</span>
            </a>
        @empty
            <div class="px-4 py-8 explorer-text-muted text-center">Нет транзакций</div>
        @endforelse
    </div>
@endsection
