@php
    /**
     * @var Illuminate\Database\Eloquent\Collection $addressesCollection
     * @var int $entityId
     */

    $class = $class ?? '';
    $dataId = $dataId ?? false;
    $disabled = $disabled ?? false;
    $entityId = $entityId ?? false;
    $formModalId = $formModalId ?? null;
    $id = $id ?? null;
    $label = $label ?? null;
    $name = $name ?? null;
    $selectedValue = $selectedValue ?? null;
@endphp

<div @class(['input-group', 'disabled' => $disabled])">
    <div class="form-group local-forms mb-0">
        <x-form.select :id="$id"
                       :class="'select floating ' . $class"
                       :name="$name"
                       :options="$options"
                       :selected-value="$selectedValue"
                       :label="$label"
                       :required="true"
                       :disabled="$disabled"
                       :data-id="$entityId"
        ></x-form.select>
    </div>

    <span id="{{ 'address-add-button-' . $id }}"
          @class([
            'input-group-text',
          ])
          data-id="{{ $entityId }}"
          @if(!$disabled && $formModalId) data-bs-toggle="modal" data-bs-target="#{{ $formModalId }}" @endif
    >
        <i class="fa fa-add"></i>
    </span>
</div>
