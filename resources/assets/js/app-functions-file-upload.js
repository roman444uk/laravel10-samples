import {FileHelper} from "../../js/Helpers/FileHelper.js";

/**
 * Files uploading
 *
 * Config example:
 * https://github.com/johndatserakis/file-upload-with-preview/blob/9d6bd7ce29e932bc907e179e357383d7a821a6aa/example/index.ts
 */
const filesUploadWithPreview = {};

let uploaderLightBoxImages = null;

window.isFileUploadWithPreviewMultiple = (fileUpload) => {
    return fileUpload.el.classList.contains('multiple');
}

window.collectFileUploadDatasetOptions = (element, initOptions) => {
    let dataset = element.dataset;

    let options = {
        fileType: getOptionValue(dataset, 'fileType'),
        instantUploading: [1, '1', true].indexOf(getOptionValue(dataset, 'instantUploading', '1')) >= 0 ? true : false,
        owner: getOptionValue(dataset, 'owner'),
        ownerId: getOptionValue(dataset, 'ownerId'),
        presetFiles: [],
        maxFileSize: getOptionValue(dataset, 'maxFileSize', 100 * 1024 * 1024),
        mimeTypes: [],
        disabled: dataset.disabled === '1' ? true : false,
    };

    let originFileArray = [];

    if (element.dataset.mimeTypes) {
        JSON.parse(element.dataset.mimeTypes).forEach((mimeType) => {
            options.mimeTypes.push(mimeType);
        });
    }

    if (element.dataset.presetFiles) {
        JSON.parse(element.dataset.presetFiles).forEach((presetFile) => {
            originFileArray.push(presetFile);
            options.presetFiles.push(presetFile.path.replace('http:', 'https:'));
        });
    }

    if (initOptions.presetFiles && initOptions.presetFiles.length > 0) {
        initOptions.presetFiles = initOptions.presetFiles.map((presetFile) => {
            originFileArray.push(presetFile);

            return presetFile.url.replace('http:', 'https:')
        });
    }

    return [options, originFileArray]
}

window.initFileUploadWithPreview = (uploadId, options) => {
    let [
        optionsFromData, originFileArray
    ] = collectFileUploadDatasetOptions(document.querySelector('[data-upload-id="' + uploadId + '"]'), options);

    options = {...optionsFromData, ...options};

    let fileUpload = new FileUploadWithPreview(uploadId, options);
    fileUpload.originFileArray = originFileArray;

    if (options.presetFiles && options.presetFiles.length > 0) {
        fileUpload.el.classList.add('has-files');

        if (!isFileUploadWithPreviewMultiple(fileUpload)) {
            fileUpload.el.dataset.fileId = options.presetFiles[0].id;
        }
    } else {
        fileUpload.el.classList.remove('has-files');

        // if (!isFileUploadWithPreviewMultiple(fileUpload)) {
        fileUpload.el.dataset.fileId = '';
        // }
    }

    filesUploadWithPreview[uploadId] = fileUpload;
    setFileUploadWithPreviewDisabled(uploadId, options.disabled === true);

    return filesUploadWithPreview[uploadId];
}

window.getFileUploadWithPreview = (uploadId) => {
    return filesUploadWithPreview[uploadId]
}

window.getFileUploadWithPreviewLastFile = (uploadId) => {
    let fileUpload = getFileUploadWithPreview(uploadId);

    return fileUpload.cachedFileArray.length > 0
        ? fileUpload.cachedFileArray[fileUpload.cachedFileArray.length - 1]
        : null;
}

window.setFileUploadWithPreviewOptions = (uploadId, options) => {
    let fileUpload = getFileUploadWithPreview(uploadId);
    fileUpload.options = {...fileUpload.options, ...options};
}

window.setFileUploadWithPreviewUploadingProgress = (uploadId, options) => {
    let fileUpload = getFileUploadWithPreview(uploadId);

    options.progress = Math.floor(options.progress);

    if (!isFileUploadWithPreviewMultiple(fileUpload)) {
        if (options.progress >= 0 && options.progress < 100) {
            fileUpload.el.classList.add('uploading');
            fileUpload.el.querySelector('.custom-file-container__image-preview').innerHTML = '<div class="uploading-progress">' + options.progress + '%</div>';
        } else {
            fileUpload.el.classList.remove('uploading');
            fileUpload.el.querySelector('.custom-file-container__image-preview').innerHTML = '';
        }
    } else {
        setTimeout(() => {
            let multiPreview = fileUpload.el.querySelector('.custom-file-container__image-multi-preview[data-upload-token="' + options.file.token + '"]'),
                uploadingProgress = multiPreview.querySelector('.uploading-progress');
            if (options.progress >= 0 && options.progress < 100) {
                multiPreview.classList.add('uploading');
                uploadingProgress.innerHTML = options.progress + '%';
            } else {
                multiPreview.classList.remove('uploading');
                uploadingProgress.innerHTML = '';
            }
        }, 100);
    }
}

window.setFileUploadWithPreviewDisabled = (uploadId, disabled) => {
    let fileUpload = getFileUploadWithPreview(uploadId);

    disabled ? fileUpload.el.classList.add('disabled') : fileUpload.el.classList.remove('disabled');
}

window.setFileUploadWithPreviewDeletingProgress = (uploadId, progress, file) => {
    let fileUpload = getFileUploadWithPreview(uploadId);

    if (!isFileUploadWithPreviewMultiple(fileUpload)) {
        progress ? fileUpload.el.classList.add('uploading') : fileUpload.el.classList.remove('uploading');
    } else {
        let multiPreview = getMultiPreview(fileUpload, file.token)
        progress ? multiPreview.classList.add('uploading') : multiPreview.classList.remove('uploading');
    }
}

window.syncOriginAndCachedFiles = (fileUpload, index) => {
    let originFile = fileUpload.originFileArray[index];

    fileUpload.cachedFileArray[index].filename = originFile.filename;
    fileUpload.cachedFileArray[index].path = originFile.path;
}

window.fileAdd = (e, currentFileIndex) => {
    let uploadId = e.detail.uploadId,
        fileUpload = getFileUploadWithPreview(uploadId),
        options = fileUpload.options;

    let cachedFile = fileUpload.cachedFileArray[currentFileIndex];

    options.onImagesAdded && options.onImagesAdded(fileUpload, cachedFile);

    if (isFileUploadWithPreviewMultiple(fileUpload)) {
        let imagePreview = fileUpload.el.querySelector('.custom-file-container__image-preview');
        let addIcon = imagePreview.querySelector('.custom-file-container__image-multi-preview--add');
        addIcon ? addIcon.remove() : null;
    }

    fileUpload.el.classList.add('has-files');

    if (options.instantUploading) {
        let formData = new FormData(),
            uploadOptions = {
                progress: 0, file: cachedFile
            };
        formData.append('file', cachedFile);

        setFileUploadWithPreviewUploadingProgress(uploadId, {...uploadOptions, ...{progress: 0}});

        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();

                xhr.upload.addEventListener('progress', function (evt) {
                    if (evt.lengthComputable) {
                        setFileUploadWithPreviewUploadingProgress(uploadId, {...uploadOptions, ...{progress: (evt.loaded / evt.total * 100) - 1}});
                    }
                }, false);

                xhr.addEventListener('progress', function (evt) {
                    if (evt.lengthComputable) {
                        setFileUploadWithPreviewUploadingProgress(uploadId, {...uploadOptions, ...{progress: (evt.loaded / evt.total * 100) - 1}});
                    }
                }, false);

                return xhr;
            },
            type: 'POST',
            url: route('file.store', {
                owner: fileUpload.el.dataset.fileOwner,
                owner_id: fileUpload.el.dataset.fileOwnerId,
                type: fileUpload.el.dataset.fileType
            }),
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                setFileUploadWithPreviewUploadingProgress(uploadId, {...uploadOptions, ...{progress: 100}});

                let file = {...data.data.file, token: cachedFile.token};

                fileUpload.originFileArray[currentFileIndex] = {
                    id: file.id,
                    path: file.url,
                    token: cachedFile.token,
                    filename: file.name,
                    previews: file.previews,
                };
                // fileUpload.originFileArray.push({
                //     id: file.id,
                //     path: file.url,
                //     token: cachedFile.token,
                //     filename: file.name,
                //     previews: file.previews,
                // });

                syncOriginAndCachedFiles(fileUpload, currentFileIndex);

                if (!isFileUploadWithPreviewMultiple(fileUpload)) {
                    fileUpload.el.dataset.fileId = file.id;
                }

                fileUpload.el.classList.add('has-files');

                iconClearShow(fileUpload);
                if (['gif', 'jpg', 'jpeg', 'png'].indexOf(file.ext) > -1) {
                    setTimeout(() => {
                        // Initialize lightbox only once when loading has been completed
                        let initUploaderLightBox = fileUpload.originFileArray.length === fileUpload.cachedFileArray.length;

                        iconZoomShow(fileUpload, file, initUploaderLightBox);
                    }, 300);
                }

                options.onImagesAddedAndUploaded && options.onImagesAddedAndUploaded(fileUpload, cachedFile, data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                showErrorPopupFromJqXHR(jqXHR);
            }, complete: function () {

            }
        });
    }
}

document.querySelectorAll('[data-upload-id]').forEach((element) => {
    initFileUploadWithPreview(element.dataset.uploadId, {});
});

$(document).on('click', '.custom-file-container__image-preview', function (e) {
    // single uploader
    let containerSingle = $(this).closest('.custom-file-container.single').get(0);
    if (containerSingle) {
        let preview = containerSingle.querySelector('.custom-file-container__image-preview'),
            zoomLink = containerSingle.querySelector('.custom-file-container__image-multi-preview__single-image-zoom'),
            fileUpload = getFileUploadWithPreview(containerSingle.dataset.uploadId);

        // open file browser window
        if (!containerSingle.classList.contains('disabled')
            && !preview.classList.contains('custom-file-container__image-preview--active')
            && !fileUpload.options.disabled
        ) {
            containerSingle.querySelector('.custom-file-container__custom-file').click();
        }
        // zoom file
        if (zoomLink && preview.classList.contains('custom-file-container__image-preview--active')
            && zoomLink.getAttribute('href')
        ) {
            zoomLink.click();
        }
    }

    // Multi uploader
    let containerMultiple = $(this).closest('.custom-file-container.multiple').get(0);
    if (containerMultiple) {
        let preview = containerMultiple.querySelector('.custom-file-container__image-preview'),
            previewMulti = containerMultiple.querySelector('.custom-file-container__image-multi-preview'),
            fileUpload = getFileUploadWithPreview(containerMultiple.dataset.uploadId);

        if (!fileUpload.options.disabled && (
            e.target.classList.contains('custom-file-container__image-preview')
            || e.target.classList.contains('custom-file-container__image-multi-preview--add')
        )) {
            containerMultiple.querySelector('.custom-file-container__custom-file').click();
        }

        if (e.target.classList.contains('custom-file-container__image-multi-preview') &&
            !e.target.classList.contains('custom-file-container__image-multi-preview--add')
        ) {
            let zoomLink = e.target.querySelector('.custom-file-container__image-multi-preview__single-image-zoom');
            if (zoomLink) {
                zoomLink.click();
            }
        }
    }
})

window.addEventListener('fileUploadWithPreview:validationError', (e) => {
    let fileUpload = getFileUploadWithPreview(e.detail.uploadId),
        messages = [];

    e.detail.errors.forEach((error) => {
        let message;
        switch (error.type) {
            case 'maxFileSize':
                message = trans('validation.file_with_name_size_exceeded', {
                    file: error.file.name,
                    size: FileHelper.getSizeHuman(error.file.size),
                    maxSize: FileHelper.getSizeHuman(fileUpload.options.maxFileSize),
                });
                break;
            case 'mimeTypes':
                message = trans('validation.file_with_name_wrong_extension', {
                    ext: getFileExtension(error.file.name),
                });
                break;
        }
        messages.push(message);
    });

    showErrorPopup(trans('validation.errors_while_files_uploading'), messages);
});

// Internal `imagesAdded` event works for both multiple and single file uploading
window.addEventListener('fileUploadWithPreview:imagesAdded', (e) => {
    let uploadId = e.detail.uploadId,
        fileUpload = getFileUploadWithPreview(uploadId),
        options = fileUpload.options;

    // Initiate uploader first time
    if (fileUpload.originFileArray.length === e.detail.cachedFileArray.length) {
        fileUpload.cachedFileArray.forEach((cachedFile, index) => {
            fileUpload.originFileArray[index].token = cachedFile.token;

            syncOriginAndCachedFiles(fileUpload, index);
        });

        if (isFileUploadWithPreviewMultiple(fileUpload)) {
            updateZoomIcons(fileUpload)
        }

        return;
    }

    let currentFileIndex = fileUpload.originFileArray.length;
    do {
        fileAdd(e, currentFileIndex);
    } while (e.detail.cachedFileArray[++currentFileIndex]);
});

// Internal `imageDeleted` event works only for multiple file uploading
window.addEventListener('fileUploadWithPreview:imageDeleted', (e) => {
    let fileUpload = getFileUploadWithPreview(e.detail.uploadId), options = fileUpload.options;

    let removeIndex = null;
    fileUpload.originFileArray.forEach((originFile, index) => {
        if (removeIndex === null && (
            !fileUpload.cachedFileArray[index] || originFile.token !== fileUpload.cachedFileArray[index].token
        )) {
            removeIndex = index;
        }
    });

    if (isFileUploadWithPreviewMultiple(fileUpload)) {
        updateZoomIcons(fileUpload)
    }

    let file = fileUpload.originFileArray.splice(removeIndex, 1)[0];

    if (file && options.instantUploading) {
        // setFileUploadWithPreviewDeletingProgress(fileUpload.uploadId, true, file);

        ajaxFileDestroy(file.id, {
            onSuccess: (data) => {
                if (!isFileUploadWithPreviewMultiple(fileUpload)) {
                    iconClearHide(fileUpload);
                }

                initUploaderLightBoxImages();
                // setFileUploadWithPreviewDeletingProgress(fileUpload.uploadId, false, file);

                options.onImageDestroyed && options.onImageDestroyed(fileUpload, file, data);
            }, onComplete: () => {

            }
        });
    }

    options.onImageDeleted && options.onImageDeleted(fileUpload, file);
});

// Internal `imageDeleted` event works only for multiple file uploading, that's why handle it different way
$(document).on('click', '.custom-file-container.single .custom-file-container__image-multi-preview__single-image-clear', function (e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    e.preventDefault();

    let uploadId = $(e.target).closest('[data-upload-id]').data('upload-id'),
        fileUpload = getFileUploadWithPreview(uploadId),
        options = fileUpload.options;

    if (!fileUpload.el.dataset.fileId) {
        fileUpload.clearButton.dispatchEvent(new Event('click'));
        fileUpload.el.classList.remove('has-files');

        return;
    }

    let file = fileUpload.originFileArray.splice(0, 1)[0];

    setFileUploadWithPreviewDeletingProgress(uploadId, true);

    ajaxFileDestroy(file.id, {
        onSuccess: (data) => {
            fileUpload.clearButton.dispatchEvent(new Event('click'));
            fileUpload.el.classList.remove('has-files');

            iconClearHide(fileUpload);
            iconZoomHide(fileUpload);

            options.onImageDestroyed && options.onImageDestroyed(fileUpload, file, data);
        }, onComplete: () => {
            setFileUploadWithPreviewDeletingProgress(uploadId, false);
        }
    });
})

window.ajaxFileDestroy = (fileId, options) => {
    $.ajax({
        type: 'DELETE', url: route('file.destroy', {
            file: fileId
        }), success: function (data) {
            options && options.onSuccess && options.onSuccess(data);
        }, error: function (jqXHR, textStatus, errorThrown) {
            options && options.onError && options.onError();

            showErrorPopupFromJqXHR(jqXHR);
        }, complete: function () {
            options && options.onComplete && options.onComplete();
        }
    });
}

/**
 * Icons
 */
window.getIconClear = (fileUpload) => {
    return fileUpload.el.querySelector('.custom-file-container__image-multi-preview__single-image-clear');
}

window.iconClearShow = (fileUpload) => {
    let iconClear = getIconClear(fileUpload);
    iconClear ? iconClear.classList.remove('d-none') : null;
}

/*window.iconZoomAdd = (fileUpload, file) => {
    fileUpload.originFileArray.forEach((originFile, index) => {
        if (!file || originFile.id === file.id) {
            let cachedFile = fileUpload.cachedFileArray[index],
                container = fileUpload.el.querySelector('.custom-file-container__image-multi-preview[data-upload-token="' + originFile.token + '"]');


            // if (container) {
            //     container.innerHTML = container.innerHTML.concat(`
            //         <a class="custom-file-container__image-multi-preview__single-image-zoom uploader-light-box-image"
            //            href="${originFile.previews['100x'].url}"
            //            data-title="${originFile.filename}"
            //            data-description="${originFile.filename}"
            //         >
            //             <span class="custom-file-container__image-multi-preview__single-image-zoom__icon">
            //                 <i class="fa feather-zoom-in"></i>
            //             </span>
            //         </a>
            //     `);
            // }
        }
    });
}*/

window.iconClearHide = (fileUpload) => {
    if (!isFileUploadWithPreviewMultiple(fileUpload)) {
        let iconClear = getIconClear(fileUpload);
        iconClear ? iconClear.classList.add('d-none') : null;

        let iconClearCustom = fileUpload.el.querySelector('.custom-file-container__image-clear-custom');
        iconClearCustom ? iconClearCustom.classList.add('d-none') : null;
    }
}

window.iconZoomShow = (fileUpload, file, initUploaderLightBox) => {
    let iconZoom;

    if (!isFileUploadWithPreviewMultiple(fileUpload)) {
        iconZoom = fileUpload.el.querySelector('.custom-file-container__image-multi-preview__single-image-zoom');
    } else {
        let multiPreview = fileUpload.el.querySelector('.custom-file-container__image-multi-preview[data-upload-token="' + file.token + '"]');
        iconZoom = multiPreview.querySelector('.custom-file-container__image-multi-preview__single-image-zoom');
    }

    if (iconZoom) {
        iconZoom.setAttribute('href', file.path ? file.path : file.url);
        iconZoom.setAttribute('data-title', file.filename ? file.filename : file.name);
        iconZoom.setAttribute('data-description', file.filename ? file.filename : file.name);
        iconZoom.classList.add('uploader-light-box-image');

        iconZoom.parentElement.setAttribute('href', file.previews['100x'].url);
    }

    if (initUploaderLightBox) {
        initUploaderLightBoxImages();
    }
}

window.iconZoomHide = (fileUpload) => {
    let iconZoom = fileUpload.el.querySelector('.custom-file-container__image-multi-preview__single-image-zoom');

    if (iconZoom && !isFileUploadWithPreviewMultiple(fileUpload)) {
        iconZoom.classList.add('d-none');
        iconZoom.classList.remove('uploader-light-box-image');
        iconZoom.parentElement.setAttribute('href', '')

        initUploaderLightBoxImages();
    }
}

window.updateZoomIcons = (fileUpload) => {
    setTimeout(() => {
        fileUpload.originFileArray.forEach((file) => {
            iconZoomShow(fileUpload, file, false);
        });
    }, 100);

    setTimeout(() => {
        initUploaderLightBoxImages();
    }, 150);
}

window.getMultiPreview = (fileUpload, token) => {
    return fileUpload.el.querySelector('.custom-file-container__image-multi-preview[data-upload-token="' + token + '"]');
}

/**
 * Lightbox
 */
window.initUploaderLightBoxImages = () => {
    if (uploaderLightBoxImages) {
        uploaderLightBoxImages.destroy();
    }

    setTimeout(() => {
        uploaderLightBoxImages = GLightbox({
            selector: '.uploader-light-box-image'
        });
    }, 300);
}
