@extends('layouts.explorer')

@section('title', 'Токен ' . Str::limit($address, 16))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-2xl font-bold explorer-text-white mb-2">Токен</h1>
    <p class="font-mono explorer-text-muted-4 text-sm break-all mb-6">{{ $address }}</p>

    @php
        $name = $state['name'] ?? $contract['name'] ?? '—';
        $symbol = $state['symbol'] ?? $contract['symbol'] ?? '—';
        $decimals = $state['decimals'] ?? $contract['decimals'] ?? 18;
        $totalSupply = $state['total_supply'] ?? $contract['total_supply'] ?? '—';
    @endphp

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden mb-8">
        <h2 class="px-4 py-3 border-b explorer-border text-lg font-semibold explorer-text">Информация</h2>
        <table class="w-full text-sm">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-4 py-3 explorer-text-muted w-48">Название</td><td class="px-4 py-3 explorer-text">{{ $name }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Символ</td><td class="px-4 py-3 font-mono explorer-primary">{{ $symbol }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Decimals</td><td class="px-4 py-3 font-mono explorer-text">{{ $decimals }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Total Supply</td><td class="px-4 py-3 font-mono explorer-text">{{ $totalSupply }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Контракт</td><td class="px-4 py-3"><a href="{{ route('explorer.contract.show', ['address' => $address]) }}" class="explorer-primary hover:underline font-mono break-all">{{ $address }}</a></td></tr>
            </tbody>
        </table>
    </div>

    @if(!empty($state['balances']))
        <h2 class="text-lg font-semibold explorer-text mb-3">Балансы (примеры)</h2>
        <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden">
            @foreach(array_slice($state['balances'] ?? [], 0, 20) as $addr => $bal)
                <a href="{{ route('explorer.address.show', ['address' => $addr]) }}" class="flex justify-between px-4 py-3 border-b explorer-divider explorer-row-hover transition-colors">
                    <span class="font-mono text-sm explorer-primary truncate max-w-[240px]">{{ $addr }}</span>
                    <span class="font-mono explorer-text-muted-4">{{ $bal }}</span>
                </a>
            @endforeach
        </div>
    @endif
@endsection
