<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      {{ isset($title) ? $title.' | '.config('app.name') : config('app.name') }}
    </title>

    @vite(['resources/js/app.js', 'resources/scss/pages/auth.scss'])
  </head>

  <body>
    <script
      src="{{ Vite::asset('resources/js/initTheme.js') }}"
      type="module"
    ></script>
    <div id="auth">
      <div class="row h-100 justify-content-center">
        {{ $slot }}
      </div>
    </div>
  </body>
</html>
