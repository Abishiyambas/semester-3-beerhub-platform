<div {{ $attributes->merge([
    'class' => 'grid gap-4 grid-cols-[1fr_2fr_1fr] h-full',
]) }}>
    {{ $slot }}
</div>
