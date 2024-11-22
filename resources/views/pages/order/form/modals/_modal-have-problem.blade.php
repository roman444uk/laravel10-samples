@php
    /**
     * @var App\Models\Order $order
     */
@endphp

<x-modal id="have-problem-form-modal">
    <x-slot:title>
        {{ __('chats.sending_problem_message_to_laboratory_chat') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="have-problem-save-button"
                  class="btn btn-secondary"
                  type="submit"
                  :loadable="true"
        >{{ __('buttons.send') }}</x-button>
    </x-slot:buttons>

    <form id="have-problem-form">
        @csrf

        <div class="form-group local-forms">
            <x-form.textarea id="reject-reason"
                             class="floating"
                             name="message"
                             rows="5"
                             :label="__('chats.describe_your_problem')"
            ></x-form.textarea>
        </div>
    </form>
</x-modal>
