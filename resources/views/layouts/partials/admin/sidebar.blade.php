<?php
use App\Models\Menu;

$menus = Menu::with('submenus')->get();
?>

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('dashboard') }}">
            <div class="d-flex app-sidebar-logo-default align-items-center">
                <div class="d-flex align-items-center">
                    <img alt="Logo" src="{{ asset('assets/media/logos/android-chrome-192x192.png') }}" class="h-25px app-sidebar-logo-default me-2" />
                    <p class="app-sidebar-logo-default text-white fw-bold fs-2 mb-0">BTKV E-LOGBOOK</p>
                </div>
            </div>
            <img alt="Logo" src="{{ asset('assets/media/logos/android-chrome-192x192.png') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                    @foreach ($menus as $menu)
                    @php
                    // Filter submenus based on the user's permissions through roles
                    $filteredSubmenus = $menu->submenus->filter(function ($submenu) {
                    $userPermissions = auth()->user()->roles->flatMap(function ($role) {
                    return $role->permissions->pluck('id');
                    });
                    return $userPermissions->contains($submenu->permission_id);
                    });


                    $submenuRoutes = $filteredSubmenus->pluck('route')->toArray();
                    if (in_array('tindakan', $submenuRoutes)) {
                    if (!in_array('create-tindakan', $submenuRoutes)) {
                    $submenuRoutes[] = 'create-tindakan';
                    }
                    if (!in_array('edit-tindakan', $submenuRoutes)) {
                    $submenuRoutes[] = 'edit-tindakan';
                    }
                    }
                    if (in_array('logbook', $submenuRoutes)) {
                    if (!in_array('add-logbook', $submenuRoutes)) {
                    $submenuRoutes[] = 'add-logbook';
                    }
                    if (!in_array('edit-logbook', $submenuRoutes)) {
                    $submenuRoutes[] = 'edit-logbook';
                    }
                    }
                    @endphp

                    @if ($filteredSubmenus->isNotEmpty())
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (request()->routeIs($submenuRoutes) || (request()->routeIs('semua-tindakan') && $menu->name == 'Master Data')) ? 'show' : '' }}">
                        <span class="menu-link {{ request()->routeIs($submenuRoutes) ? 'active' : '' }}">
                            <span class="menu-icon">
                                <i class="{{ $menu->icon }} fs-2"></i>
                            </span>
                            <span class="menu-title">{{ $menu->name }}</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion">
                            @foreach ($filteredSubmenus as $submenu)
                            <div class="menu-item">
                                @php
                                $submenuRouteArray = [$submenu->route];
                                if ($submenu->route == 'tindakan') {
                                $submenuRouteArray[] = 'create-tindakan';
                                $submenuRouteArray[] = 'edit-tindakan';
                                $submenuRouteArray[] = 'detail-tindakan';
                                }
                                if ($submenu->route == 'logbook') {
                                $submenuRouteArray[] = 'add-logbook';
                                $submenuRouteArray[] = 'edit-logbook';
                                }
                                @endphp
                                <a class="menu-link {{ request()->routeIs($submenuRouteArray) ? 'active' : '' }}" href="{{ route($submenu->route) }}" {{ $submenu->route != 'user' ? 'wire:navigate' : '' }}>
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ $submenu->name }}</span>
                                </a>
                            </div>
                            @endforeach

                            @if (
                                $menu->name == 'Master Data' &&
                                (
                                    (Auth::user()->roles->pluck('name')->contains('dokter') && Auth::user()->akses_semua == 1)
                                    || Auth::user()->roles->pluck('name')->contains('admin')
                                    || Auth::user()->roles->pluck('name')->contains('operator')
                                    || Auth::user()->roles->pluck('name')->contains('developer')
                                )
                            )
                            <div class="menu-item">
                                @php
                                $semuaTindakanRoutes = ['semua-tindakan', 'semua-tindakan.*'];
                                @endphp
                                <a class="menu-link {{ request()->routeIs($semuaTindakanRoutes) ? 'active' : '' }}" href="{{ route('semua-tindakan') }}" wire:navigate>
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Semua Tindakan</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach


                </div>
            </div>
        </div>
    </div>

</div>
