@php
    $entries = ['resources/css/app.css', 'resources/js/app.js'];
    $manifestPath = public_path('build/manifest.json');
    $hotPath = public_path('hot');
    $isProduction = app()->environment('production');
    $hasHot = file_exists($hotPath);
    $hasManifest = file_exists($manifestPath);
    // В production всегда используем только сборку из манифеста (никогда localhost:5173)
    $useBuild = $hasManifest && ($isProduction || !$hasHot);
@endphp
@if($useBuild)
    @php
        $manifest = json_decode(file_get_contents($manifestPath), true);
    @endphp
    @foreach($entries as $entry)
        @if(isset($manifest[$entry]['file']))
            @if(str_ends_with($manifest[$entry]['file'], '.css'))
    <link rel="stylesheet" href="{{ asset('build/' . $manifest[$entry]['file']) }}" />
            @endif
        @endif
    @endforeach
    @foreach($entries as $entry)
        @if(isset($manifest[$entry]['file']))
            @if(str_ends_with($manifest[$entry]['file'], '.js'))
    <script type="module" src="{{ asset('build/' . $manifest[$entry]['file']) }}"></script>
            @endif
        @endif
    @endforeach
@elseif(!$isProduction && $hasHot)
    @vite($entries)
@elseif($isProduction)
    {{-- Production без сборки: загрузите public/build и выполните npm run build при деплое --}}
    <!-- npm run build + загрузите public/build -->
@else
    @vite($entries)
@endif
