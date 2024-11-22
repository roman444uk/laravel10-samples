@php
    /**
     * @var array $properties
     * @var string $title
     * @var bool $usePropertyIndexInFile
     */

    $usePropertyIndexInFile = $usePropertyIndexInFile ?? true;
@endphp

<div class="form-heading">
    <h4 class="text-center fw-bold">{{ $title }}</h4>
</div>

@foreach($properties as $propertyIndex => $property)
    <div class="stage-fields-row"
         data-prop="{{ 'stages[' . $stage->id . '][fields_laboratory][' . $property . ']' }}"
         style="padding: 15px 0;"
    >
        @include('pages.order.form.stage.fields-laboratory.' . ($usePropertyIndexInFile ? $propertyIndex . '-' : '') . $property, [
            'title' => ($usePropertyIndexInFile ? $propertyIndex . '. ' : '') . __('stages.' . str_replace('-', '_', $property)),
            'titleClass' => 'col2 col-md-4 col-lg-4 col-xl-4',
            'leftColumnClass' => '',
            'rightColumnClass' => '',
            'prefixForId' => 'stages-' . $stage->id . '-fields-laboratory-' . $property . '-',
            'prefixForName' => 'stages[' . $stage->id . '][fields_laboratory][' . str_replace('-', '_', $property) . ']',
        ])
    </div>
@endforeach
