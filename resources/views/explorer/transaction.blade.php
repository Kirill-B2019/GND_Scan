@extends('layouts.explorer')

@section('title', 'Транзакция ' . Str::limit($tx['Hash'] ?? $tx['hash'] ?? '', 16))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    @php
        $txHash = $tx['Hash'] ?? $tx['hash'] ?? '—';
        $status = $tx['Status'] ?? $tx['status'] ?? 'pending';
        $statusClass = ($status === 'confirmed' || $status === 'success') ? 'text-emerald-400' : (($status === 'failed' || $status === 'error') ? 'text-red-400' : 'explorer-primary');
        $statusIcon = ($status === 'confirmed' || $status === 'success') ? '✓' : (($status === 'failed' || $status === 'error') ? '✗' : '⏳');
        $blockId = $tx['BlockId'] ?? $tx['block_id'] ?? $tx['BlockNumber'] ?? $tx['block_number'] ?? null;
        $ts = $tx['Timestamp'] ?? $tx['timestamp'] ?? null;
        $from = $tx['Sender'] ?? $tx['sender'] ?? $tx['From'] ?? $tx['from'] ?? '—';
        $to = $tx['Recipient'] ?? $tx['recipient'] ?? $tx['To'] ?? $tx['to'] ?? '—';
        $value = $tx['Value'] ?? $tx['value'] ?? '0';
        $symbol = $tx['Symbol'] ?? $tx['symbol'] ?? 'GND';
        $fee = $tx['Fee'] ?? $tx['fee'] ?? '—';
        $nonce = $tx['Nonce'] ?? $tx['nonce'] ?? '—';
        $type = $tx['Type'] ?? $tx['type'] ?? '—';
    @endphp

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Транзакция</h1>
    <p class="font-mono explorer-text-muted text-xs sm:text-sm break-all mb-4 sm:mb-6">{{ $txHash }}</p>
    <p class="mb-4 sm:mb-6"><span class="{{ $statusClass }} font-medium">{{ $statusIcon }} {{ ($status === 'confirmed' || $status === 'success') ? 'Успешно' : (($status === 'failed' || $status === 'error') ? 'Ошибка' : 'Ожидание') }}</span></p>

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-x-auto mb-6 sm:mb-8">
        <table class="w-full text-sm min-w-[280px]">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted w-36 sm:w-48">Блок</td><td class="px-3 sm:px-4 py-3 explorer-text break-all">@if($blockId !== null && $blockId !== '')<a href="{{ route('explorer.block.show', ['number' => $blockId]) }}" class="explorer-primary hover:underline">{{ $blockId }}</a>@else — @endif</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Время</td><td class="px-3 sm:px-4 py-3 explorer-text text-xs sm:text-sm">{{ $ts ? \Carbon\Carbon::parse($ts)->setTimezone(config('app.timezone'))->format('d.m.Y H:i:s') : '—' }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">От</td><td class="px-3 sm:px-4 py-3 break-all"><a href="{{ route('explorer.address.show', ['address' => $from]) }}" class="font-mono explorer-primary hover:underline text-xs sm:text-sm break-all">{{ $from }}</a></td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">Кому</td><td class="px-3 sm:px-4 py-3 break-all"><a href="{{ route('explorer.address.show', ['address' => $to]) }}" class="font-mono explorer-primary hover:underline text-xs sm:text-sm break-all">{{ $to }}</a></td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Сумма</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text text-xs sm:text-sm">{{ $value }} {{ $symbol }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Комиссия</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text">{{ $fee }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Nonce</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text">{{ $nonce }}</td></tr>
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Тип</td><td class="px-3 sm:px-4 py-3 explorer-text">{{ $type }}</td></tr>
                @if(!empty($tx['Data']) || !empty($tx['data']) || !empty($tx['Payload']) || !empty($tx['payload']))
                    @php $payload = $tx['Data'] ?? $tx['data'] ?? $tx['Payload'] ?? $tx['payload'] ?? ''; @endphp
                    <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted align-top">Input Data</td><td class="px-3 sm:px-4 py-3 font-mono text-xs break-all explorer-text-muted-3 word-break-all">{{ is_string($payload) ? $payload : json_encode($payload) }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
