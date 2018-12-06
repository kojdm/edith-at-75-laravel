<!DOCTYPE html>

<!-- What are YOU doing here??? -->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A website for Edith's 75th birthday. Upload images and leave messages for her to see.">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta id="token" name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>{{config('app.name', 'Edith @ 75')}}</title>

    {{-- MaterializeCSS CSS --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    {{-- Dropzone CSS --}}
    <link rel="stylesheet" href="dropzonejs/dropzone.css">

    {{-- Custom CSS && Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Lato|Playfair+Display:400,400i" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
    <header>
        @include('header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div id="footer">
            <p><small>created by Koji Del Mundo for his lola</small></p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="dropzonejs/dropzone.js"></script>
    <script src="js/macy.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    @stack('scripts')
</body>
</html>