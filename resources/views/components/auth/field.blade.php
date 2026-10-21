@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'icon' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z',
    'hint' => null,
    'required' => true,
    'autofocus' => false,
    'autocomplete' => 'off',
    'placeholder' => '',
    'minlength' => null,
])

@php
    $id = 'auth-'.$name;
    $adaError = $errors->has($name);
    $kelasInput = 'block w-full rounded-xl border bg-white py-3 pl-11 pr-4 text-sm text-neutral-900 shadow-sm outline-none transition placeholder:text-neutral-400 focus:ring-4';
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/>
            </svg>
        </span>

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            @if ($minlength) minlength="{{ $minlength }}" @endif
            @if ($adaError) aria-invalid="true" @endif
            @if ($deskripsi !== []) aria-describedby="{{ implode(' ', $deskripsi) }}" @endif
            class="{{ $kelasInput }}"
        >
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
