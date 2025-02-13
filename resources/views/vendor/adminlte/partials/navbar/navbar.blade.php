@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav
    class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- RTL/LTR Toggle Button --}}
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('toggleDirection') }}">
                <i class="fas fa-language"></i>
                Switch to {{ session('direction') == 'ltr' ? 'RTL' : 'LTR' }}
            </a>
        </li> --}}

        <li class="nav-item mx-2">
            <a class="nav-link btn" href="javascript:void(0);" onclick="customBack()">
                <i class="fas fa-arrow-left mr-1"></i>
                <span class="d-none d-md-inline">Back</span>
            </a>
        </li>
        
        
        {{-- Language & RTL/LTR Toggle Button --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('toggleLanguage') }}">
                {{-- <i class="fas fa-language"></i> --}}
                {{ session('locale', 'en') == 'en' ? 'فارسی' : 'English' }}
            </a>
        </li>


        {{-- User menu link --}}
        @if (Auth::user())
            @if (config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if ($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>



<script>
    function customBack() {
        let referrer = document.referrer; // Get the previous URL
        let currentURL = window.location.href; // Get the current URL
        let homeURL = "{{ url('/home') }}"; // Home page URL
        let loginURL = "{{ url('/login') }}"; // Login page URL

        if (referrer.includes(loginURL)) {
            // If the previous page was login, always go to home
            window.location.href = homeURL;
        } else if (currentURL === homeURL) {
            // If already on home, refresh the page instead of going back
            window.location.reload();
        } else {
            // Otherwise, go back normally in history
            history.back();
        }
    }
</script>