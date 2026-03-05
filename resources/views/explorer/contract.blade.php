@extends('layouts.explorer')

@section('title', 'Контракт ' . Str::limit($address, 16))

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-2xl font-bold explorer-text-white mb-2">Контракт</h1>
    <p class="font-mono explorer-text-muted-4 text-sm break-all mb-6">{{ $address }}</p>

    <div class="rounded-xl border explorer-border explorer-bg-card overflow-hidden mb-8">
        <table class="w-full text-sm">
            <tbody class="divide-y explorer-divider">
                <tr><td class="px-4 py-3 explorer-text-muted w-48">Адрес</td><td class="px-4 py-3 font-mono explorer-text-muted-4 break-all">{{ $address }}</td></tr>
                @if($contract)
                    <tr><td class="px-4 py-3 explorer-text-muted">Имя</td><td class="px-4 py-3 explorer-text">{{ $contract['name'] ?? '—' }}</td></tr>
                    <tr><td class="px-4 py-3 explorer-text-muted">Стандарт</td><td class="px-4 py-3 explorer-text">{{ $contract['standard'] ?? '—' }}</td></tr>
                    <tr><td class="px-4 py-3 explorer-text-muted">Верифицирован</td><td class="px-4 py-3 explorer-text">{{ !empty($contract['is_verified']) ? 'Да' : 'Нет' }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($contractView && !empty($contractView['view_functions']))
        <h2 class="text-lg font-semibold explorer-text mb-3">Read Contract (view)</h2>
        <p class="explorer-text-muted text-sm mb-4">Доступные view-функции контракта. Вызов через API ноды.</p>
        <ul class="list-disc list-inside explorer-text-muted space-y-1">
            @foreach($contractView['view_functions'] ?? [] as $fn)
                <li class="font-mono text-sm">{{ is_string($fn) ? $fn : ($fn['name'] ?? json_encode($fn)) }}</li>
            @endforeach
        </ul>
    @endif

    <div class="mt-8">
        <a href="{{ route('explorer.token.show', ['address' => $address]) }}" class="explorer-primary hover:underline">Открыть как токен →</a>
    </div>
@endsection
