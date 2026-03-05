@extends('layouts.explorer')

@section('title', 'Контракт ' . Str::limit($address, 16))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Контракт</h1>
    <p class="font-mono explorer-text-muted-4 text-xs sm:text-sm break-all mb-4 sm:mb-6">{{ $address }}</p>

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-x-auto mb-6 sm:mb-8">
        <table class="w-full text-sm min-w-[280px]">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted w-36 sm:w-48">Адрес</td><td class="px-3 sm:px-4 py-3 font-mono explorer-text-muted-4 break-all text-xs sm:text-sm">{{ $address }}</td></tr>
                @if($contract)
                    <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Имя</td><td class="px-3 sm:px-4 py-3 explorer-text text-xs sm:text-sm">{{ $contract['name'] ?? '—' }}</td></tr>
                    <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Стандарт</td><td class="px-3 sm:px-4 py-3 explorer-text">{{ $contract['standard'] ?? '—' }}</td></tr>
                    <tr><td class="px-3 sm:px-4 py-3 explorer-text-muted">Верифицирован</td><td class="px-3 sm:px-4 py-3 explorer-text">{{ !empty($contract['is_verified']) ? 'Да' : 'Нет' }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($contractView && !empty($contractView['view_functions']))
        <h2 class="text-base sm:text-lg font-semibold explorer-text mb-3">Read Contract (view)</h2>
        <p class="explorer-text-muted text-xs sm:text-sm mb-4">Доступные view-функции контракта. Вызов через API ноды.</p>
        <ul class="list-disc list-inside explorer-text-muted space-y-1 text-xs sm:text-sm">
            @foreach($contractView['view_functions'] ?? [] as $fn)
                <li class="font-mono break-all">{{ is_string($fn) ? $fn : ($fn['name'] ?? json_encode($fn)) }}</li>
            @endforeach
        </ul>
    @endif

    <div class="mt-6 sm:mt-8">
        <a href="{{ route('explorer.token.show', ['address' => $address]) }}" class="explorer-primary hover:underline py-2 inline-block touch-manual min-h-[44px] sm:min-h-0 sm:py-0">Открыть как токен →</a>
    </div>
@endsection
