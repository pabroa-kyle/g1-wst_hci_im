@php($input = fn ($name) => ['id' => $name, 'name' => $name, 'aria-invalid' => $errors->has($name) ? 'true' : 'false'])
<x-step-form :portfolio="$portfolio" :step="$step"
    intro="Start with the basics. Only the portfolio name and your full name are required. You can fill in the rest now or come back later.">

    <div class="space-y-6">
        <section class="panel p-5 sm:p-6" aria-labelledby="g-portfolio">
            <h2 id="g-portfolio" class="font-semiwide text-base font-bold text-n-950">Portfolio</h2>
            <p class="mt-1 text-sm text-n-600">Only you see this name. It labels the portfolio on your dashboard.</p>
            <div class="mt-5">
                <x-field label="Portfolio name" name="title" help="For example: “Web developer portfolio” or “Internship application”.">
                    <input type="text" class="field-input" maxlength="120" required
                           @foreach ($input('title') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                           value="{{ old('title', $portfolio->title) }}" placeholder="My portfolio">
                </x-field>
            </div>
        </section>

        <section class="panel p-5 sm:p-6" aria-labelledby="g-identity">
            <h2 id="g-identity" class="font-semiwide text-base font-bold text-n-950">You</h2>

            <div class="mt-5 grid gap-6 sm:grid-cols-[auto_1fr] sm:items-start"
                 x-data="photoField(@js($portfolio->photoUrl()))" data-field>
                <div class="relative size-28 overflow-hidden rounded-full bg-ink-50 ring-1 ring-n-200">
                    <template x-if="preview">
                        <img :src="preview" alt="Profile picture preview" class="size-full object-cover">
                    </template>
                    <div x-show="!preview" class="flex size-full items-center justify-center text-ink-400">
                        <x-lucide-user-round class="size-10" />
                    </div>
                    <div x-show="busy" x-cloak class="absolute inset-0 flex items-center justify-center bg-white/70 text-xs font-semibold text-n-700">Processing…</div>
                </div>
                <div>
                    <p class="field-label mb-1"><span>Profile picture</span> <span class="optional">Optional</span></p>
                    <p class="text-sm text-n-600">JPG, PNG, or WebP. A square photo of your face works best. Large photos are resized automatically.</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <label class="btn btn-secondary btn-sm cursor-pointer focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-ink-500">
                            <x-lucide-image-plus />
                            <span x-text="preview ? 'Replace photo' : 'Upload photo'">Upload photo</span>
                            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="sr-only" x-ref="input" @change="pick">
                        </label>
                        <button type="button" class="btn btn-ghost btn-sm text-danger-600 hover:bg-danger-50" x-show="preview" x-cloak @click="clear">
                            <x-lucide-trash-2 /> Remove
                        </button>
                    </div>
                    <input type="hidden" name="remove_photo" :value="removed ? 1 : 0" value="0">
                    @error('photo')
                        <p class="field-error"><x-lucide-circle-alert class="size-3.5 shrink-0" /> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <x-field label="Full name" name="full_name">
                    <input type="text" class="field-input" maxlength="120" autocomplete="name" required
                           @foreach ($input('full_name') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                           value="{{ old('full_name', $portfolio->full_name) }}">
                </x-field>
                <x-field label="Headline" name="headline" optional help="Your role or focus, e.g. “Front-end developer”.">
                    <input type="text" class="field-input" maxlength="160"
                           @foreach ($input('headline') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                           value="{{ old('headline', $portfolio->headline) }}" placeholder="Aspiring software engineer">
                </x-field>
            </div>
        </section>

        <section class="panel p-5 sm:p-6" aria-labelledby="g-contact">
            <h2 id="g-contact" class="font-semiwide text-base font-bold text-n-950">Contact</h2>
            <p class="mt-1 text-sm text-n-600">Shown on your generated portfolio so people can reach you.</p>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <x-field label="Email" name="email" optional>
                    <input type="email" class="field-input" maxlength="160" autocomplete="email" inputmode="email"
                           @foreach ($input('email') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                           value="{{ old('email', $portfolio->email) }}" placeholder="you@example.com">
                </x-field>
                <x-field label="Contact number" name="phone" optional>
                    <input type="tel" class="field-input" maxlength="40" autocomplete="tel" inputmode="tel"
                           @foreach ($input('phone') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                           value="{{ old('phone', $portfolio->phone) }}" placeholder="+63 912 345 6789">
                </x-field>
                <x-field label="Address" name="address" optional class="sm:col-span-2" help="City and province is usually enough.">
                    <input type="text" class="field-input" maxlength="255" autocomplete="address-level2"
                           @foreach ($input('address') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                           value="{{ old('address', $portfolio->address) }}" placeholder="Quezon City, Metro Manila">
                </x-field>
            </div>
        </section>

        <section class="panel p-5 sm:p-6" aria-labelledby="g-about">
            <h2 id="g-about" class="font-semiwide text-base font-bold text-n-950">About me</h2>
            <div class="mt-5" x-data="{ count: {{ mb_strlen(old('about', $portfolio->about ?? '')) }} }">
                <x-field label="About me" name="about" optional>
                    <textarea class="field-input min-h-44" maxlength="3000" rows="7" @input="count = $event.target.value.length"
                              @foreach ($input('about') as $k => $v) {{ $k }}="{{ $v }}" @endforeach
                              placeholder="Two or three short paragraphs about who you are, what you do, and what you’re looking for.">{{ old('about', $portfolio->about) }}</textarea>
                </x-field>
                <p class="mt-1.5 text-right text-xs text-n-500"><span x-text="count">0</span> / 3,000</p>
            </div>
        </section>
    </div>
</x-step-form>
