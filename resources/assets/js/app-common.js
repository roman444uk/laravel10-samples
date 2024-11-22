$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        "Access-Control-Allow-Origin": "*"
    },
    xhrFields: {
        withCredentials: true
    }
    // crossDomain: true
});
// $.support.cors = true;

$.datepicker.regional['ru'] = {
    closeText: trans('datepicker.closeText'),
    currentText: trans('datepicker.currentText'),
    dateFormat: trans('datepicker.dateFormat'),
    dayNames: [
        trans('datepicker.dayNames.0'), trans('datepicker.dayNames.1'), trans('datepicker.dayNames.2'), trans('datepicker.dayNames.3'), trans('datepicker.dayNames.4'), trans('datepicker.dayNames.5'), trans('datepicker.dayNames.6')
    ],
    dayNamesMin: [
        trans('datepicker.dayNamesMin.0'), trans('datepicker.dayNamesMin.1'), trans('datepicker.dayNamesMin.2'), trans('datepicker.dayNamesMin.3'), trans('datepicker.dayNamesMin.4'), trans('datepicker.dayNamesMin.5'), trans('datepicker.dayNamesMin.6')
    ],
    dayNamesShort: [
        trans('datepicker.dayNamesShort.0'), trans('datepicker.dayNamesShort.1'), trans('datepicker.dayNamesShort.2'), trans('datepicker.dayNamesShort.3'), trans('datepicker.dayNamesShort.4'), trans('datepicker.dayNamesShort.5'), trans('datepicker.dayNamesShort.6')
    ],
    firstDay: 0,
    isRTL: false,
    monthNames: [
        trans('datepicker.monthNames.0'), trans('datepicker.monthNames.1'), trans('datepicker.monthNames.2'), trans('datepicker.monthNames.3'), trans('datepicker.monthNames.4'), trans('datepicker.monthNames.5'), trans('datepicker.monthNames.6'), trans('datepicker.monthNames.7'), trans('datepicker.monthNames.8'), trans('datepicker.monthNames.9'), trans('datepicker.monthNames.10'), trans('datepicker.monthNames.11'),
    ],
    monthNamesShort: [
        trans('datepicker.monthNamesShort.0'), trans('datepicker.monthNamesShort.1'), trans('datepicker.monthNamesShort.2'), trans('datepicker.monthNamesShort.3'), trans('datepicker.monthNamesShort.4'), trans('datepicker.monthNamesShort.5'), trans('datepicker.monthNamesShort.6'), trans('datepicker.monthNamesShort.7'), trans('datepicker.monthNamesShort.8'), trans('datepicker.monthNamesShort.9'), trans('datepicker.monthNamesShort.10'), trans('datepicker.monthNamesShort.11'),
    ],
    nextText: trans('datepicker.nextText'),
    prevText: trans('datepicker.prevText'),
    showMonthAfterYear: false,
    weekHeader: 'Wk',
    yearSuffix: ''
};

const sendSubmittedForm = (button, form) => {
    showButtonLoader(button);

    $.ajax({
        type: form.method,
        url: form.action,
        data: $(form).serializeArray(),
        success: function (data) {
            closeModalWithForm(form);

            showSuccessPopup(trans('common.operation_succeed'), null, {
                onConfirmed: function () {
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            showErrorPopup(jqXHR.status === 422 ? null : jqXHR.responseJSON.message, jqXHR.responseJSON.errors);
        },
        complete: function () {
            hideButtonLoader(button);
        }
    });
}

const subscribeFormOnSubmit = (button, form) => {

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        sendSubmittedForm(button, form);
    });

    button.addEventListener('click', (e) => {
        e.preventDefault();

        sendSubmittedForm(button, form);
    });
}

const DOMContentLoadedCallbacks = [];
const DOMContentLoadedStackPush = (callback) => {
    DOMContentLoadedCallbacks.push(callback);
}

const DOMContentLoadedStackCall = () => {
    DOMContentLoadedCallbacks.forEach((callback) => {
        callback();
    })
}

/**
 * Dropdown action labels
 */
$(document).on('click', '.dropdown.action-label .dropdown-item', function (e) {
    setTimeout(() => {
        $(this).closest('.action-label')
            .data('value', this.dataset.value)
            .find('.custom-badge')
            .html(this.innerHTML)
            .attr('class', 'custom-badge dropdown-toggle status-' + this.dataset.color)
            .data('value', this.dataset.value);

        $(this).closest('.action-label').trigger('change');
    }, 200);
});

/**
 * Page size selects labels.
 */
const pageSizeSelectLabel = (select) => {
    select.querySelectorAll('option').forEach((option) => {
        option.label = option.selected ? trans('common.recs_count_with_number', {number: option.value}) : option.value;
    });
}

$(document).on('click', '.linkable', function (e) {
    if (['A', 'BUTTON'].indexOf(e.target.nodeName) > -1 || ['A', 'BUTTON'].indexOf(e.target.parentElement.nodeName) > -1) {
        return;
    }

    e.stopPropagation();
    e.preventDefault();

    window.location = this.dataset.link;
});

/**
 * Offcanvas resizing.
 */
let caughtCanvas;
let caughtCanvasOptions = {
    width: null,
    clientX: null
};

const caughtCanvasGetWidth = (clientX) => {
    return caughtCanvasOptions.width + (caughtCanvasOptions.clientX - clientX);
}

$(document).mousedown('.offcanvas-resizer', function (e) {
    if ($(e.target).hasClass('offcanvas-resizer') || $(e.target).hasClass('offcanvas-resize-stroke')) {
        caughtCanvas = $(e.target).closest('.offcanvas');

        caughtCanvasOptions.width = caughtCanvas.width();
        caughtCanvasOptions.clientX = e.originalEvent.clientX;
    }
});

$(document).mouseup('.offcanvas-resizer', function (e) {
    if (caughtCanvas) {
        caughtCanvas = null;

        $.ajax({
            type: 'POST',
            url: route('setting.chat-off-canvas-width', {width: caughtCanvasGetWidth(e.originalEvent.clientX)}),
            success: (data) => {
            },
            error: (jqXHR, textStatus, errorThrown) => {
                showErrorPopupFromJqXHR(jqXHR);
            }
        });
    }
})

$(document).mousemove('.offcanvas-backdrop.show', function (e) {
    if (caughtCanvas) {
        caughtCanvas.width(caughtCanvasGetWidth(e.originalEvent.clientX));
    }
});
/**
 * Linkable elements
 */
$(document).on('click', '[data-link]', function (e) {
    e.stopPropagation();
    e.preventDefault();

    $('<form action="' + this.dataset.link + '" method="' + this.dataset.method + '"><input type="hidden" name="_token" value="' + getCsrfToken() + '"></form>')
        .appendTo($(document.body))
        .submit();
});

$(document).on('click', '.sortable .sort-link', function (e) {
    e.stopPropagation();
    e.preventDefault();

    document.querySelectorAll('.sortable .sort-link').forEach((link) => {
        link.classList.remove('active');
        link.classList.add('text-dark');
    });

    this.classList.add('active');
    this.classList.remove('text-dark');
});

$(document).on('click', '[data-action=delete]', function (e) {
    e.stopPropagation();
    e.preventDefault();

    showQuestionPopup(this.dataset.message ? this.dataset.message : trans('common.remove_this_item_question'), null, {
        confirmButtonText: trans('buttons.yes'),
        cancelButtonText: trans('buttons.cancel'),
        customClass: {
            confirmButton: 'swal2-danger-important',
        },
        onConfirmed: () => {
            $.ajax({
                type: 'DELETE',
                url: this.dataset.url,
                success: (data) => {
                    showSuccessPopup(this.dataset.messageSuccess ? this.dataset.messageSuccess : trans('common.operation_succeed'), null, {
                        onConfirmed: function () {
                            window.location.reload();
                        }
                    });
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    showErrorPopupFromJqXHR(jqXHR);
                }
            });
        }
    });
});

document.querySelectorAll('[data-input-mask="phone"]').forEach((input, r) => {
    $(input).val(input.getAttribute('value')).mask('+7 (999) 999-9999');
});

document.querySelectorAll('.page-size').forEach((select) => {
    pageSizeSelectLabel(select);

    select.addEventListener('change', (e) => {
        pageSizeSelectLabel(select);
    });
});

document.querySelectorAll('.width-parent').forEach((element) => {
    $(element).css('width', $(element).parent().css('width'));
});

$(document).on('click', '[data-swal-toggle]', function () {
    let self = $(this),
        message = self.data('message');

    if (typeof message === 'undefined') {
        message = $(self.data('swal-template')).html();
    }

    showPopup(self.data('title'), message, {
        ...{}, ...{
            icon: self.data('icon'),
        }
    });
});

$(document).on('click', '.notification-read', function () {
    let element = $(this);

    $.ajax({
        type: 'POST',
        url: route('notification.read', {notification: element.data('id')}),
        success: (data) => {
            element.removeClass('unread');

            if (data.data.unreadNotificationsCount === 0) {
                $('.pulse-notifications').hide();
            }
        },
        error: (jqXHR, textStatus, errorThrown) => {
            showErrorPopupFromJqXHR(jqXHR);
        }
    });
});
