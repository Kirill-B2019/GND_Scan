@extends('layouts.explorer')

@section('title', 'Транзакции')

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Транзакции</h1>
    <p class="explorer-text-muted text-xs sm:text-sm mb-6 sm:mb-8">Список транзакций сети ГАНИМЕД.</p>

    @if(!$gndConfigured)
        <div class="rounded-xl border explorer-border explorer-bg-card p-8 text-center explorer-text-muted">
            Задайте <code class="explorer-bg-surface px-1.5 py-0.5 rounded explorer-text-muted-4">GND_NODE_URL</code> в .env для загрузки данных.
        </div>
    @else
        <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[720px]">
                    <thead class="explorer-bg-surface border-b explorer-border">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-left explorer-text-muted font-medium">Хэш</th>
                            <th class="px-3 sm:px-4 py-3 text-left explorer-text-muted font-medium">Тип</th>
                            <th class="px-3 sm:px-4 py-3 text-left explorer-text-muted font-medium">Блок</th>
                            <th class="px-3 sm:px-4 py-3 text-left explorer-text-muted font-medium hidden lg:table-cell">От</th>
                            <th class="px-3 sm:px-4 py-3 text-left explorer-text-muted font-medium hidden lg:table-cell">Кому</th>
                            <th class="px-3 sm:px-4 py-3 text-right explorer-text-muted font-medium">Сумма</th>
                            <th class="px-3 sm:px-4 py-3 text-center explorer-text-muted font-medium">Статус</th>
                            <th class="px-3 sm:px-4 py-3 text-right explorer-text-muted font-medium">Время</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y explorer-divider">
                        @forelse($transactions as $tx)
                            @php
                                $hash = $tx['Hash'] ?? $tx['hash'] ?? '—';
                                $typeRaw = $tx['Type'] ?? $tx['type'] ?? '';
                                $typeLabel = $typeRaw !== '' ? $typeRaw : '—';
                                $typeSlug = strtolower((string) $typeRaw);
                                $badgeClass = 'tx-badge--muted';
                                if (in_array($typeSlug, ['transfer', 'send', 'payment'], true)) {
                                    $badgeClass = 'tx-badge--primary';
                                } elseif (in_array($typeSlug, ['contract', 'call', 'contractcall', 'invoke'], true)) {
                                    $badgeClass = 'tx-badge--teal';
                                } elseif (in_array($typeSlug, ['deploy', 'create'], true)) {
                                    $badgeClass = 'tx-badge--purple';
                                } elseif (in_array($typeSlug, ['stake', 'delegate', 'validator'], true)) {
                                    $badgeClass = 'tx-badge--purple-2';
                                } elseif (in_array($typeSlug, ['mint', 'burn', 'approve'], true)) {
                                    $badgeClass = 'tx-badge--purple-3';
                                }
                                // block_number — номер в цепи (GET /block/:number). block_id — внутренний id БД, НЕ использовать для отображения.
                                $blockId = $tx['BlockNumber'] ?? $tx['block_number'] ?? null;
                                $from = $tx['Sender'] ?? $tx['sender'] ?? $tx['From'] ?? $tx['from'] ?? '—';
                                $to = $tx['Recipient'] ?? $tx['recipient'] ?? $tx['To'] ?? $tx['to'] ?? '—';
                                $value = $tx['Value'] ?? $tx['value'] ?? '0';
                                $symbol = $tx['Symbol'] ?? $tx['symbol'] ?? 'GND';
                                $status = $tx['Status'] ?? $tx['status'] ?? 'pending';
                                $ts = $tx['Timestamp'] ?? $tx['timestamp'] ?? $tx['CreatedAt'] ?? $tx['created_at'] ?? null;
                            @endphp
                            <tr class="explorer-row-hover">
                                <td class="px-3 sm:px-4 py-3">
                                    <a href="{{ route('explorer.transaction.show', ['hash' => $hash]) }}" class="font-mono text-xs sm:text-sm explorer-primary hover:underline break-all">{{ Str::limit($hash, 18) }}</a>
                                </td>
                                <td class="px-3 sm:px-4 py-3">
                                    <span class="tx-badge {{ $badgeClass }}" title="{{ $typeLabel }}">{{ $typeLabel }}</span>
                                </td>
                                <td class="px-3 sm:px-4 py-3">
                                    @if($blockId !== null && $blockId !== '')
                                        <a href="{{ route('explorer.block.show', ['number' => $blockId]) }}" class="explorer-primary hover:underline font-mono">{{ $blockId }}</a>
                                    @else
                                        <span class="explorer-text-muted">—</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-4 py-3 hidden lg:table-cell">
                                    <a href="{{ route('explorer.address.show', ['address' => $from]) }}" class="font-mono text-xs explorer-primary hover:underline break-all" title="{{ $from }}">{{ Str::limit($from, 12) }}</a>
                                </td>
                                <td class="px-3 sm:px-4 py-3 hidden lg:table-cell">
                                    <a href="{{ route('explorer.address.show', ['address' => $to]) }}" class="font-mono text-xs explorer-primary hover:underline break-all" title="{{ $to }}">{{ Str::limit($to, 12) }}</a>
                                </td>
                                <td class="px-3 sm:px-4 py-3 text-right font-mono explorer-text whitespace-nowrap">{{ $value }} {{ $symbol }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center">
                                    @if($status === 'confirmed' || $status === 'success')
                                        <span class="text-emerald-400 font-medium">✓</span>
                                    @elseif($status === 'failed' || $status === 'error')
                                        <span class="text-red-400 font-medium">✗</span>
                                    @else
                                        <span class="explorer-text-muted">⏳</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-4 py-3 text-right explorer-text-muted text-xs whitespace-nowrap">
                                    @if($ts)
                                        {{ \Carbon\Carbon::parse($ts)->setTimezone(config('app.timezone'))->format('d.m.Y H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 sm:px-4 py-8 text-center explorer-text-muted">Транзакций пока нет</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($hasPrev || $hasNext)
            <nav class="flex flex-wrap items-center justify-between gap-3 sm:justify-center sm:gap-4" aria-label="Пагинация">
                @if($hasPrev)
                    <a href="{{ route('explorer.transactions', ['page' => $page - 1]) }}" class="rounded-lg border explorer-border explorer-bg-card px-4 py-2 text-sm explorer-text explorer-link transition-colors hover:bg-[var(--color-surface-dark)]">← Назад</a>
                @else
                    <span class="rounded-lg border explorer-border border-transparent px-4 py-2 text-sm explorer-text-muted opacity-50">← Назад</span>
                @endif
                <span class="explorer-text-muted text-sm">Страница {{ $page }}</span>
                @if($hasNext)
                    <a href="{{ route('explorer.transactions', ['page' => $page + 1]) }}" class="rounded-lg border explorer-border explorer-bg-card px-4 py-2 text-sm explorer-text explorer-link transition-colors hover:bg-[var(--color-surface-dark)]">Вперёд →</a>
                @else
                    <span class="rounded-lg border explorer-border border-transparent px-4 py-2 text-sm explorer-text-muted opacity-50">Вперёд →</span>
                @endif
            </nav>
        @endif
    @endif
@endsection
