@props(['title' => null, 'active' => null])
@php
    $user = auth()->user();
    $recent = $user->portfolios()->latest('updated_at')->take(6)->get(['id', 'title', 'template']);
    $navItem = 'flex items-center gap-3 rounded-(--radius-ui) px-3 py-2 text-[0.9375rem] font-medium transition-colors duration-150';
    $navIdle = 'text-ink-100 hover:bg-ink-600 hover:text-white';
    $navActive = 'bg-ink-700 text-white';
@endphp
<x-layouts.base :title="$title">
    <div class="lg:grid lg:min-h-dvh lg:grid-cols-[256px_1fr]" x-data="{ menu: false }">
        {{-- Ink rail --}}
        <aside class="bg-ink-500 text-white lg:sticky lg:top-0 lg:flex lg:h-dvh lg:flex-col">
            <div class="flex items-center justify-between px-4 py-3 lg:px-5 lg:pt-6 lg:pb-8">
                <a href="{{ route('dashboard') }}" class="rounded-(--radius-ui) focus-visible:outline-white" aria-label="Folio dashboard">
                    <x-logo tone="white" />
                </a>
                <button type="button" class="btn btn-sm text-white hover:bg-ink-600 lg:hidden" @click="menu = !menu" :aria-expanded="menu" aria-controls="rail-nav">
                    <x-lucide-menu x-show="!menu" />
                    <x-lucide-x x-show="menu" x-cloak />
                    <span x-text="menu ? 'Close' : 'Menu'">Menu</span>
                </button>
            </div>

            <nav id="rail-nav" class="hidden flex-1 flex-col px-3 pb-4 lg:flex" :class="menu ? '!flex' : ''" aria-label="Main">
                <a href="{{ route('dashboard') }}" class="{{ $navItem }} {{ $active === 'dashboard' ? $navActive : $navIdle }}" @if($active === 'dashboard') aria-current="page" @endif>
                    <x-lucide-layout-grid class="size-[18px]" /> Manage portfolios
                </a>
                <a href="{{ route('portfolios.create') }}" class="{{ $navItem }} {{ $active === 'create' ? $navActive : $navIdle }}" @if($active === 'create') aria-current="page" @endif>
                    <x-lucide-plus class="size-[18px]" /> New portfolio
                </a>

                @if ($recent->isNotEmpty())
                    <p class="spec mt-8 mb-2 px-3 text-ink-200">Your portfolios</p>
                    <ul class="space-y-0.5">
                        @foreach ($recent as $item)
                            <li>
                                <a href="{{ route('portfolios.preview', $item) }}" class="{{ $navItem }} py-1.5 text-sm {{ $active === 'portfolio-'.$item->id ? $navActive : $navIdle }}">
                                    <span class="size-1.5 shrink-0 rounded-full bg-ink-200"></span>
                                    <span class="truncate">{{ $item->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-auto border-t border-ink-400/50 pt-4">
                    <div class="px-3 pb-3">
                        <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                        <p class="truncate text-[0.8125rem] text-ink-200">{{ $user->email }}</p>
                    </div>
                    <button type="button" class="{{ $navItem }} {{ $navIdle }} w-full"
                            onclick="document.getElementById('logout-dialog').showModal()" aria-haspopup="dialog">
                        <x-lucide-log-out class="size-[18px]" /> Log out
                    </button>

                    {{-- Confirm before logging out, so a stray click doesn't end the session. --}}
                    <dialog id="logout-dialog" aria-labelledby="logout-title"
                            class="m-auto w-[min(26rem,calc(100vw-2rem))] rounded-(--radius-ui) border border-n-200 bg-white p-0 text-n-900 shadow-[0_24px_48px_-16px_rgb(10_17_69/0.35)] backdrop:bg-n-950/50">
                        <form method="POST" action="{{ route('logout') }}" class="p-6">
                            @csrf
                            <div class="mb-4 flex size-10 items-center justify-center rounded-full bg-ink-50 text-ink-500">
                                <x-lucide-log-out class="size-5" />
                            </div>
                            <h2 id="logout-title" class="font-semiwide text-lg font-bold">Log out of Folio?</h2>
                            <p class="mt-2 text-sm leading-relaxed text-n-600">
                                Your saved portfolios stay in your account. Anything you haven’t saved on this page will be lost.
                            </p>
                            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                <button type="button" class="btn btn-secondary" onclick="this.closest('dialog').close()" autofocus>Stay logged in</button>
                                <button type="submit" class="btn btn-primary"><x-lucide-log-out /> Log out</button>
                            </div>
                        </form>
                    </dialog>
                </div>
            </nav>
        </aside>

        <main id="main" class="min-w-0">
            <x-flash />
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
