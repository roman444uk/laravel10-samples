window.getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

window.trans = (key, replace = {}) => {
    var translation = key.split('.').reduce((t, i) => t[i] || null, window.i18n);

    for (var placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }
    return translation;
}

window.getCustomValue = (key, replace) => {
    var translation = key.split('.').reduce((t, i) => t ? (t[i] || null) : null, window.custom);

    for (var placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }
    return translation;
}

window.setCustomValue = (key, replace) => {
    key = typeof key === 'String' ? key.split('.') : key;

    let count = 1;

    key.reduce((data, property) => {
        if (property === key[key.length - 1] && count === key.length) {
            data[property] = replace;
        } else if (!data[property]) {
            data[property] = {};
        }

        count++;

        return data[property];
    }, window.custom);
}

window.redirect = (location = {}) => {
    window.location = location;
}

window.getFormFields = (form, needFields, excludeFields) => {
    let fields = {};

    $(form).serializeArray().forEach((fieldData) => {
        if (fieldData.name !== '_token' && ((needFields && needFields.length > 0 && needFields.indexOf(fieldData.name) === -1) || (excludeFields && excludeFields.indexOf(fieldData.name) > -1))) {
            return;
        }

        fields[fieldData.name] = fieldData.value;
    });

    return fields;
}

/**
 * Forms
 */
window.getFormDataFields = (form, onlyFields, excludeFields) => {
    let formData = new FormData();

    Array.from(form).forEach((input, inputIndex) => {
        if (['button', 'submit'].indexOf(input.type) > -1) {
            return;
        }

        if (onlyFields && onlyFields.length > 0 && onlyFields.indexOf(input.name) < 0) {
            return;
        }

        if (input.type === 'checkbox' && input.checked === false) {
            return;
        }

        if (input.type === 'file' && input.files.length > 0) {
            formData.append(input.name, input.files[0], input.files[0].name);
            return;
        }

        formData.append(input.name, input.value);
    });

    return formData;
}

/**
 * Params
 */
window.getFilterParams = (filterForm) => {
    let params = {};

    filterForm.find('.filter-field').each(function (index, input) {
        if ((input.type === 'radio' && input.checked) || (input.type !== 'radio' && input.value)) {
            params[input.name] = input.value;
        }
    });

    return params;
}

window.getUrlParams = (paramsNames)=> {
    var params = {};

    window.location.search.replace(/^\?/, '').split('&').forEach((entry) => {
        const [name, value] = entry.split('=');
        if (paramsNames.indexOf(name) > -1 && value) {
            params[name] = value;
        }
    });

    return params;
}

window.prepareParamsForUrl = (params) => {
    var urlParams = [];

    Object.entries(params).forEach((entry) => {
        const [name, value] = entry;
        if (value) {
            urlParams.push(name + '=' + value);
        }
    });

    return urlParams;
}

window.pushInHistory = (url) => {
    window.history.pushState([], '', url);
}

window.setPaginatorValueToObject = (object) => {
    let activePageLink = document.querySelector('.page-item.current .page-link');
    if (activePageLink && parseInt(activePageLink.innerHTML) > 1) {
        object.page = activePageLink.innerHTML;
    }
}

window.setSortingValueToObject = (object) => {
    let activeSortLink = document.querySelector('.sortable .sort-link.active');
    if (activeSortLink && activeSortLink.dataset.param && activeSortLink.dataset.desc && !activeSortLink.dataset.default) {
        object.sort = activeSortLink.dataset.param;
        object.desc = activeSortLink.dataset.desc;
    }
}

window.closeModalWithForm = (formElement) => {
    $(formElement.closest('.modal')).modal('hide');
    formElement.reset();
}

window.showPreloader = () => {
    $('.preloader').fadeIn(300);
}

window.hidePreloader = () => {
    $('.preloader').fadeOut(300);
}

/**
 * Loaders
 */
let activeLoader = null;
window.showLoader = (loaderId) => {
    $('#' + loaderId).fadeIn(300);
}
window.hideLoader = (loaderId) => {
    $('#' + loaderId).fadeOut(300);
}

/**
 * Modals
 */
window.hideParentModal = (element) => {
    $(element).closest('.modal').modal('hide');
}

/**
 * Button and links enabling/disabling
 */
window.enableButtons = (btnElements) => {
    btnElements.forEach((btnElement) => {
        enableButton(btnElement);
    });
};

window.disableButtons = (btnElements) => {
    btnElements.forEach((btnElement) => {
        disableButton(btnElement);
    });
};

window.enableButton = (btnElement) => {
    if (btnElement) {
        btnElement.disabled = false;
    }
};

window.disableButton = (btnElement) => {
    if (btnElement) {
        btnElement.disabled = true;
    }
};

/**
 * Button and links loaders
 */
let activeButtonLoader = null;
window.showButtonLoader = (btnElement) => {
    hideButtonLoader();
    activeButtonLoader = btnElement;

    activeButtonLoader.classList.add('loading');
    if (activeButtonLoader.tagName.toLowerCase() == 'button') {
        activeButtonLoader.disabled = true;
    }
};
window.hideButtonLoader = (options) => {
    if (!activeButtonLoader) {
        return;
    }

    activeButtonLoader.classList.remove('loading');
    if (activeButtonLoader.tagName.toLowerCase() == 'button') {
        if (!options || !options.leaveDisabled) {
            activeButtonLoader.disabled = false;
        }
    }

    activeButtonLoader = null;
};

/**
 * Popups
 */
window.showErrorPopupFromJqXHR = (jqXHR) => {
    if (jqXHR.responseJSON && jqXHR.responseJSON.errors && Object.entries(jqXHR.responseJSON.errors).length) {
        showErrorPopup(trans('validation.errors_while_filling_fields'), Object.values(jqXHR.responseJSON.errors));
    } else {
        showErrorPopup(trans('common.internal_server_error_occured'));
    }
}

window.showErrorPopupFromResponseData = (data) => {
    if (data.errors) {
        showErrorPopup(trans('validation.errors_while_filling_fields'), data.errors);
    } else {
        showErrorPopup(data.message ? data.message : trans('common.internal_server_error_occured'));
    }
}

window.showQuestionPopup = (title, messages, options) => {
    showPopup(title, messages, {
        ...options, ...{
            icon: 'question'
        }
    });
}

window.showSuccessPopup = (title, messages, options) => {
    showPopup(title, messages, {
        ...options, ...{
            icon: 'success',
            focusConfirm: true,
            willOpen: () => {
                focusActivePopup();
            },
            didOpen: () => {
                focusActivePopup();
            },
        }
    });

    // focusActivePopup();
}

window.showWarningPopup = (title, messages, options) => {
    showPopup(title, messages, {
        ...options, ...{
            icon: 'warning'
        }
    });
}

window.showDangerPopup = (title, messages, options) => {
    showPopup(title, messages, {
        ...options, ...{
            icon: 'danger'
        }
    });
}

window.showErrorPopup = (title, errorMessages, options) => {
    showPopup(title, errorMessages, {
        ...options, ...{
            icon: 'error',
            focusConfirm: true,
            didOpen: () => {
                focusActivePopup();
            },
        }
    });

    // focusActivePopup();
}

window.showPopup = (title, messages, options) => {
    let text = implodeArray(messages, '<br> - <br>');

    let onConfirmed = options.onConfirmed,
        onComplete = options.onComplete,
        onDenied = options.onDenied,
        onDismiss = options.onDismiss;

    delete options.onConfirmed, options.onComplete, options.onDenied, options.onDismiss;

    Swal.fire({
        ...options, ...{
            icon: options.icon,
            title: title,
            html: text,
            showCloseButton: getOptionValue(options, 'showCloseButton', true),
            showCancelButton: getOptionValue(options, 'showCancelButton', false) || getOptionValue(options, 'cancelButtonText', false),
            showDenyButton: getOptionValue(options, 'showDenyButton', false) || getOptionValue(options, 'denyButtonText', false),
            confirmButtonText: getOptionValue(options, 'confirmButtonText', trans('buttons.ok')),
            cancelButtonText: getOptionValue(options, 'cancelButtonText', trans('buttons.cancel')),
            denyButtonText: getOptionValue(options, 'denyButtonText', trans('buttons.deny')),
        }
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirmed && onConfirmed();
        } else if (result.isDenied) {
            onDenied && onDenied();
        } else if (result.isDismissed) {
            onDismiss && onDismiss();
        }

        onComplete && onComplete();
    })
}

window.focusActivePopup = (btnType) => {
    let backdrop = document.querySelector('.swal2-container.swal2-backdrop-show');
    if (backdrop) {
        let button = backdrop.querySelector('.swal2-' + (btnType ? btnType : 'confirm'));
        button ? button.focus() : null;
    }
}

/**
 * Select2
 */
window.reinitSelect2 = (element, newHtml, newValue) => {
    $(element).select2('destroy');
    $(element).replaceWith(newHtml);
    $('#' + $(element).attr('id')).val(newValue).select2();
}

/**
 * Datetime
 */
window.initDateTimePicker = (selector, options) => {
    let format = options && options.format ? options.format : 'DD.MM.Y';
    options = {
        ...{
            format: format,
            locale: 'ru',
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: 'fas fa-angle-right',
                previous: 'fas fa-angle-left'
            }
        }, ...options
    };

    $(selector).datetimepicker(options);

    $(selector).on('dp.change', function () {

    });
}

window.initTimePicker = (selector, options) => {
    options = {
        ...{
            format: 'HH:mm',
        }, ...options
    };

    initDateTimePicker(selector, options);
}

/**
 * Mask
 */
window.setInputMaskValue = (element, value) => {
    $(element).val(value).trigger('input');
}

/**
 * Modals
 */
window.setModalTitle = (modal, value) => {
    modal.querySelector('.modal-title').innerHTML = value;
}

/**
 *
 */
window.showFormModal = (modal, options) => {
    $(modal).modal('show');

    options && options.title ? setModalTitle(modal, options.title) : null;
}

window.setFormInputValue = (form, inputName, value) => {
    setInputValue(form.querySelector('[name="' + inputName + '"]'), value);
}

window.setFormInputDisabled = (form, inputName, disabled) => {
    let input = form.querySelector('[name="' + inputName + '"]');
    disabled = disabled === false ? disabled : true;

    console.log(disabled);

    input.disabled = disabled
}

window.setFormInputEnabled = (form, inputName) => {
    setFormInputDisabled(form, inputName, false);
}

window.getFormInputValue = (form, inputName) => {
    return getInputValue(form.querySelector('[name="' + inputName + '"]'));
}

window.setInputValue = (element, value) => {
    if (element) {
        element.value = value;
    }
}

window.selectInputRadio = (radioInputName, value) => {
    let element = document.querySelector(radioInputName + '[value="' + value + '"]');
    if (element) {
        element.checked = true;
    }
}

window.getInputValue = (element) => {
    return element ? element.value : null;
}

/**
 * Helpers functions
 */
window.getOptionValue = (options, propertyName, defaultValue) => {
    return options.hasOwnProperty(propertyName) ? options[propertyName] : defaultValue;
}

window.implodeArray = (messages, delimiter) => {
    if (typeof messages !== 'object') {
        return messages;
    }

    let text = [];
    for (const messageKey in messages) {
        if (!messages.hasOwnProperty(messageKey)) continue;
        text.push((typeof messages[messageKey]).toLowerCase() === 'string' ? messages[messageKey] : implodeArray(messages[messageKey], delimiter));
    }
    return text.join(delimiter);
}

window.mimeTypeValidate = (mimeType, acceptedMimeTypes) => {
    return acceptedMimeTypes.indexOf(mimeType) >= 0 || acceptedMimeTypes.indexOf(getMimeTypeComplex(mimeType) + '/*') >= 0;
}

window.getMimeTypeComplex = (mimeType) => {
    return mimeType.split('/')[0];
}

window.getFileExtension = (fineName) => {
    let parts = fineName.split('.');

    return parts[parts.length - 1];
}

window.getMimeTypeFromExtension = (extension = "txt") => {
    if (extension[0] === ".") {
        extension = extension.substr(1);
    }
    return {
        "aac": "audio/aac",
        "abw": "application/x-abiword",
        "arc": "application/x-freearc",
        "avchd": "video/avchd-stream",
        "avi": "video/x-msvideo",
        "azw": "application/vnd.amazon.ebook",
        "bin": "application/octet-stream",
        "bmp": "image/bmp",
        "bz": "application/x-bzip",
        "bz2": "application/x-bzip2",
        "cda": "application/x-cdf",
        "csh": "application/x-csh",
        "css": "text/css",
        "csv": "text/csv",
        "doc": "application/msword",
        "docx": "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "eot": "application/vnd.ms-fontobject",
        "epub": "application/epub+zip",
        "flv": "video/x-flv",
        "f4v": "video/x-flv",
        "gz": "application/gzip",
        "gif": "image/gif",
        "htm": "text/html",
        "html": "text/html",
        "ico": "image/vnd.microsoft.icon",
        "ics": "text/calendar",
        "jar": "application/java-archive",
        "jpeg": "image/jpeg",
        "jpg": "image/jpeg",
        "js": "text/javascript",
        "json": "application/json",
        "jsonld": "application/ld+json",
        "mid": "audio/midi audio/x-midi",
        "midi": "audio/midi audio/x-midi",
        "mjs": "text/javascript",
        "mkv": "video/x-matroska",
        "mov": "video/quicktime",
        "mp3": "audio/mpeg",
        "mp4": "video/mp4",
        "mpeg": "video/mpeg",
        "mpkg": "application/vnd.apple.installer+xml",
        "odp": "application/vnd.oasis.opendocument.presentation",
        "ods": "application/vnd.oasis.opendocument.spreadsheet",
        "odt": "application/vnd.oasis.opendocument.text",
        "oga": "audio/ogg",
        "ogv": "video/ogg",
        "ogx": "application/ogg",
        "opus": "audio/opus",
        "otf": "font/otf",
        "png": "image/png",
        "pdf": "application/pdf",
        "php": "application/x-httpd-php",
        "ppt": "application/vnd.ms-powerpoint",
        "pptx": "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        "rar": "application/vnd.rar",
        "rtf": "application/rtf",
        "sh": "application/x-sh",
        "svg": "image/svg+xml",
        "swf": "application/x-shockwave-flash",
        "tar": "application/x-tar",
        "tif": "image/tiff",
        "tiff": "image/tiff",
        "ts": "video/mp2t",
        "ttf": "font/ttf",
        "txt": "text/plain",
        "vsd": "application/vnd.visio",
        "wav": "audio/wav",
        "weba": "audio/webm",
        "webm": "video/webm",
        "webp": "image/webp",
        "wmv": "video/x-ms-wmv",
        "woff": "font/woff",
        "woff2": "font/woff2",
        "xhtml": "application/xhtml+xml",
        "xls": "application/vnd.ms-excel",
        "xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "xml": "application/xml",
        "xul": "application/vnd.mozilla.xul+xml",
        "zip": "application/zip",
        "3gp": "video/3gpp",
        "3g2": "video/3gpp2",
        "7z": "application/x-7z-compressed"
    }[extension.toLowerCase()] || "application/octet-stream";
}

/**
 * Sortable
 */
window.initSortableTable = (element, options) => {
    options.handle = 'td';
    initSortable(element.querySelector('tbody'), options);
}

window.initSortable = (element, options) => {
    $(element).sortable({
        handle: options.handle
    });

    $(element).sortable().bind('sortupdate', function (e, ui) {
        options.onSortUpdate && options.onSortUpdate();
    });
}

/**
 * Da data
 */
window.initDaData = (element, options) => {
    $(element).suggestions({
        token: "4f258bbe7b9acebef863c8d222a986451dba1627",
        type: options.type,
        onSelect: function (suggestion) {
            options && options.onSelect && options.onSelect(suggestion);
        }
    });
}

window.initDaDataAddress = (element, options) => {
    initDaData(element, {
        ...options, ...{type: 'ADDRESS'}
    })
}

window.initDaDataParty = (element, options) => {
    initDaData(element, {
        ...options, ...{type: 'PARTY'}
    })
}
