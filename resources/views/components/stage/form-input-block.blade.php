@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     */

    $isInline = $isInline ?? false;
    $labelClass = $attributes->get('label-class', 'col-md-6 col-lg-6 col-xl-6');
    $containerClass = $attributes->get('container-class', 'col-md-6 col-lg-6 col-xl-6');
@endphp

<div class="input-block row">
    <label @class(['col-form-label', $labelClass])>
        {{ $attributes->get('label') }}
    </label>
    <div @class([$containerClass])>
        <input {{ $attributes->except('label-class')->merge(['class' => 'form-control', 'type' => 'text']) }}>
    </div>
</div>
