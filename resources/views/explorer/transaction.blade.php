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

    <h1 class="text-2xl font-bold explorer-text-white mb-2">Транзакция</h1>
    <p class="font-mono explorer-text-muted text-sm break-all mb-6">{{ $tx['hash'] ?? '—' }}</p>
    <p class="mb-6"><span class="{{ $statusClass }} font-medium">{{ $statusIcon }} {{ $status === 'confirmed' ? 'Успешно' : ($status === 'failed' ? 'Ошибка' : 'Ожидание') }}</span></p>

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden mb-8">
        <table class="w-full text-sm">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-4 py-3 explorer-text-muted w-48">Блок</td><td class="px-4 py-3 explorer-text">@if(!empty($tx['block_id']))<a href="{{ route('explorer.block.show', ['number' => $tx['block_id']]) }}" class="explorer-primary hover:underline">{{ $tx['block_id'] }}</a>@else — @endif</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Время</td><td class="px-4 py-3 explorer-text">{{ isset($tx['timestamp']) ? \Carbon\Carbon::parse($tx['timestamp'])->format('d.m.Y H:i:s') : '—' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">От</td><td class="px-4 py-3"><a href="{{ route('explorer.address.show', ['address' => $tx['sender'] ?? '']) }}" class="font-mono explorer-primary hover:underline break-all">{{ $tx['sender'] ?? '—' }}</a></td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Кому</td><td class="px-4 py-3"><a href="{{ route('explorer.address.show', ['address' => $tx['recipient'] ?? '']) }}" class="font-mono explorer-primary hover:underline break-all">{{ $tx['recipient'] ?? '—' }}</a></td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Сумма</td><td class="px-4 py-3 font-mono explorer-text">{{ $tx['value'] ?? '0' }} {{ $tx['symbol'] ?? 'GND' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Комиссия</td><td class="px-4 py-3 font-mono explorer-text">{{ $tx['fee'] ?? '—' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Nonce</td><td class="px-4 py-3 font-mono explorer-text">{{ $tx['nonce'] ?? '—' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Тип</td><td class="px-4 py-3 explorer-text">{{ $tx['type'] ?? '—' }}</td></tr>
                @if(!empty($tx['data']) || !empty($tx['payload']))
                    <tr><td class="px-4 py-3 explorer-text-muted align-top">Input Data</td><td class="px-4 py-3 font-mono text-xs break-all explorer-text-muted-3">{{ is_string($tx['data'] ?? $tx['payload'] ?? '') ? $tx['data'] ?? $tx['payload'] : json_encode($tx['data'] ?? $tx['payload']) }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
