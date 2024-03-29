<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="row">
                <div class="col-8">

                    Профиль / {{auth()->user()->name}} / {{auth()->user()->user_role->name}}
                </div>
                <div class="col-4 text-right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-jet-responsive-nav-link href="{{ route('logout') }}" class="btn btn-danger"
                                                   onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            Выход
                        </x-jet-responsive-nav-link>
                    </form>
                </div>
            </div>
        </h2>
    </x-slot>

    <div>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border/>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border/>
            @endif

            {{--            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())--}}
            {{--                <div class="mt-10 sm:mt-0">--}}
            {{--                    @livewire('profile.two-factor-authentication-form')--}}
            {{--                </div>--}}

            {{--                <x-jet-section-border />--}}
            {{--            @endif--}}

            {{--            <div class="mt-10 sm:mt-0">--}}
            {{--                @livewire('profile.logout-other-browser-sessions-form')--}}
            {{--            </div>--}}

            {{--            <x-jet-section-border />--}}
            {{----}}
            {{--            <div class="mt-10 sm:mt-0">--}}
            {{--                @livewire('profile.delete-user-form')--}}
            {{--            </div>--}}
        </div>
    </div>
</x-app-layout>
