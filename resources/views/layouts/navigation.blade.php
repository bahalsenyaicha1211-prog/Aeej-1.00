<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    @auth
        @php
            $user = auth()->user();
            $initials = strtoupper(substr($user->name, 0, 1) . (explode(' ', $user->name)[1][0] ?? ''));
        @endphp
    @endauth

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- GAUCHE : Logo + liens --}}
            <div class="flex items-center">
                <a href="{{ route('accueil') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                @auth
                    <div class="hidden sm:flex sm:items-center space-x-8 sm:ms-10">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>

                        @if($user->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                Admin
                            </x-nav-link>
                        @endif
                    </div>
                @endauth
            </div>

            {{-- DROITE : actions / dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                                
                                {{-- REMPLACEMENT DE L'IMAGE PAR LE CERCLE D'INITIALES --}}
                                <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs uppercase border border-gray-200 shadow-sm">
                                    {{ $initials }}
                                </div>

                                <span class="max-w-[160px] truncate">{{ $user->name }}</span>

                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                            @if($user->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')">Espace Admin</x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Déconnexion
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            {{-- HAMBURGER (mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 flex items-center gap-3">
                    {{-- INITIALES MOBILE --}}
                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm uppercase shadow-sm">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            Déconnexion
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>