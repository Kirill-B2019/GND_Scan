@extends('layouts.explorer')

@section('title', 'Валидаторы')

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>

    <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-4 sm:mb-6">Валидаторы PoSA</h1>

    <div class="rounded-xl border explorer-border explorer-bg-card p-6 sm:p-8 text-center explorer-text-muted text-sm">
        Раздел в разработке. Данные о валидаторах будут доступны после реализации API <code class="explorer-bg-surface px-1.5 py-0.5 rounded explorer-text-muted-4">/api/v1/validators</code> на ноде.
    </div>
@endsection
