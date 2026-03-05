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
    <x-vite-assets />
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
                    <div class="explorer-search-wrap flex-1 min-w-0 relative flex flex-col sm:flex-row gap-2 sm:gap-0">
                        <form action="{{ route('explorer.search') }}" method="get" class="flex flex-col sm:flex-row flex-1 min-w-0 gap-2 sm:gap-0 sm:ml-0" id="explorer-search-form">
                            <label for="search" class="sr-only">Поиск</label>
                            <div class="relative flex-1 min-w-0">
                                <input type="search" name="q" id="search" placeholder="Адрес, хэш, блок…" autocomplete="off"
                                       class="w-full min-w-0 rounded-lg border explorer-input px-3 sm:px-4 py-2.5 sm:py-2 text-sm min-h-[44px] sm:min-h-0"
                                       value="{{ request('q', '') }}" aria-expanded="false" aria-controls="explorer-search-dropdown" aria-autocomplete="list">
                                <div id="explorer-search-dropdown" class="explorer-search-dropdown absolute left-0 right-0 top-full z-50 mt-1 rounded-lg border explorer-border explorer-bg-card shadow-lg py-1 max-h-[280px] overflow-y-auto display-none" role="listbox"></div>
                            </div>
                            <button type="submit" class="rounded-lg explorer-btn-primary px-4 py-2.5 sm:py-2 text-sm font-medium shrink-0 transition-colors min-h-[44px] sm:min-h-0 sm:ml-2">Найти</button>
                        </form>
                    </div>
                </div>
                <nav class="flex flex-wrap items-center gap-1 sm:gap-4 lg:gap-6 text-sm -mx-1">
                    <a href="{{ route('explorer.dashboard') }}" class="explorer-text-muted explorer-link transition-colors px-3 py-2.5 rounded-lg min-h-[44px] flex items-center touch-manual">Главная</a>
                    <a href="{{ route('explorer.transactions') }}" class="explorer-text-muted explorer-link transition-colors px-3 py-2.5 rounded-lg min-h-[44px] flex items-center touch-manual">Транзакции</a>
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
    <script>
    (function() {
        var form = document.getElementById('explorer-search-form');
        var input = document.getElementById('search');
        var dropdown = document.getElementById('explorer-search-dropdown');
        if (!form || !input || !dropdown) return;
        var suggestUrl = '{{ route("explorer.search.suggest", [], false) }}';
        var debounceTimer;
        function closeDropdown() {
            dropdown.classList.add('display-none');
            dropdown.innerHTML = '';
            input.setAttribute('aria-expanded', 'false');
        }
        function showDropdown(items) {
            dropdown.innerHTML = '';
            if (!items || items.length === 0) { closeDropdown(); return; }
            items.forEach(function(s) {
                var a = document.createElement('a');
                a.href = s.url;
                a.className = 'explorer-search-suggestion block px-3 py-2 text-sm explorer-text explorer-row-hover truncate';
                a.textContent = s.label;
                a.setAttribute('role', 'option');
                dropdown.appendChild(a);
            });
            dropdown.classList.remove('display-none');
            input.setAttribute('aria-expanded', 'true');
        }
        function fetchSuggest() {
            var q = (input.value || '').trim().replace(/\s+/g, '');
            if (q.length < 1) { closeDropdown(); return; }
            fetch(suggestUrl + '?q=' + encodeURIComponent(q))
                .then(function(r) { return r.json(); })
                .then(function(data) { showDropdown(data.suggestions || []); })
                .catch(function() { closeDropdown(); });
        }
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchSuggest, 220);
        });
        input.addEventListener('focus', function() {
            if (input.value.trim().length >= 1) fetchSuggest();
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeDropdown(); input.blur(); }
        });
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && e.target !== input) closeDropdown();
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
