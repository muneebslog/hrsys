<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-slate-700 dark:bg-slate-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
    @if(auth()->user()->role->name === 'admin')

   
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Admin')" class="grid">
                    <flux:navlist.item class="m-3" icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="users" :href="route('staffdirectory')" :current="request()->routeIs('staffdirectory')" wire:navigate>{{ __('Staff Directory') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="calendar-date-range" :href="route('leaverequests')" :current="request()->routeIs('leaverequests')" wire:navigate>{{ __('Leave Requests') }}</flux:navlist.item>
                    {{-- <flux:navlist.item class="m-3" icon="document-duplicate" :href="route('docs')" :current="request()->routeIs('docs')" wire:navigate>{{ __('Document Vault') }}</flux:navlist.item> --}}
                    <flux:navlist.item class="m-3" icon="chat-bubble-bottom-center-text" :href="route('feedlogs')" :current="request()->routeIs('feedlogs')" wire:navigate>{{ __('Feedback Logs') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="cog" :href="route('panel')" :current="request()->routeIs('panel')" wire:navigate>{{ __('Manage Basic Tables') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="map-pin" :href="route('rounds.sections')" :current="request()->routeIs('rounds.sections')" wire:navigate>{{ __('Round Sections') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="clipboard-document-check" :href="route('rounds.conduct')" :current="request()->routeIs('rounds.conduct')" wire:navigate>{{ __('Conduct Round') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="book-open-text" :href="route('admin.guide')" :current="request()->routeIs('admin.guide')" wire:navigate>{{ __('Admin Guide') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
             @elseif((auth()->user()->role->name ?? '') === 'supervisor')
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Supervisor')" class="grid">
                    <flux:navlist.item class="m-3" icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="clipboard-document-check" :href="route('rounds.conduct')" :current="request()->routeIs('rounds.conduct')" wire:navigate>{{ __('Conduct Round') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="book-open-text" :href="route('emp.guide')" :current="request()->routeIs('emp.guide')" wire:navigate>{{ __('Employee Guide') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
             @else
    
              <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Staff')" class="grid">
                    <flux:navlist.item class="m-3" icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="chat-bubble-bottom-center-text" :href="route('staffapplyleave')" :current="request()->routeIs('staffapplyleave')" wire:navigate>{{ __('Apply for Leave') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="clipboard-document-list" :href="route('staffcomplaints')" :current="request()->routeIs('staffcomplaints')" wire:navigate>{{ __('Staff Complaints') }}</flux:navlist.item>
                    <flux:navlist.item class="m-3" icon="book-open-text" :href="route('emp.guide')" :current="request()->routeIs('emp.guide')" wire:navigate>{{ __('Employee Guide') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
    @endif


            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <!-- Toast Notification Component -->
        <x-toast-notification />

        @fluxScripts
    </body>
</html>
