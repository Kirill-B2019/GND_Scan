@extends('layouts.explorer')

@section('title', 'Блок не найден')

@section('content')
    <div class="mb-6">
        <a href="{{ route('explorer.dashboard') }}" class="text-sm explorer-text-muted explorer-link transition-colors">← Главная</a>
    </div>
    <div class="rounded-xl border explorer-border explorer-bg-card p-6 sm:p-8 text-center">
        <h1 class="text-xl sm:text-2xl font-bold explorer-text-white mb-2">Блок #{{ $number }} не найден</h1>
        <p class="explorer-text-muted text-sm mb-4 sm:mb-6">{{ $error }}</p>
        <p class="explorer-text-muted-3 text-xs sm:text-sm mb-4 sm:mb-6">Проверьте номер блока или перейдите к <a href="{{ route('explorer.dashboard') }}" class="explorer-primary hover:underline">главной</a> и выберите блок из списка.</p>
        <a href="{{ route('explorer.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-lg explorer-code-bg explorer-primary explorer-card-hover transition-colors min-h-[44px] items-center justify-center touch-manual">На главную</a>
    </div>
@endsection
