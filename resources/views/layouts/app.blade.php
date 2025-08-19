<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PMOシステム')</title>
    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ secure_asset('build/assets/app-CtlWd-5B.css') }}">
        <script src="{{ secure_asset('build/assets/app-C0G0cght.js') }}" defer></script>
    @endif
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- ナビゲーションバー -->
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('projects.index') }}" class="text-white font-bold text-xl">
                        PMOシステム
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('projects.index') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">
                        プロジェクト一覧
                    </a>
                    <a href="{{ route('projects.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-md text-sm font-medium">
                        新規プロジェクト
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- メインコンテンツ -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- フッター -->
    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 PMOシステム. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
