{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>mogitate</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Google Fonts 代用 -->
    <link href="https://fonts.googleapis.com/css2?family=Georgia&display=swap" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
     @yield('css')
</head>
<body>

    <header class="header">
        <h1 class="header-title">mogitate</h1>
    </header>


    <main>
        @yield('content')
    </main>

        @yield('js')
</body>
</html>
