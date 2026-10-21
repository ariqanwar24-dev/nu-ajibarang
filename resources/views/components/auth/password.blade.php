@props([
    'name' => 'password',
    'label' => 'Kata sandi',
    'autocomplete' => 'current-password',
    'hint' => null,
    'required' => true,
    'autofocus' => false,
    'placeholder' => 'Masukkan kata sandi',
    'minlength' => null,
])

@php
    $id = 'auth-'.$name;
    $adaError = $errors->has($name);
    $kelasInput = 'block w-full rounded-xl border bg-white py-3 pl-11 pr-12 text-sm text-neutral-900 shadow-sm outline-none transition placeholder:text-neutral-400 focus:ring-4';
    $kelasInput .= $adaError
        ? ' border-red-300 focus:border-red-500 focus:ring-red-100'
        : ' border-brand-border focus:border-brand focus:ring-brand/10';

    $deskripsi = [];
    if (filled($hint)) {
        $deskripsi[] = $id.'-hint';
    }
    if ($adaError) {
        $deskripsi[] = $id.'-error';
    }
@endphp

<div>
    <label for="{{ $id }}" class="flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wider text-neutral-700">
        {{ $label }}
        @if ($required)
            <span class="text-brand-accent" aria-hidden="true">*</span>
        @endif
    </label>

    <div class="relative mt-2">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-brand-muted/70" aria-hidden="true">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 11V8a5 5 0 0 1 10 0v3m-9 0h8a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2Zm4 4v2"/>
            </svg>
        </span>

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="password"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            data-password-input
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            @if ($minlength) minlength="{{ $minlength }}" @endif
            @if ($adaError) aria-invalid="true" @endif
            @if ($deskripsi !== []) aria-describedby="{{ implode(' ', $deskripsi) }}" @endif
            class="{{ $kelasInput }}"
        >

        <button type="button"
                data-password-toggle="{{ $id }}"
                aria-controls="{{ $id }}"
                aria-pressed="false"
                aria-label="Tampilkan kata sandi"
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-xl text-brand-muted transition hover:text-brand focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand">
            <svg data-icon-show class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                <circle cx="12" cy="12" r="3" stroke-width="1.8"/>
            </svg>
            <svg data-icon-hide class="hidden h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 5.8A10.6 10.6 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17 17 0 0 1-2.4 3.2M6.5 8.1A17.5 17.5 0 0 0 2.5 12S6 18.5 12 18.5c1.2 0 2.3-.3 3.3-.7"/>
            </svg>
        </button>
    </div>

    @if ($adaError)
        <p id="{{ $id }}-error" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600">
            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            </svg>
            <span>{{ $errors->first($name) }}</span>
        </p>
    @endif

    @if (filled($hint))
        <p id="{{ $id }}-hint" class="mt-2 text-[11px] leading-5 text-brand-muted">{{ $hint }}</p>
    @endif
</div>
