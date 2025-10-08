<!-- section-content-right -->
<div class="section-content-right">
    <!-- header-dashboard -->
    <div class="header-dashboard">
        <div class="wrap">
            <div class="header-left">
                {{-- === LOGO + TOGGLE SIDEBAR === --}}
                <a href="{{ route('dashboard') }}">
                    <img id="logo_header_mobile"
                        src="{{ asset('images/logo/logo.png') }}"
                        data-light="{{ asset('images/logo/logo.png') }}"
                        data-dark="{{ asset('images/logo/logo-dark.png') }}"
                        alt="Logo"
                        width="154" height="52">
                </a>

                <div class="button-show-hide">
                    <i class="icon-menu-left"></i>
                </div>
               
            </div>

            {{-- === HEADER RIGHT MENU === --}}
            <div class="header-grid">

                {{-- === DARK MODE SWITCH === --}}
                <div class="header-item button-dark-light">
                    <i class="icon-moon"></i>
                </div>
                
                {{-- === FULLSCREEN TOGGLE === --}}
                <div class="header-item button-zoom-maximize">
                    <i class="icon-maximize"></i>
                </div>

                {{-- === USER DROPDOWN === --}}
                <div class="popup-wrap user type-header">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                            id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="header-user wg-user">
                                <span class="image">
                                    <img src="{{ asset('images/avatar/user-1.png') }}" alt="">
                                </span>
                                <span class="flex flex-column">
                                    <span class="body-title mb-2">{{ Auth::user()->name ?? 'User' }}</span>
                                    <span class="text-tiny text-capitalize">{{ Auth::user()->role ?? 'user' }}</span>
                                </span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownUser">
                            <li>
                                <a href="" class="user-item">
                                    <div class="icon"><i class="icon-user"></i></div>
                                    <div class="body-title-2">Profil</div>
                                </a>
                            </li>
                            <li>
                                <a href="" class="user-item">
                                    <div class="icon"><i class="icon-settings"></i></div>
                                    <div class="body-title-2">Pengaturan</div>
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="user-item w-full text-start">
                                        <div class="icon"><i class="icon-log-out"></i></div>
                                        <div class="body-title-2">Keluar</div>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> {{-- /header-grid --}}
        </div>
    </div>
    <!-- /header-dashboard -->
</div>
<!-- /section-content-right -->
