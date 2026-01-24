<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- GAUCHE : Logo + liens --}}
            <div class="flex items-center">
                {{-- Logo --}}
                <a href="{{ route('accueil') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                {{-- Liens (connecté uniquement) --}}
                @auth
                    <div class="hidden sm:flex sm:items-center space-x-8 sm:ms-10">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>

                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                Admin
                            </x-nav-link>
                        @endif
                    </div>
                @endauth
            </div>

            <a href="{{ route('membre.annonces.index') }}"
   class="relative inline-flex items-center text-gray-600 hover:text-gray-900">

    {{-- Icône cloche --}}
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>

    {{-- Badge --}}
    @if(($annoncesNonLues ?? 0) > 0)
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            {{ $annoncesNonLues }}
        </span>
    @endif
</a>


            {{-- DROITE : actions / dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    @php
                        $user = auth()->user();
                        $avatar = $user->profile_photo_path
                            ? asset('storage/'.$user->profile_photo_path)
                            : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=111827&color=fff';
                    @endphp

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button"
                                class="inline-flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md
                                       text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                                <img class="h-9 w-9 rounded-full object-cover border border-gray-200"
                                     src="{{ $avatar }}"
                                     alt="Avatar">
                                <span class="max-w-[160px] truncate">{{ $user->name }}</span>

                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>

                            @if($user->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    Espace Admin
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Déconnexion
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Connexion
                        </a>
                        <a href="{{ route('inscription') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Inscription
                        </a>
                    </div>
                @endguest
            </div>

            {{-- HAMBURGER (mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400
                               hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            @php
                $user = auth()->user();
                $avatar = $user->profile_photo_path
                    ? asset('storage/'.$user->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=111827&color=fff';
            @endphp

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                @if($user->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        Admin
                    </x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200"
                         src="{{ $avatar }}"
                         alt="Avatar">
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profil
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Déconnexion
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="pt-4 pb-3 space-y-2 px-4 border-t border-gray-200">
                <a href="{{ route('login') }}" class="block text-gray-700">Connexion</a>
                <a href="{{ route('inscription') }}" class="block text-gray-700">Inscription</a>
            </div>
        @endguest
    </div>
</nav>
