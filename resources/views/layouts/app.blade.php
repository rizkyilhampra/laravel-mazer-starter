<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      {{ isset($title) ? $title.' | '.config('app.name') : config('app.name') }}

    </title>

    @livewireStyles
    @vite('resources/js/app.js')
  </head>

  <body>
    <script
      src="{{ Vite::asset('resources/js/initTheme.js') }}"
      type="module"
    ></script>

    <div id="app">
      @include('layouts.partials.sidebar')
      <div id="main" class="layout-navbar navbar-fixed">
        @include('layouts.partials.header')
        <div id="main-content">
          <div class="page-heading">
            <div class="page-title">
              <div class="row">
                <div class="col-12">
                  <h3>
                    {{ isset($pageTitle) ? $pageTitle : '' }}
                  </h3>
                </div>
              </div>
            </div>
          </div>
          {{ $slot }}
        </div>
      </div>
    </div>

    @livewireScriptConfig
    <script
      src="{{ Vite::asset('resources/js/components/dark.js') }}"
      type="module"
    ></script>
    <script
      src="{{ Vite::asset('resources/js/mazer.js') }}"
      type="module"
    ></script>
  </body>
</html>
