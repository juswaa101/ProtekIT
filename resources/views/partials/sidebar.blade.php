<nav class="sidebar d-none">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="{{ asset('image/sidebar-icon.png') }}" alt="">
            </span>

            <div class="text logo-text mx-2">
                <span class="name text-wrap text-danger">
                    @auth
                        {{ Str::upper(auth()->user()->name) }}
                    @else
                        {{ Str::upper('guest') }}
                    @endauth
                </span>
            </div>
        </div>

        <i class="bx bx-chevron-right toggle"></i>
    </header>

    <div class="menu-bar">
        <div class="menu">


            @auth
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="{{ route('home') }}">
                            <i class="bx bx-home-alt icon"></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    @can('view_users', App\Models\User::class)
                        <li class="nav-link">
                            <a href="{{ route('users.index') }}">
                                <i class="bx bx-group icon"></i>
                                <span class="text nav-text">Users</span>
                            </a>
                        </li>
                    @endcan

                    @can('view_roles', App\Models\Role::class)
                        <div>
                            <li class="nav-link">
                                <a href="#" class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#roles-collapse">
                                    <i class="bx bx-shield icon"></i>
                                    <span class="text nav-text">Roles <i class="bx bx-chevron-right toggle"></i></span>
                                </a>
                            </li>
                            <div class="collapse collapse-menu" id="roles-collapse">
                                <ul>

                                    <li class="nav-link">
                                        <a href="{{ route('roles.index') }}">
                                            <i class="bx bx-cog icon"></i>
                                            <span class="text nav-text" style="font-size: 15px;">Manage</span>
                                        </a>
                                    </li>

                                    <li class="nav-link">
                                        <a href="/assign/roles">
                                            <i class="bx bx-user-plus icon"></i>
                                            <span class="text nav-text" style="font-size: 15px;">Assign</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endcan

                    @can('view_permissions', App\Models\Permission::class)
                        <div>
                            <li class="nav-link">
                                <a href="#" class="btn-toggle" data-bs-toggle="collapse"
                                    data-bs-target="#permissions-collapse">
                                    <i class="bx bx-lock icon"></i>
                                    <span class="text nav-text">Permissions <i class="bx bx-chevron-right toggle"></i></span>
                                </a>
                            </li>
                            <div class="collapse collapse-menu" id="permissions-collapse">
                                <ul>

                                    <li class="nav-link">
                                        <a href="{{ route('permissions.index') }}">
                                            <i class="bx bx-cog icon"></i>
                                            <span class="text nav-text" style="font-size: 15px;">Manage</span>
                                        </a>
                                    </li>

                                    <li class="nav-link">
                                        <a href="/assign/permissions">
                                            <i class="bx bx-user-plus icon"></i>
                                            <span class="text nav-text" style="font-size: 15px;">Assign</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endcan
                </ul>
            @endauth
        </div>

        @auth
            <div class="bottom-content">
                <li class="">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out icon"></i>
                        <span class="text nav-text">Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class="bx bx-moon icon moon"></i>
                        <i class="bx bx-sun icon sun"></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>

            </div>
        @endauth

        @guest
            <div class="bottom-content">
                <li class="">
                    <a href="{{ route('login') }}">
                        <i class="bx bx-log-in icon"></i>
                        <span class="text nav-text">Login</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('register') }}">
                        <i class="bx bx-user icon"></i>
                        <span class="text nav-text">Register</span>
                    </a>
                </li>
            </div>
        @endguest
    </div>
</nav>
