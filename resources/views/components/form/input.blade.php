@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     */

    $inputWrapIcon = $attributes->get('input-wrap-icon');
    $inputIcon = $attributes->get('input-icon');
@endphp

<x-form.label :id="$attributes->get('id')"
              :label="$attributes->get('label')"
              :required="$attributes->get('required')"
></x-form.label>

@if($inputWrapIcon)
    <div class="{{ $inputWrapIcon }}">
@endif

<input {{ $attributes->merge(['class' => 'form-control']) }}>

@if($inputWrapIcon)
    </div>
@endif

@if($inputIcon)
    <span class="{{ $inputIcon }}"></span>
@endif

<div class="text-danger pt-2">
    @error('0')
    {{ $message }}
    @enderror
    @error($attributes->get('name'))
    {{ $message }}
    @enderror
</div>
