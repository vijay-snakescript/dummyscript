import pdfjs from "@bundled-es-modules/pdfjs-dist/build/pdf";
import * as basicLightbox from 'basiclightbox'
import Sortable from 'sortablejs';
import Swal from 'sweetalert2';

pdfjs.GlobalWorkerOptions.workerSrc = "@bundled-es-modules/pdfjs-dist/build/pdf.worker.js";
const PDFApp = function () {
    const sweetPrompt = function (text, callback, options) {
        const defaults = {
            text: text,
            input: 'password',
            confirmButtonText: "Ok",
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            inputValidator: function (value) {
                if (!value) {
                    return "Password is required";
                }
            }
        };

        Swal.fire(defaults).then(function (result) {
            if (result.value && typeof callback === 'function') {
                callback(result)
                return
            }
        });
    };

    return {
        initPDFUpload: function (uploader, { filesGrid = null, previewPages = false, allowRotate = true, isSortable = false, allowPreview = true, maxFiles = 1, maxSize = null, fileExtensions = null, dropOnBody = false, fileMetadataCallback = false, allowProtectedFiles = true }, { extensionsError, sizeError, filesError, tooManyInvalidAttempts, fileNotSupported }, { filePasswordRequired = (file) => { }, fileHasPassword = (file) => { }, onFileChange = (files) => { }, onInitMeta = (meta) => { } }) {
            let filesList = [];
            let filesData = [];
            let pdfCounter = 0;
            let sortableInstance = null
            const fileSelect = uploader.querySelector('[type=file]');
            const fileDataInput = uploader.querySelector('.pdf_file__data');
            const uploaderDrag = uploader.querySelector('label.file-drag');
            const fileDrag = dropOnBody ? document.body : uploaderDrag;
            const uploadWrapper = uploader.querySelector('.uploader-wrapper')
            const pdfActions = document.querySelector('.pdf___more-actions')
            const filesPreview = !filesGrid ? uploader.querySelector('.files-grid') : document.querySelector(filesGrid)

            fileSelect.addEventListener('change', fileSelectHandler, false);
            var xhr = new XMLHttpRequest();
            if (allowRotate) {
                addRotateButtons()
            }

            if (xhr.upload) {
                fileDrag.addEventListener('dragover', fileDragHover, false);
                fileDrag.addEventListener('dragleave', fileDragHover, false);
                fileDrag.addEventListener('drop', fileSelectHandler, false);
            }

            function fileDragHover(event) {
                event.stopPropagation();
                event.preventDefault();

                event.type === 'dragover' ? uploaderDrag.classList.add('hover') : uploaderDrag.classList.remove('hover');
            }

            function fileSelectHandler(e) {
                fileDragHover(e);
                var files = e.target.files || e.dataTransfer.files;
                ([...files]).forEach(file => {
                    const fileIndex = parseFile(file)
                    if (fileIndex !== false) {
                        output(file, fileIndex)
                    }
                });
                updateFileList();
            }

            function isPdf(file) {
                var imageName = file.name;
                const regex = new RegExp(`(?=.pdf)`, 'gi')

                return regex.test(imageName);
            }

            function fileExtension(filename) {
                return filename.replace(/^.*?\.([a-zA-Z0-9]+)$/, "$1")
            }

            function isFileImage(file) {
                return file && file['type'].split('/')[0] === 'image';
            }

            function deleteFileFromList(index) {
                filesList.splice(index, 1)
                updateFileList()
                updateDataset()
            }

            async function output(file, fileNumber) {
                const index = filesList.findIndex(item => item.id == fileNumber);
                if (isPdf(file)) {
                    let passwordRetries = 0;
                    readFile(file, e => {
                        var typedarray = new Uint8Array(e.target.result);
                        const loadingTask = pdfjsLib.getDocument(typedarray);
                        loadingTask.onPassword = (updatePassword, reason) => {
                            if ((reason == 1 || reason == 2) && passwordRetries < 3 && allowProtectedFiles) {
                                if (fileHasPassword(file)) {
                                    deleteFileFromList(index)
                                    return
                                }
                                passwordRetries++
                                sweetPrompt(`Password for ${file.name}`, (password) => {
                                    filesList[index].password = password.value
                                    updatePassword(password.value);
                                })
                            } else {
                                if (passwordRetries > 3) {
                                    ArtisanApp.toastError(tooManyInvalidAttempts)
                                }
                                if (!allowProtectedFiles) {
                                    ArtisanApp.toastError(fileNotSupported)
                                }
                                deleteFileFromList(index)
                            }
                        };

                        loadingTask.promise.then(pdf => {
                            if (passwordRetries == 0) {
                                if (filePasswordRequired(file)) {
                                    deleteFileFromList(index)
                                    return;
                                }
                            }
                            pdf.getMetadata().then(function (metadata) {
                                if (typeof fileMetadataCallback === 'function') {
                                    if (onInitMeta(metadata)) {
                                        return;
                                    }
                                }
                            })

                            var pages = previewPages ? pdf.numPages : 1;
                            for (let currentPage = 1; currentPage <= pages; currentPage++) {
                                pdf.getPage(currentPage).then(function (page) {
                                    pdfCounter++;
                                    var div = document.createElement('div');
                                    div.className = `pdf-preview pdf-file-${fileNumber} position-relative is-loading grid-item-${pdfCounter}${(isSortable ? ' item-sortable' : '')}`
                                    div.bsToggle = 'tooltip'
                                    div.title = file.name
                                    div.dataset.id = fileNumber
                                    div.dataset.index = filesList.findIndex(item => item.id == fileNumber)
                                    div.dataset.page = previewPages ? currentPage : 'all'
                                    div.dataset.rotation = 0
                                    div.innerHTML = `<div class="item-actions">
                                                        <button type="button" class="btn btn-primary rounded-pill preview-item p-0 action-btn${!allowPreview ? ' d-none' : ''}"></button>
                                                        <button type="button" class="btn btn-primary rounded-pill rotate-left-item p-0 action-btn${!allowRotate ? ' d-none' : ''}"></button>
                                                        <button type="button" class="btn btn-primary rounded-pill rotate-right-item p-0 action-btn${!allowRotate ? ' d-none' : ''}"></button>
                                                        <button type="button" class="btn btn-danger rounded-pill delete-item p-0 action-btn"></button>
                                                    </div>
                                                    <div class="item-pdf d-flex align-items-center justify-content-center position-relative">
                                                        <div class="file-loader"><div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div></div>
                                                        <canvas class="item-canvas" id="pdf-preview-${fileNumber}-${currentPage}" width="150"></canvas>
                                                    </div>
                                                    <div class="content">
                                                        <p>${file.name}</p>
                                                        <span>${pdfCounter}</span>
                                                    </div>`;
                                    div.querySelector('.item-actions .delete-item').addEventListener('click', deleteFile)
                                    if (allowPreview) {
                                        div.querySelector('.item-actions .preview-item').addEventListener('click', previewPage)
                                    }
                                    if (allowRotate) {
                                        div.querySelector('.item-actions .rotate-left-item').addEventListener('click', rotateLeft)
                                        div.querySelector('.item-actions .rotate-right-item').addEventListener('click', rotateRight)
                                    }

                                    filesPreview.appendChild(div);
                                    new Tooltip(div, {})

                                    var canvas = document.getElementById(`pdf-preview-${fileNumber}-${currentPage}`);
                                    var page_scale = 145 / page.getViewport({ scale: 1 }).width
                                    var viewport = page.getViewport({ scale: page_scale });
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;
                                    canvas.style.height = viewport.height + 'px';
                                    canvas.style.width = viewport.width + 'px';

                                    page.render({ canvasContext: context, viewport: viewport }).promise.then(() => {
                                        div.classList.remove('is-loading')
                                        if (currentPage == pages) {
                                            updateDataset()
                                        }
                                    });
                                });
                            }
                        });
                    })
                } else {
                    var elementPreview = `<div class="item-actions">
                                                        <button type="button" class="btn btn-primary rounded-pill rotate-left-item p-0 action-btn${!allowRotate ? ' d-none' : ''}"></button>
                                                        <button type="button" class="btn btn-primary rounded-pill rotate-right-item p-0 action-btn${!allowRotate ? ' d-none' : ''}"></button>
                                                        <button type="button" class="btn btn-danger rounded-pill delete-item p-0 action-btn"></button>
                                                    </div>
                                                    <div class="item-pdf overflow-hidden d-flex align-items-center justify-content-center position-relative">
                                                        <div class="file-loader"><div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div></div>`;
                    if (isFileImage(file)) {
                        elementPreview += `<img class="item-canvas" src="${URL.createObjectURL(file)}" />`
                    } else {
                        elementPreview += `<i class="item-canvas an an-overview display-1 text-muted icon-${fileExtension(file.name)}"></i>`

                    }
                    elementPreview += `</div>
                                                    <div class="content">
                                                        <p>${file.name}</p>
                                                        <p class="fw-normal small mb-0">${ArtisanApp.humanFileSize(file.size)}</p>
                                                    </div>`;
                    var div = document.createElement('div');
                    div.className = `pdf-preview pdf-file-${fileNumber} position-relative grid-item-${fileNumber}${(isSortable ? ' item-sortable' : '')}`
                    div.bsToggle = 'tooltip'
                    div.title = file.name
                    div.dataset.id = fileNumber
                    div.dataset.index = filesList.findIndex(item => item.id == fileNumber)
                    div.dataset.page = 1
                    div.dataset.rotation = 0
                    div.innerHTML = elementPreview;
                    div.querySelector('.item-actions .delete-item').addEventListener('click', deleteFile)

                    if (allowRotate) {
                        div.querySelector('.item-actions .rotate-left-item').addEventListener('click', rotateLeft)
                        div.querySelector('.item-actions .rotate-right-item').addEventListener('click', rotateRight)
                    }

                    filesPreview.appendChild(div);
                    new Tooltip(div, {})
                }

                if (isSortable) {
                    sortableInstance = Sortable.create(filesPreview, {
                        onUpdate: () => {
                            updateDataset()
                        }
                    });
                }
            }

            function readFile(file, callback) {
                var fileReader = new FileReader();
                fileReader.onload = callback
                fileReader.readAsArrayBuffer(file);
            }

            function previewPage(event) {
                const item = event instanceof Event ? event.target.closest('.pdf-preview') : event
                Tooltip.getInstance(item).hide()
                const file = filesList[item.dataset.index]
                if (!file) {
                    return;
                }

                readFile(file.file, e => {
                    var typedarray = new Uint8Array(e.target.result);
                    const previewTask = pdfjsLib.getDocument(typedarray);
                    previewTask.onPassword = (updatePassword, reason) => {
                        if (reason == 1) {
                            updatePassword(file.password);
                        }
                    };
                    previewTask.promise.then(pdf => {
                        const pageNumber = parseInt(item.dataset.page, 10)
                        pdf.getPage(pageNumber).then(function (page) {
                            const width = (window.screen.availWidth / 1.5) > 500 ? 500 : (window.screen.availWidth / 1.5)
                            var page_scale = width / page.getViewport({ scale: 1 }).width
                            var viewport = page.getViewport({ scale: page_scale });

                            var canvas = document.createElement('canvas');
                            var context = canvas.getContext('2d')
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            canvas.style.height = viewport.height + 'px';
                            canvas.style.width = viewport.width + 'px';

                            const instance = basicLightbox.create(`<div class="bg-white shadow pdf-preview-lightbox" id="pdf-preview-lightbox" style="transform:rotate(${item.dataset.rotation}deg)"></div>`)
                            page.render({ canvasContext: context, viewport: viewport }).promise.then(() => {
                                instance.show()
                                document.querySelector('#pdf-preview-lightbox').appendChild(canvas)
                            });
                        })
                    })
                })
            }

            function rotateLeft(event) {
                var item = event instanceof Event ? event.target.closest('.pdf-preview') : event
                const currentRotation = parseInt(item.dataset.rotation)
                var rotation = (currentRotation <= 0) ? 270 : currentRotation - 90
                item.dataset.rotation = rotation
                item.querySelector('.item-canvas').style.transform = `rotate(${rotation}deg)`
                if (event instanceof Event) {
                    updateDataset()
                }
            }

            function rotateRight(event) {
                var item = event instanceof Event ? event.target.closest('.pdf-preview') : event
                const currentRotation = parseInt(item.dataset.rotation)
                var rotation = (currentRotation == 270) ? 0 : currentRotation + 90
                item.dataset.rotation = rotation
                item.querySelector('.item-canvas').style.transform = `rotate(${rotation}deg)`
                if (event instanceof Event) {
                    updateDataset()
                }
            }

            function deleteFile(event) {
                var item = event.target.closest('.pdf-preview')
                var id = item.dataset.id
                var index = filesList.findIndex(item => item.id == id);
                Tooltip.getInstance(item).dispose()
                item.remove()
                if (document.querySelectorAll(`.pdf-file-${id}`).length == 0) {
                    filesList.splice(index, 1)
                    updateFileList()
                }
                updateDataset()
            }

            function updateDataset() {
                pdfCounter = 0
                filesData = []
                document.querySelectorAll('.pdf-preview').forEach(element => {
                    var id = element.dataset.id
                    var index = filesList.findIndex(item => item.id == id);
                    element.dataset.index = index
                    var tmpData = JSON.parse(JSON.stringify(element.dataset))
                    delete tmpData.bsOriginalTitle
                    tmpData.password = filesList[index].password || false
                    filesData.push(tmpData)
                    pdfCounter++;
                    if (element.querySelector('.content span')) {
                        element.querySelector('.content span').innerHTML = pdfCounter
                    }
                });

                if (fileDataInput) {
                    fileDataInput.value = JSON.stringify(filesData)
                }
            }

            function updateFileList() {
                if (!filesGrid) {
                    if (filesList.length == 0) {
                        uploadWrapper.classList.remove('d-none')
                        uploader.querySelector('.add-more')?.classList.add("d-none");
                        uploader.querySelector('.uploader-after > .process-button')?.classList.add('d-none');
                        if (maxFiles > 1) {
                            uploaderDrag.classList.remove('pt-0')
                        }
                    } else {
                        uploadWrapper.classList.add('d-none');
                        uploader.querySelector('.uploader-after > .process-button')?.classList.remove('d-none');
                        if (maxFiles > 1) {
                            uploaderDrag.classList.add('pt-0')
                        }
                    }
                }

                const dataTransfer = new DataTransfer()
                filesList.forEach((item, index) => {
                    dataTransfer.items.add(item.file)
                });

                fileSelect.files = dataTransfer.files
                onFileChange(filesList)
            }

            function addRotateButtons() {
                const rotateActions = document.createElement('div')
                rotateActions.innerHTML = `<button type="button" class="btn btn-primary rotate-all-left"><i class="an an-flip-text"></i></button>
                                        <button type="button" class="btn btn-primary rotate-all-right"><i class="an an-flip-wording"></i></button>`;
                rotateActions.querySelector('.rotate-all-right').addEventListener('click', () => {
                    document.querySelectorAll('.pdf-preview').forEach(element => {
                        rotateRight(element)
                    });
                    updateDataset()
                })
                rotateActions.querySelector('.rotate-all-left').addEventListener('click', () => {
                    document.querySelectorAll('.pdf-preview').forEach(element => {
                        rotateLeft(element)
                    });
                    updateDataset()
                })
                pdfActions.appendChild(rotateActions);
            }

            function parseFile(file) {
                if (validation(file)) {
                    uploader.querySelector('.uploader-wrapper').classList.add("d-none");
                    uploader.querySelector('.uploader-error')?.classList.add("d-none");
                    if (validateMaxFiles(file)) {
                        if (maxFiles != 1) {
                            uploader.querySelector('.add-more')?.classList.remove("d-none");
                        }
                        const fileID = uuidv4()
                        filesList.push({ id: fileID, file: file })

                        return fileID
                    }

                    return false;
                } else {
                    uploader.querySelector('.uploader-error')?.classList.remove("d-none");
                    uploader.querySelector('label.file-drag').classList.remove("d-none");

                    return false
                }
            }

            function uuidv4() {
                return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                );
            }

            function validation(file) {
                return validateExtensions(file) && validateSize(file)
            }

            function validateExtensions(file) {
                var imageName = file.name;
                const regex = new RegExp(`(?=${fileExtensions})`, 'gi')
                const state = !fileExtensions || regex.test(imageName);
                if (!state) {
                    ArtisanApp.toastError(extensionsError)
                }

                return state
            }

            function validateSize(file) {
                const state = !maxSize || file.size <= maxSize * 1024 * 1024
                if (!state) {
                    ArtisanApp.toastError(sizeError)
                }

                return state;
            }

            function validateMaxFiles() {
                const state = !maxFiles || filesList.length < maxFiles;
                if (!state) {
                    ArtisanApp.toastError(filesError)
                }

                return state
            }
        }
    }
}();

window.PDFApp = PDFApp;
