@extends('layouts.explorer')

@section('title', 'Токен ' . Str::limit($address, 16))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Токен</h1>
    <p class="font-mono explorer-text-muted-4 text-xs sm:text-sm break-all mb-4 sm:mb-6">{{ $address }}</p>

    @php
        $name = $state['name'] ?? $contract['name'] ?? '—';
        $symbol = $state['symbol'] ?? $contract['symbol'] ?? '—';
        $decimals = $state['decimals'] ?? $contract['decimals'] ?? 18;
        $totalSupply = $state['total_supply'] ?? $contract['total_supply'] ?? '—';
    @endphp

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-x-auto mb-6 sm:mb-8">
        <h2 class="px-3 sm:px-4 py-3 border-b explorer-border text-base sm:text-lg font-semibold explorer-text">Информация</h2>
        <table class="w-full text-sm min-w-[280px]">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted w-36 sm:w-48">Название</td><td class="px-3 sm:px-4 py-3 explorer-text text-xs sm:text-sm">{{ $name }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Символ</td><td class="px-3 sm:px-4 py-3 font-mono explorer-primary text-xs sm:text-sm">{{ $symbol }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Decimals</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text">{{ $decimals }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Total Supply</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text text-xs sm:text-sm break-all">{{ $totalSupply }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">Контракт</td><td class="px-3 sm:px-4 py-3 break-all"><a href="{{ route('explorer.contract.show', ['address' => $address]) }}" class="explorer-primary hover:underline font-mono text-xs sm:text-sm">{{ $address }}</a></td></tr>
            </tbody>
        </table>
    </div>

    @if(!empty($state['balances']))
        <h2 class="text-base sm:text-lg font-semibold explorer-text mb-3">Балансы (примеры)</h2>
        <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden">
            @foreach(array_slice($state['balances'] ?? [], 0, 20) as $addr => $bal)
                <a href="{{ route('explorer.address.show', ['address' => $addr]) }}" class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 px-3 sm:px-4 py-3 border-b explorer-divider explorer-row-hover transition-colors min-h-[44px] sm:min-h-0">
                    <span class="font-mono text-xs sm:text-sm explorer-primary break-all">{{ $addr }}</span>
                    <span class="font-mono explorer-text-muted-4 text-xs sm:text-sm">{{ $bal }}</span>
                </a>
            @endforeach
        </div>
    @endif
@endsection
