<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('bill.index') }}" class="h2">
                        Пино
                        {{--                        <x-jet-application-mark class="block h-9 w-auto" />--}}
                    </a>
                </div>
                @if(in_array(auth()->user()->id,[1,2]))
                    <div class="hidden sm:flex sm:items-center ml-6">
                        <x-jet-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>Admin</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Админка
                                </div>

                                <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                </x-jet-dropdown-link>
                                <x-jet-dropdown-link href="{{ route('chains.index') }}">
                                    Цепочки
                                </x-jet-dropdown-link>
                                <x-jet-dropdown-link href="{{ route('organisations.index') }}">
                                    Организации
                                </x-jet-dropdown-link>
                                <x-jet-dropdown-link href="{{ route('users.index') }}">
                                    Пользователи
                                </x-jet-dropdown-link>
                                <x-jet-dropdown-link href="{{ route('users.index') }}">
                                    Пользователи
                                </x-jet-dropdown-link>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
            @endif
            <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                    <x-jet-nav-link href="{{ route('bill.my') }}" :active="request()->routeIs('bill.my')">
                        Мои счета
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('bill.accept') }}" :active="request()->routeIs('bill.accept')">
                        Счета для подтверждения
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('bill.accepted') }}" :active="request()->routeIs('bill.accepted')">
                        Подтвержденные счета
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.index')">
                        Контрагенты
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('kanban.index') }}" :active="request()->routeIs('kanban.index')">
                        Задачи
                    </x-jet-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->


            <div class="hidden sm:flex sm:items-center">

                <x-jet-dropdown-link href="{{ route('profile.show') }}">
                    {{Auth::user()->name}}
                </x-jet-dropdown-link>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('bill.my') }}" :active="request()->routeIs('bill.my')">
                Мои счета
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('bill.accept') }}" :active="request()->routeIs('bill.accept')">
                Счета для подтверждения
            </x-jet-responsive-nav-link>
            <x-jet-responsive-nav-link href="{{ route('bill.accepted') }}"
                                       :active="request()->routeIs('bill.accepted')">
                Подтвержденные счета
            </x-jet-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}"
                                           :active="request()->routeIs('profile.show')">
                    Профиль
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}"
                                               :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-jet-responsive-nav-link>
                @endif

            <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        Выход
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                               :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('teams.create') }}"
                                               :active="request()->routeIs('teams.create')">
                        {{ __('Create New Team') }}
                    </x-jet-responsive-nav-link>

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link"/>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</nav>
@if(in_array(auth()->user()->id,[1,2]))
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100" style="font-size: 12px">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between py-1">

                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('bill.index') }}" class="text-secondary">
                            Админка
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-jet-nav-link href="{{ route('profile.show') }}">
                        </x-jet-nav-link>
                        <x-jet-nav-link href="{{ route('chains.index') }}">
                            Цепочки
                        </x-jet-nav-link>
                        <x-jet-nav-link href="{{ route('organisations.index') }}">
                            Организации
                        </x-jet-nav-link>
                        <x-jet-nav-link href="{{ route('users.index') }}">
                            Пользователи
                        </x-jet-nav-link>
                        <x-jet-nav-link href="{{ route('users.index') }}">
                            Пользователи
                        </x-jet-nav-link>

                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-jet-responsive-nav-link href="{{ route('bill.my') }}" :active="request()->routeIs('bill.my')">
                    Мои счета
                </x-jet-responsive-nav-link>
                <x-jet-responsive-nav-link href="{{ route('bill.accept') }}"
                                           :active="request()->routeIs('bill.accept')">
                    Счета для подтверждения
                </x-jet-responsive-nav-link>
                <x-jet-responsive-nav-link href="{{ route('bill.accepted') }}"
                                           :active="request()->routeIs('bill.accepted')">
                    Подтвержденные счета
                </x-jet-responsive-nav-link>
            </div>


        </div>
    </nav>
@endif
