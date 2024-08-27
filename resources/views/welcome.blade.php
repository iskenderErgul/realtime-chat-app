<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>

        <title>Laravel</title>
        @vite('resources/css/app.css')
    </head>
    <body >

    <div id="app">
        <app></app>

    </div>




    @vite('resources/js/app.js')

    </body>
</html>
