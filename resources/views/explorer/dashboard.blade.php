@extends('layouts.explorer')

@section('title', 'Главная')

@section('hero')
    <section class="border-b explorer-border explorer-bg-surface py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <p class="explorer-text-muted text-sm uppercase tracking-wider mb-2">Гибридный блокчейн</p>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold explorer-text-white tracking-tight">ГАНИМЕД</h1>
            <p class="text-xl explorer-text-muted-4 mt-2 font-light">в экосистеме <span class="explorer-primary font-medium">НЕКСУС</span></p>
            <p class="explorer-text-muted-3 mt-4 max-w-2xl text-sm sm:text-base">
                Блокчейн-сканер: транзакции, блоки, адреса, контракты и токены GND/GANI. Прозрачность сети в реальном времени.
            </p>
        </div>
    </section>
@endsection

@section('content')
    <div class="w-full min-w-0 overflow-x-hidden">
    @if(!$gndConfigured)
        <div class="rounded-lg explorer-alert border px-4 py-3 mb-6">
            Задайте <code class="explorer-code-bg px-1.5 py-0.5 rounded">GND_NODE_URL</code> в .env для загрузки данных с ноды.
        </div>
    @endif

    @if($gndConfigured)
        <h2 class="text-lg font-semibold explorer-text mb-2">Состояние сети <span id="dashboard-updated" class="explorer-text-muted text-sm font-normal">(обновляется каждые 30 с)</span></h2>
        <div class="mb-6">
            <div class="flex items-center justify-between gap-2 mb-1">
                <span class="explorer-text-muted text-xs">До обновления</span>
                <span id="dashboard-refresh-countdown" class="explorer-text-muted text-xs font-mono">30 с</span>
            </div>
            <div id="dashboard-refresh-bar-wrap" class="dashboard-refresh-bar-wrap">
                <div id="dashboard-refresh-bar" class="dashboard-refresh-bar"></div>
            </div>
        </div>
        <div id="dashboard-cards" class="dashboard-cards-grid">
            <div class="dashboard-metric-card rounded-xl border explorer-border explorer-bg-card p-3 sm:p-4 explorer-card-hover transition-colors">
                <div class="explorer-text-muted text-xs uppercase tracking-wide">Блоков</div>
                <div id="dashboard-blocks-count" class="text-lg sm:text-xl font-mono font-semibold explorer-primary mt-1">{{ number_format($metrics['blocks_count'] ?? $metrics['total_blocks'] ?? 0, 0, ',', ' ') }}</div>
            </div>
            <div class="dashboard-metric-card rounded-xl border explorer-border explorer-bg-card p-3 sm:p-4 explorer-card-hover transition-colors">
                <div class="explorer-text-muted text-xs uppercase tracking-wide">Транзакций</div>
                <div id="dashboard-tx-count" class="text-lg sm:text-xl font-mono font-semibold explorer-primary mt-1">{{ number_format($metrics['transactions_count'] ?? $metrics['total_transactions'] ?? 0, 0, ',', ' ') }}</div>
            </div>
            <div class="dashboard-metric-card rounded-xl border explorer-border explorer-bg-card p-3 sm:p-4 explorer-card-hover transition-colors">
                <div class="explorer-text-muted text-xs uppercase tracking-wide">TPS</div>
                <div id="dashboard-tps" class="text-lg sm:text-xl font-mono font-semibold explorer-primary mt-1">{{ $metrics['tps'] ?? $metrics['transactions_per_second'] ?? '—' }}</div>
            </div>
            <div class="dashboard-metric-card rounded-xl border explorer-border explorer-bg-card p-3 sm:p-4 explorer-card-hover transition-colors">
                <div class="explorer-text-muted text-xs uppercase tracking-wide">Средняя комиссия</div>
                @php $fee = $metrics['avg_fee'] ?? $metrics['average_gas_price'] ?? null; @endphp
                <div id="dashboard-avg-fee" class="text-base sm:text-lg font-mono explorer-text mt-1">{{ $fee !== null && $fee !== '' ? $fee . ' GND' : '—' }}</div>
            </div>
            <div class="dashboard-metric-card rounded-xl border explorer-border explorer-bg-card p-3 sm:p-4 explorer-card-hover transition-colors dashboard-metric-card-validators">
                <div class="explorer-text-muted text-xs uppercase tracking-wide">Валидаторы</div>
                <div id="dashboard-validators" class="text-lg sm:text-xl font-mono font-semibold explorer-primary mt-1">{{ $metrics['validators_count'] ?? '—' }}</div>
            </div>
        </div>
        @if(empty($blocks) && (empty($metrics['total_blocks']) && empty($metrics['blocks_count'])))
            <p class="explorer-text-muted text-sm mb-6">Не удалось загрузить данные с ноды. Проверьте <code class="explorer-code-bg px-1 rounded">GND_NODE_URL</code> в .env и доступность ноды (например, <code class="explorer-code-bg px-1 rounded">/api/v1/metrics</code>, <code class="explorer-code-bg px-1 rounded">/api/v1/block/latest</code>).</p>
        @endif
    @endif

    <div class="mb-6 sm:mb-8 w-full min-w-0">
        <h2 class="text-lg font-semibold explorer-text mb-3">Токены экосистемы</h2>
        <div class="flex flex-wrap gap-3">
            @if(config('services.gnd.gnd_contract_address'))
                <a href="{{ route('explorer.token.show', ['address' => config('services.gnd.gnd_contract_address')]) }}" class="rounded-xl border explorer-border explorer-bg-card px-4 py-3 explorer-primary explorer-card-hover transition-colors font-medium">GND Token</a>
            @endif
            @if(config('services.gnd.gani_contract_address'))
                <a href="{{ route('explorer.token.show', ['address' => config('services.gnd.gani_contract_address')]) }}" class="rounded-xl border explorer-border explorer-bg-card px-4 py-3 explorer-accent-teal explorer-card-hover transition-colors font-medium">GANI Token</a>
            @endif
            <a href="{{ route('explorer.stats') }}" class="rounded-xl border explorer-border explorer-bg-card px-4 py-3 explorer-text-muted-4 explorer-card-hover transition-colors">Статистика и графики</a>
        </div>
    </div>

    <div class="dashboard-bottom-grid grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 w-full min-w-0">
        <section class="dashboard-section min-w-0 w-full">
            <div class="flex items-center justify-between mb-3 gap-2 min-w-0">
                <h2 class="text-base sm:text-lg font-semibold explorer-text truncate">Последние блоки</h2>
                <a href="{{ route('explorer.stats') }}" class="text-sm explorer-primary explorer-link shrink-0 py-2 touch-manual">Все</a>
            </div>
            <div id="dashboard-blocks-list" class="rounded-xl border explorer-border explorer-bg-card overflow-hidden w-full">
                @forelse($blocks as $block)
                    @php
                        $id = $block['Index'] ?? $block['index'] ?? $block['ID'] ?? $block['id'] ?? $block['Height'] ?? $block['height'] ?? $block['number'] ?? '—';
                        $txCount = is_array($block['Transactions'] ?? $block['transactions'] ?? null) ? count($block['Transactions'] ?? $block['transactions']) : ($block['TxCount'] ?? $block['tx_count'] ?? $block['transactions_count'] ?? 0);
                        $ts = $block['Timestamp'] ?? $block['timestamp'] ?? $block['created_at'] ?? '';
                    @endphp
                    <a href="{{ route('explorer.block.show', ['number' => $id]) }}" class="dashboard-list-row flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-3 sm:px-4 py-3 border-b explorer-divider last:border-0 hover:bg-[var(--color-surface-dark)] transition-colors min-h-[44px] sm:min-h-0 touch-manual min-w-0">
                        <span class="font-mono explorer-primary font-medium shrink-0">#{{ $id }}</span>
                        <span class="explorer-text-muted text-xs sm:text-sm truncate min-w-0">{{ $txCount }} tx · <span class="block-time-ago" data-block-ts="{{ $ts ? \Carbon\Carbon::parse($ts)->toIso8601String() : '' }}">{{ $ts ? \Carbon\Carbon::parse($ts)->diffForHumans() : '—' }}</span></span>
                    </a>
                @empty
                    <div class="px-4 py-8 explorer-text-muted text-center">Нет данных о блоках</div>
                @endforelse
            </div>
        </section>

        <section class="dashboard-section min-w-0 w-full">
            <div class="flex items-center justify-between mb-3 gap-2 min-w-0">
                <h2 class="text-base sm:text-lg font-semibold explorer-text truncate">Последние транзакции</h2>
                <a href="{{ route('explorer.transactions') }}" class="text-sm explorer-primary explorer-link shrink-0 py-2 touch-manual">Все</a>
            </div>
            <div id="dashboard-tx-list" class="rounded-xl border explorer-border explorer-bg-card overflow-hidden w-full">
                @forelse($transactions as $tx)
                    @php
                        $hash = $tx['hash'] ?? '';
                        $shortHash = strlen($hash) > 16 ? substr($hash, 0, 10) . '…' . substr($hash, -6) : $hash;
                        $status = $tx['status'] ?? 'pending';
                        $value = $tx['value'] ?? '0';
                        $type = $tx['type'] ?? $tx['Type'] ?? '—';
                    @endphp
                    <a href="{{ route('explorer.transaction.show', ['hash' => $hash]) }}" class="dashboard-list-row flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-3 sm:px-4 py-3 border-b explorer-divider last:border-0 hover:bg-[var(--color-surface-dark)] transition-colors min-h-[44px] sm:min-h-0 touch-manual min-w-0">
                        <span class="font-mono text-sm explorer-text-muted-4 truncate min-w-0" title="{{ $hash }}">{{ $shortHash }}</span>
                        <span class="explorer-text-muted text-xs sm:text-sm flex items-center gap-2 shrink-0"><span>{{ $type }}</span><span class="font-mono">{{ $value }} GND</span><span class="{{ $status === 'confirmed' ? 'text-emerald-400' : ($status === 'failed' ? 'text-red-400' : 'explorer-primary') }}">{{ $status === 'confirmed' ? '✓' : ($status === 'failed' ? '✗' : '…') }}</span></span>
                    </a>
                @empty
                    <div class="px-4 py-8 explorer-text-muted text-center">Нет данных о транзакциях</div>
                @endforelse
            </div>
        </section>
    </div>
    </div>

    @if($gndConfigured)
        @push('scripts')
        <script>
            (function() {
                const DATA_URL = '{{ route("explorer.dashboard.data") }}';
                const BLOCK_URL = '{{ url("/block") }}';
                const TX_URL = '{{ url("/tx") }}';
                const INTERVAL_MS = 30000;

                function formatNum(n) {
                    if (n === null || n === undefined) return '—';
                    return Number(n).toLocaleString('ru-RU', { maximumFractionDigits: 0 });
                }

                function parseTimestamp(ts) {
                    if (ts == null || ts === '') return null;
                    if (typeof ts === 'number') {
                        return ts < 1e12 ? ts * 1000 : ts;
                    }
                    const date = new Date(ts);
                    return isNaN(date.getTime()) ? null : date.getTime();
                }

                function timeAgo(ts) {
                    const ms = parseTimestamp(ts);
                    if (ms == null) return '—';
                    const sec = Math.floor((Date.now() - ms) / 1000);
                    if (sec < 0) return 'только что';
                    if (sec < 60) return sec + ' сек. назад';
                    if (sec < 3600) return Math.floor(sec / 60) + ' мин. назад';
                    if (sec < 86400) return Math.floor(sec / 3600) + ' ч. назад';
                    return Math.floor(sec / 86400) + ' дн. назад';
                }

                function renderBlocks(blocks) {
                    if (!blocks || !blocks.length) return '<div class="px-4 py-8 explorer-text-muted text-center">Нет данных о блоках</div>';
                    return blocks.map(function(b) {
                        const id = b.Index ?? b.index ?? b.ID ?? b.id ?? b.Height ?? b.height ?? b.number ?? '—';
                        const txCount = (b.Transactions || b.transactions) ? (b.Transactions || b.transactions).length : (b.TxCount ?? b.tx_count ?? b.transactions_count ?? 0);
                        const ts = b.Timestamp ?? b.timestamp ?? b.created_at ?? '';
                        const tsAttr = ts ? encodeURIComponent(typeof ts === 'string' ? ts : (ts && ts.toString && ts.toString()) || '') : '';
                        return '<a href="' + BLOCK_URL + '/' + id + '" class="dashboard-list-row flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-3 sm:px-4 py-3 border-b explorer-divider last:border-0 explorer-row-hover transition-colors min-h-[44px] sm:min-h-0 touch-manual min-w-0">' +
                            '<span class="font-mono explorer-primary font-medium">#' + id + '</span>' +
                            '<span class="explorer-text-muted text-xs sm:text-sm truncate min-w-0">' + txCount + ' tx · <span class="block-time-ago" data-block-ts="' + tsAttr + '">' + timeAgo(ts) + '</span></span></a>';
                    }).join('');
                }

                function updateBlockTimeAgoAll() {
                    document.querySelectorAll('.block-time-ago').forEach(function(el) {
                        var ts = el.getAttribute('data-block-ts');
                        if (ts) try { ts = decodeURIComponent(ts); } catch (e) {}
                        el.textContent = timeAgo(ts || '');
                    });
                }

                function renderTransactions(txs) {
                    if (!txs || !txs.length) return '<div class="px-4 py-8 explorer-text-muted text-center">Нет данных о транзакциях</div>';
                    return txs.map(function(tx) {
                        const hash = tx.hash ?? '';
                        const short = hash.length > 16 ? hash.slice(0, 10) + '…' + hash.slice(-6) : hash;
                        const status = tx.status ?? 'pending';
                        const value = tx.value ?? '0';
                        const type = tx.type ?? tx.Type ?? '—';
                        let statusCls = 'explorer-primary';
                        let statusChar = '…';
                        if (status === 'confirmed') { statusCls = 'text-emerald-400'; statusChar = '✓'; }
                        else if (status === 'failed') { statusCls = 'text-red-400'; statusChar = '✗'; }
                        return '<a href="' + TX_URL + '/' + encodeURIComponent(hash) + '" class="dashboard-list-row flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-3 sm:px-4 py-3 border-b explorer-divider last:border-0 explorer-row-hover transition-colors min-h-[44px] sm:min-h-0 touch-manual min-w-0">' +
                            '<span class="font-mono text-sm explorer-text-muted-4 truncate min-w-0" title="' + hash + '">' + short + '</span>' +
                            '<span class="explorer-text-muted text-xs sm:text-sm flex items-center gap-2 shrink-0"><span>' + type + '</span><span class="font-mono">' + value + ' GND</span><span class="' + statusCls + '">' + statusChar + '</span></span></a>';
                    }).join('');
                }

                var refreshBarEl = document.getElementById('dashboard-refresh-bar');
                var refreshCountdownEl = document.getElementById('dashboard-refresh-countdown');
                var refreshElapsed = 0;
                var refreshTick = 200;

                function setRefreshBarPct(pct) {
                    if (refreshBarEl) refreshBarEl.style.setProperty('--refresh-bar-pct', (pct < 0 ? 0 : pct > 100 ? 100 : pct) + '%');
                }

                function updateRefreshBar() {
                    if (!refreshBarEl) return;
                    refreshElapsed += refreshTick;
                    var remaining = Math.max(0, INTERVAL_MS - refreshElapsed);
                    var pct = Math.max(0, 100 - (refreshElapsed / INTERVAL_MS) * 100);
                    setRefreshBarPct(pct);
                    if (refreshCountdownEl) refreshCountdownEl.textContent = Math.ceil(remaining / 1000) + ' с';
                }

                function resetRefreshBar() {
                    refreshElapsed = 0;
                    setRefreshBarPct(100);
                    if (refreshCountdownEl) refreshCountdownEl.textContent = '30 с';
                }

                function updateDashboard(data) {
                    const m = data.metrics || {};
                    const blocksEl = document.getElementById('dashboard-blocks-count');
                    const txCountEl = document.getElementById('dashboard-tx-count');
                    const tpsEl = document.getElementById('dashboard-tps');
                    const feeEl = document.getElementById('dashboard-avg-fee');
                    const validatorsEl = document.getElementById('dashboard-validators');
                    const blocksListEl = document.getElementById('dashboard-blocks-list');
                    const txListEl = document.getElementById('dashboard-tx-list');

                    if (blocksEl) blocksEl.textContent = formatNum(m.blocks_count ?? m.total_blocks ?? 0);
                    if (txCountEl) txCountEl.textContent = formatNum(m.transactions_count ?? m.total_transactions ?? 0);
                    if (tpsEl) tpsEl.textContent = m.tps ?? m.transactions_per_second ?? '—';
                    if (feeEl) {
                        const fee = m.avg_fee ?? m.average_gas_price;
                        feeEl.textContent = (fee !== null && fee !== undefined && fee !== '') ? fee + ' GND' : '—';
                    }
                    if (validatorsEl) validatorsEl.textContent = m.validators_count ?? '—';
                    if (blocksListEl) blocksListEl.innerHTML = renderBlocks(data.blocks);
                    if (txListEl) txListEl.innerHTML = renderTransactions(data.transactions);
                    resetRefreshBar();
                    updateBlockTimeAgoAll();
                }

                function fetchData() {
                    fetch(DATA_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function(r) { return r.json(); })
                        .then(updateDashboard)
                        .catch(function() {});
                }

                var timer = setInterval(fetchData, INTERVAL_MS);
                var barTimer = setInterval(updateRefreshBar, refreshTick);
                updateBlockTimeAgoAll();
                var timeAgoTimer = setInterval(updateBlockTimeAgoAll, 10000);
            })();
        </script>
        @endpush
    @endif
@endsection
