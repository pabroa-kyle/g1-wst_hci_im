<x-layouts.auth title="Log in" heading="Welcome back" lead="Log in to manage, edit, and generate your portfolios.">
    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf
        <x-field label="Email" name="email">
            <input id="email" name="email" type="email" class="field-input" autocomplete="email" inputmode="email" required autofocus
                   value="{{ old('email') }}" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
        </x-field>
        <x-field label="Password" name="password">
            <input id="password" name="password" type="password" class="field-input" autocomplete="current-password" required
                   aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
        </x-field>
        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-n-700">
            <input type="checkbox" name="remember" value="1" class="size-4 rounded-[3px] accent-ink-500" @checked(old('remember'))>
            Keep me logged in
        </label>
        <button type="submit" class="btn btn-primary btn-lg w-full">Log in <x-lucide-arrow-right /></button>
    </form>
    <p class="mt-6 text-sm text-n-600">
        New to Folio? <a href="{{ route('register') }}" class="font-semibold text-ink-500 underline-offset-4 hover:underline">Create an account</a>
    </p>
</x-layouts.auth>
