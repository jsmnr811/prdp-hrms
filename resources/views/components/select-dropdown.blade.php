@props([
    'wireModel' => null,
    'placeholder' => null,
    'options' => [],
])

<flux:select 
    {{ $attributes->merge(['wire:model' => $wireModel, 'placeholder' => $placeholder]) }}
>
    @foreach($options as $key => $label)
        @php
            $value = is_int($key) ? $label : $key;
        @endphp
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</flux:select>