<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Ганимед Explorer — блокчейн-сканер NEKSUS. Транзакции, блоки, адреса, контракты, токены GND/GANI.">
    <title>@yield('title', 'Ганимед Explorer') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased explorer-bg explorer-text min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 border-b explorer-border explorer-bg-header backdrop-blur shrink-0">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 py-3 sm:py-2">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 min-w-0">
                    <a href="{{ route('explorer.dashboard') }}" class="flex items-center gap-2 shrink-0 py-1 sm:py-0 min-h-[44px] sm:min-h-0 items-center">
                        <span class="text-lg sm:text-xl font-bold explorer-primary">ГАНИМЕД</span>
                        <span class="explorer-text-muted text-sm hidden sm:inline font-normal">Explorer</span>
                    </a>
                    <form action="{{ route('explorer.search') }}" method="get" class="flex flex-col sm:flex-row flex-1 min-w-0 gap-2 sm:gap-0 sm:ml-0">
                        <label for="search" class="sr-only">Поиск</label>
                        <input type="search" name="q" id="search" placeholder="Адрес, хэш, блок…"
                               class="w-full min-w-0 rounded-lg border explorer-input px-3 sm:px-4 py-2.5 sm:py-2 text-sm min-h-[44px] sm:min-h-0"
                               value="{{ request('q', '') }}">
                        <button type="submit" class="rounded-lg explorer-btn-primary px-4 py-2.5 sm:py-2 text-sm font-medium shrink-0 transition-colors min-h-[44px] sm:min-h-0 sm:ml-2">Найти</button>
                    </form>
                </div>
                <nav class="flex flex-wrap items-center gap-1 sm:gap-4 lg:gap-6 text-sm -mx-1">
                    <a href="{{ route('explorer.dashboard') }}" class="explorer-text-muted explorer-link transition-colors px-3 py-2.5 rounded-lg min-h-[44px] flex items-center touch-manual">Главная</a>
                    <a href="{{ route('explorer.stats') }}" class="explorer-text-muted explorer-link transition-colors px-3 py-2.5 rounded-lg min-h-[44px] flex items-center touch-manual">Статистика</a>
                    <a href="{{ route('explorer.validators') }}" class="explorer-text-muted explorer-link transition-colors px-3 py-2.5 rounded-lg min-h-[44px] flex items-center touch-manual">Валидаторы</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="explorer-text-muted explorer-link transition-colors px-3 py-2.5 rounded-lg min-h-[44px] flex items-center touch-manual">Кабинет</a>
                    @else
                        <span class="explorer-text-muted opacity-50 cursor-not-allowed px-3 py-2.5 rounded-lg min-h-[44px] flex items-center" aria-disabled="true" title="Временно отключено">Вход</span>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    @hasSection('hero')
        @yield('hero')
    @endif

    <main class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6 flex-1 w-full min-w-0">
        @if(session('message'))
            <div class="mb-4 rounded-lg explorer-alert border px-4 py-2 text-sm">
                {{ session('message') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t explorer-border mt-8 sm:mt-12 py-6 sm:py-8 explorer-bg-surface">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 explorer-text-muted text-xs sm:text-sm text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                    <span class="font-semibold explorer-text-muted-4">ГАНИМЕД Explorer</span>
                    <span class="hidden sm:inline">·</span>
                    <span>Блокчейн-сканер в экосистеме НЕКСУС</span>
                </div>
                <div>© {{ date('Y') }} НЕКСУС-ИНВЕСТ ФОНД</div>
            </div>
        </div>
    </footer>
    <div class="footer-kb">| KB @CerbeRus - Nexus Invest Team</div>
    @stack('scripts')
</body>
</html>
