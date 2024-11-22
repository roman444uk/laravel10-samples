@php
    /**
     * @var Illuminate\View\ComponentAttributeBag $attributes
     */

    $tagsEncoded = !($attributes->get('tags-encoded') !== null) || $attributes->get('tags-encoded');
@endphp

<x-form.label :id="$attributes->get('id')"
              :label="$attributes->get('label')"
              :label-icon-post="$attributes->get('label-icon-post')"
              :label-icon-tooltip="$attributes->get('label-icon-tooltip')"
></x-form.label>

@if($tagsEncoded)
    <textarea {{ $attributes->except(['value'])->merge(['class' => 'form-control']) }}>{{ $attributes->get('value') }}</textarea>
@else
    <textarea {{ $attributes->except(['value'])->merge(['class' => 'form-control']) }}>{!! $attributes->get('value') !!}</textarea>
@endif
