@extends('layouts.explorer')

@section('title', 'Транзакция ' . Str::limit($tx['hash'] ?? '', 16))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    @php
        $status = $tx['status'] ?? 'pending';
        $statusClass = $status === 'confirmed' ? 'text-emerald-400' : ($status === 'failed' ? 'text-red-400' : 'explorer-primary');
        $statusIcon = $status === 'confirmed' ? '✓' : ($status === 'failed' ? '✗' : '⏳');
    @endphp

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Транзакция</h1>
    <p class="font-mono explorer-text-muted text-xs sm:text-sm break-all mb-4 sm:mb-6">{{ $tx['hash'] ?? '—' }}</p>
    <p class="mb-4 sm:mb-6"><span class="{{ $statusClass }} font-medium">{{ $statusIcon }} {{ $status === 'confirmed' ? 'Успешно' : ($status === 'failed' ? 'Ошибка' : 'Ожидание') }}</span></p>

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-x-auto mb-6 sm:mb-8">
        <table class="w-full text-sm min-w-[280px]">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted w-36 sm:w-48">Блок</td><td class="px-3 sm:px-4 py-3 explorer-text break-all">@if(!empty($tx['block_id']))<a href="{{ route('explorer.block.show', ['number' => $tx['block_id']]) }}" class="explorer-primary hover:underline">{{ $tx['block_id'] }}</a>@else — @endif</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Время</td><td class="px-3 sm:px-4 py-3 explorer-text text-xs sm:text-sm">{{ isset($tx['timestamp']) ? \Carbon\Carbon::parse($tx['timestamp'])->format('d.m.Y H:i:s') : '—' }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">От</td><td class="px-3 sm:px-4 py-3 break-all"><a href="{{ route('explorer.address.show', ['address' => $tx['sender'] ?? '']) }}" class="font-mono explorer-primary hover:underline text-xs sm:text-sm">{{ $tx['sender'] ?? '—' }}</a></td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">Кому</td><td class="px-3 sm:px-4 py-3 break-all"><a href="{{ route('explorer.address.show', ['address' => $tx['recipient'] ?? '']) }}" class="font-mono explorer-primary hover:underline text-xs sm:text-sm">{{ $tx['recipient'] ?? '—' }}</a></td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Сумма</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text text-xs sm:text-sm">{{ $tx['value'] ?? '0' }} {{ $tx['symbol'] ?? 'GND' }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Комиссия</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text">{{ $tx['fee'] ?? '—' }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Nonce</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text">{{ $tx['nonce'] ?? '—' }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Тип</td><td class="px-3 sm:px-4 py-3 explorer-text">{{ $tx['type'] ?? '—' }}</td></tr>
                @if(!empty($tx['data']) || !empty($tx['payload']))
                    <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">Input Data</td><td class="px-3 sm:px-4 py-3 font-mono text-xs break-all explorer-text-muted-3 word-break-all">{{ is_string($tx['data'] ?? $tx['payload'] ?? '') ? $tx['data'] ?? $tx['payload'] : json_encode($tx['data'] ?? $tx['payload']) }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
