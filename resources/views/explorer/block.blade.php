@extends('layouts.explorer')

@section('title', 'Блок ' . ($block['Index'] ?? $block['index'] ?? $block['Height'] ?? $block['height'] ?? $block['ID'] ?? $block['id'] ?? $block['number'] ?? ''))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>
    @php
        $blockId = $block['Index'] ?? $block['index'] ?? $block['Height'] ?? $block['height'] ?? $block['ID'] ?? $block['id'] ?? $block['number'] ?? '—';
    @endphp
    <h1 class="text-2xl font-bold explorer-text-white mb-6">Блок #{{ $blockId }}</h1>

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden mb-8">
        <table class="w-full text-sm">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-4 py-3 explorer-text-muted w-48">Высота</td><td class="px-4 py-3 font-mono explorer-text">{{ $blockId }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Время</td><td class="px-4 py-3 explorer-text">{{ isset($block['Timestamp']) || isset($block['timestamp']) ? \Carbon\Carbon::parse($block['Timestamp'] ?? $block['timestamp'])->setTimezone(config('app.timezone'))->format('d.m.Y H:i:s') : '—' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Хэш</td><td class="px-4 py-3 font-mono explorer-text-muted-4 break-all">{{ $block['Hash'] ?? $block['hash'] ?? '—' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Родитель</td><td class="px-4 py-3">@if(!empty($block['PrevHash'] ?? $block['parent_hash']))<a href="{{ route('explorer.block.show', ['number' => (int)$blockId - 1]) }}" class="explorer-primary hover:underline font-mono">{{ Str::limit($block['PrevHash'] ?? $block['parent_hash'], 20) }}</a>@else — @endif</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Валидатор</td><td class="px-4 py-3 font-mono explorer-text-muted-4">{{ $block['Miner'] ?? $block['miner'] ?? $block['validator'] ?? '—' }}</td></tr>
                <tr><td class="px-4 py-3 explorer-text-muted">Транзакций</td><td class="px-4 py-3 explorer-text">{{ is_array($block['Transactions'] ?? $block['transactions'] ?? null) ? count($block['Transactions'] ?? $block['transactions']) : ($block['TxCount'] ?? $block['tx_count'] ?? 0) }}</td></tr>
            </tbody>
        </table>
    </div>

    <h2 class="text-lg font-semibold explorer-text mb-3">Транзакции</h2>
    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden">
        @php $txs = $block['Transactions'] ?? $block['transactions'] ?? []; @endphp
        @forelse($txs as $tx)
            @php $hash = is_array($tx) ? ($tx['Hash'] ?? $tx['hash'] ?? '') : (string)$tx; @endphp
            <a href="{{ route('explorer.transaction.show', ['hash' => $hash]) }}" class="flex items-center justify-between px-4 py-3 border-b explorer-divider last:border-0 explorer-row-hover transition-colors">
                <span class="font-mono text-sm explorer-primary truncate">{{ $hash }}</span>
                @if(is_array($tx))
                    <span class="explorer-text-muted text-sm">{{ $tx['status'] ?? '—' }}</span>
                @endif
            </a>
        @empty
            <div class="px-4 py-8 explorer-text-muted text-center">В блоке нет транзакций</div>
        @endforelse
    </div>
@endsection
