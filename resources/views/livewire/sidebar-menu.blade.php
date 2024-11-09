<?php

use Livewire\Volt\Component;

new class extends Component
{
    public array $menu = [
        [
            'route' => 'dashboard',
            'name' => 'Dashboard',
            'icon' => 'bi bi-grid-fill',
            'role' => [
                \App\Enums\RoleEnum::ADMIN->value
            ],
        ],
    ];
}

?>

<div class="sidebar-menu pb-5">
  <ul class="menu">
    <li class="sidebar-title">Menu</li>
    @foreach ($menu as $item)
      @if (! auth()->user()->hasRole($item['role']))
        @continue
      @endif

      <li
        class="sidebar-item @if (isset($item['url']))
            {{ request()->is($item['url'].'*') ? 'active' : '' }}
        @else
            {{ request()->routeIs($item['route']) ? 'active' : '' }}
        @endif {{ isset($item['children']) ? 'has-sub' : '' }}"
      >
        <a
          href="{{ isset($item['children']) ? '#' : route($item['route']) }}"
          @if (! isset($item['children']))
              wire:navigate
          @endif
          class="sidebar-link"
        >
          <i class="{{ $item['icon'] }}"></i>
          <span>{{ $item['name'] }}</span>
        </a>

        @if (isset($item['children']))
          <ul
            class="submenu @if (isset($item['url']))
                @if (request()->is($item['url'].'*'))
                    submenu-open
                @endif
            @endif"
          >
            @foreach ($item['children'] as $child)
              <li
                class="submenu-item {{
                  request()
                      ->is($item['url'].'/'.$child['url'].'*')
                  ? 'active'
                  : ''
                }}"
              >
                <a
                  href="{{ route($child['route']) }}"
                  wire:navigate
                  class="sidebar-link"
                >
                  @if (isset($child['icon']))
                    <i class="{{ $child['icon'] }}"></i>
                  @endif

                  <span>{{ $child['name'] }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </li>
    @endforeach
  </ul>
</div>
