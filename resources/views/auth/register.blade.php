<x-layouts.auth title="Create account" heading="Create your account" lead="Your portfolios are saved to your account, so only you can edit or delete them.">
    <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf
        <x-field label="Full name" name="name">
            <input id="name" name="name" type="text" class="field-input" autocomplete="name" required autofocus maxlength="120"
                   value="{{ old('name') }}" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
        </x-field>
        <x-field label="Email" name="email">
            <input id="email" name="email" type="email" class="field-input" autocomplete="email" inputmode="email" required maxlength="160"
                   value="{{ old('email') }}" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
        </x-field>
        <div class="grid gap-5 sm:grid-cols-2">
            <x-field label="Password" name="password" help="At least 8 characters.">
                <input id="password" name="password" type="password" class="field-input" autocomplete="new-password" required minlength="8"
                       aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
            </x-field>
            <x-field label="Confirm password" name="password_confirmation">
                <input id="password_confirmation" name="password_confirmation" type="password" class="field-input" autocomplete="new-password" required>
            </x-field>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-full">Create account <x-lucide-arrow-right /></button>
    </form>
    <p class="mt-6 text-sm text-n-600">
        Already have an account? <a href="{{ route('login') }}" class="font-semibold text-ink-500 underline-offset-4 hover:underline">Log in</a>
    </p>
</x-layouts.auth>
