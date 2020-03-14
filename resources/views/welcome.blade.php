<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Test Task</title>
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    </head>
    <script>
        window.Laravel = {
            'csrfToken': '{{ csrf_token() }}',
        };
    </script>
    <body class="bg-white">
        {{-- <h1 class="text-center">Test Vue</h1> --}}
        <div id="app">
            <router-view></router-view>
        </div>
        <div>
            <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
            <script src="{{asset('/js/echo.js')}}"></script>
            <script src="{{asset('js/app.js')}}" ></script>
        </div>
    </body>
</html>