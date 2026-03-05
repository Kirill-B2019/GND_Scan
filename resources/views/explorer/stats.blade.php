@extends('layouts.explorer')

@section('title', 'Статистика сети')

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-2xl font-bold explorer-text-white mb-2">Статистика и аналитика</h1>
    <p class="explorer-text-muted text-sm mb-8">Метрики сети ГАНИМЕД по данным ноды.</p>

    @if(!$gndConfigured)
        <div class="rounded-xl border explorer-border explorer-bg-card p-8 text-center explorer-text-muted">
            Задайте <code class="explorer-bg-surface px-1.5 py-0.5 rounded explorer-text-muted-4">GND_NODE_URL</code> в .env для загрузки данных.
        </div>
    @elseif(!empty($sections))
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @foreach($sections as $section)
                <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden">
                    <h2 class="px-4 py-3 border-b explorer-border font-semibold explorer-text explorer-bg-surface">
                        {{ $section['title'] }}
                    </h2>
                    <dl class="p-4 space-y-3">
                        @foreach($section['rows'] as $row)
                            <div class="flex justify-between items-baseline gap-4">
                                <dt class="explorer-text-muted text-sm shrink-0">{{ $row[0] }}</dt>
                                <dd class="font-mono explorer-primary text-sm text-right break-all">{{ $row[1] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endforeach
        </div>
        <p class="explorer-text-muted text-sm">Графики TPS, активных адресов и истории комиссий будут добавлены при наличии исторических данных в API ноды.</p>
    @else
        <div class="rounded-xl border explorer-border explorer-bg-card p-8 text-center explorer-text-muted">
            Нет данных. Убедитесь, что нода доступна и отдаёт <code class="explorer-bg-surface px-1.5 py-0.5 rounded explorer-text-muted-4">/api/v1/metrics</code>.
        </div>
    @endif
@endsection
