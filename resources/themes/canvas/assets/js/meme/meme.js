import './gif.js'
import './gif-frames.min.js'

(function MemeGenerator() {
    'use strict';
    const memeResult = document.getElementById('memeResult');
    const canvas = document.getElementById('canvas');
    const canvasPlaceholder = document.getElementById('canvasPlaceholder');
    const ctx = canvas.getContext('2d');
    const addTextboxBtn = document.getElementById('addTextboxBtn');
    const inputsContainer = document.getElementById('inputsContainer');
    const generateMemeBtn = document.getElementById('generateMemeBtn');
    const downloadMemeBtn = document.getElementById('downloadMemeBtn');
    const downloadMemePreview = document.getElementById('downloadMemePreview');
    const shareSection = document.getElementById('shareSection');
    const shareBtn = document.getElementById('shareBtn');
    const gifImage = document.getElementById('gifImage');
    const loading = document.getElementById('loading');
    const resultLoading = document.getElementById('result-loading');
    const showGif = document.getElementById('show_gif');
    const canvasCont = document.getElementById('canvas-cont');
    const canvasImage = canvasCont.querySelector('.canvas-image');
    let selectedImage = null;

    const defaultOptions = {
        text: '',
        fillColor: '#ffffff',
        shadowColor: '#000000',
        font: 'Impact',
        fontSize: 40,
        textAlign: 'center',
        shadowBlur: 3,
        offsetY: 0,
        offsetX: 0,
        allCaps: true
    };

    var frames = [];
    var saveFrames = [];
    var gWidth = 0;
    var gHeight = 0;
    var gifImg = false;
    var data = null;
    var showGifB = false;

    const options = [
        Object.assign({}, defaultOptions),
        Object.assign({}, defaultOptions)
    ];


    function makeGif() {
        var gif = new GIF({
            workers: 2,
            quality: 8,
            workerScript: '/themes/default/js/gif.worker.js'
        });

        // or a canvas element
        for (var i = 0; i < saveFrames.length; i++) {
            gif.addFrame(saveFrames[i].frame, { delay: saveFrames[i].delay });
        }

        gif.on('finished', function (blob) {
            if (!gifImg) {
                return;
            }
            var url = URL.createObjectURL(blob);
            downloadMemePreview.src = url;
            downloadMemePreview.addEventListener('load', function (evt) {
                resultLoading.classList.remove('d-flex');
                resultLoading.classList.add('d-none');
                downloadMemePreview.classList.remove('d-none');
                downloadMemeBtn.classList.remove('d-none');
                downloadMemeBtn.download = 'meme.png';
                downloadMemeBtn.href = url;
            });
        });

        gif.render();
    }

    function downloadGif() {
        resultLoading.classList.add('d-flex');
        resultLoading.classList.remove('d-none');
        showGifB = true;
        var tot = frames.length;
        saveFrames = [];
        for (var i = 0; i < tot; i++) {
            var oCanvas = document.createElement('canvas');
            var octx = oCanvas.getContext('2d');
            var w = oCanvas.width = gWidth;
            var h = oCanvas.height = gHeight;
            var frame = frames[i].frame;
            octx.clearRect(0, 0, w, h);
            octx.drawImage(frame, 0, 0, w, h);
            octx.drawImage(canvas, 0, 0);
            saveFrames.push({ frame: oCanvas, delay: frames[i].delay });
        }
        makeGif();
    }

    function drawGifText() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        options.forEach(function (item, index) {
            ctx.font = `${item.fontSize}px ${item.font}`;

            const multiplier = index + 1;
            const lineHeight = ctx.measureText('M').width + 20;
            const xPos = item.textAlign === 'center' || !item.textAlign ? canvas.width / 2 : item.textAlign === 'left' ? 0 : canvas.width;
            const shadowBlur = !Number.isNaN(Number(item.shadowBlur)) ? Number(item.shadowBlur) : 3;
            const text = item.allCaps === true ? item.text.toUpperCase() : item.text;

            ctx.fillStyle = item.fillColor;
            ctx.textAlign = item.textAlign;
            ctx.save();

            if (shadowBlur !== 0) {
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 0;
                ctx.shadowBlur = shadowBlur;
                ctx.shadowColor = item.shadowColor;
            }

            ctx.fillText(
                text,
                xPos + Number(item.offsetX),
                index === 1 ? canvas.height - 20 + Number(item.offsetY) : lineHeight * (multiplier - 1 || 1) + Number(item.offsetY)
            );

            ctx.restore();
        });
    }

    function loadGif() {
        showGif.classList.remove('d-none');
        drawGifText();
        gifFrames({
            url: data,
            frames: 'all',
            outputType: 'canvas',
            cumulative: true
        }, function (err, frameData) {
            if (err) {
                throw err;
            }
            frames = [];
            frameData.forEach(function (frame) {
                frames.push({ frame: frame.getImage(), delay: frame.frameInfo.delay * 10 });
                return 0;
            });
        });
    }

    function resetDownloadGif() {
        showGifB = false;

        resultLoading.classList.remove('d-flex');
        resultLoading.classList.add('d-none');

        downloadMemeBtn.classList.add('d-none');
        downloadMemeBtn.download = '';
        downloadMemeBtn.href = '';

        downloadMemePreview.classList.add('d-none');
        downloadMemePreview.src = '';
        downloadMemePreview.onload = function () { };
    }

    function resetGif() {
        gifImage.src = "";
    }

    function showError(message) {
        ArtisanApp.toastError(message)
    }

    function generateMeme() {
        resetDownloadGif();
        if (gifImg) {
            downloadGif();
        } else {
            const downloadLink = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
            downloadMemeBtn.classList.remove('d-none');
            downloadMemeBtn.download = 'meme.png';
            downloadMemeBtn.href = downloadLink;
            downloadMemePreview.src = downloadLink;
            downloadMemePreview.classList.remove('d-none');
        }

        memeResult.classList.remove('d-none')
        setTimeout(() => document.querySelector(".meme-results-wrapper").scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
    }

    function setCanvasWidth(w, h) {
        const MAX_CONTAINER = 600;
        const MAX_WIDTH = 1000;
        const MAX_HEIGHT = 1000;
        let width = w;
        let height = h;

        if (width > height) {
            if (width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
            }

        } else {
            if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
            }
        }
        let cwidth = width;
        let cheight = height;
        if (width > MAX_CONTAINER) {
            cheight *= MAX_CONTAINER / cwidth;
            cwidth = MAX_CONTAINER;
        }
        canvas.width = gWidth = width;
        canvas.height = gHeight = height;
        canvas.style.width = cwidth + "px";
        canvas.style.height = cheight + "px";
        canvasImage.style.width = cwidth + "px";
        canvasImage.style.height = cheight + "px";
    }

    function onImageLoaded(evt) {
        setCanvasWidth(evt.target.width, evt.target.height);

        if (gifImg) {
            loadGif();
        } else {
            draw(evt.target);
        }

        selectedImage = evt.target;
        canvasCont.classList.remove('d-none');
        loading.classList.add('d-none');
    }

    function handleFileSelect(files) {
        if (selectedImage) return;
        resetGif();

        const file = files[0];

        var fileName = file.name;
        var fileExt = fileName.substr(fileName.lastIndexOf('.') + 1);
        gifImg = fileExt == 'gif';

        canvasPlaceholder.classList.add('d-none');
        loading.classList.add('d-flex');
        loading.classList.remove('d-none');
        const image = gifImg ? gifImage : new Image();
        const reader = new FileReader();
        reader.addEventListener('load', function (evt) {
            data = evt.target.result;
            image.addEventListener('load', onImageLoaded);
            image.src = data;
        });

        reader.readAsDataURL(file);
    }

    function handleTextPropChange(element, index, prop) {
        options[index][prop] = element.type === 'checkbox' ? element.checked : element.value;
        if (gifImg) {
            drawGifText();
        } else {
            draw(selectedImage);
        }
    }

    function createNewInput(index) {
        const inputTemplate = `
    <div class="input-group input-group-lg mb-3 mt-lg-0 mt-sm-3">
      <input class="form-control" type="text" data-input="text" autocomplete="off" placeholder="${index === 0 ? 'Top Text' : index === 1 ? 'Bottom Text' : `Text #${index + 1}`}">
      <button class="btn btn-outline-secondary dropdown-toggle" tabindex="-1" data-button="settings" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <svg width="22" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.8529 4.81589C12.9434 4.92564 12.992 5.06437 12.9899 5.20706V6.87527C12.992 7.01796 12.9434 7.15669 12.8529 7.26644C12.7676 7.37975 12.647 7.4608 12.5104 7.49654L11.8367 7.68062C11.7728 7.70092 11.7141 7.73515 11.6648 7.78088C11.6154 7.82661 11.5767 7.88272 11.5513 7.94523C11.5203 8.00569 11.5041 8.07276 11.5041 8.14082C11.5041 8.20888 11.5203 8.27595 11.5513 8.3364L11.8938 8.95767C11.9659 9.07855 11.9941 9.22084 11.9738 9.36034C11.96 9.49689 11.9041 9.62566 11.8139 9.7285L10.6722 10.879C10.5741 10.9878 10.4403 11.0573 10.2954 11.0746C10.1572 11.0885 10.018 11.0604 9.89576 10.994L9.2678 10.6489C9.2078 10.6176 9.14124 10.6013 9.0737 10.6013C9.00615 10.6013 8.93959 10.6176 8.8796 10.6489C8.82065 10.6661 8.76588 10.6955 8.71872 10.7351C8.67157 10.7747 8.63305 10.8237 8.60558 10.879L8.43431 11.5463C8.406 11.6872 8.32916 11.8135 8.21738 11.9029C8.10557 11.9875 7.96895 12.032 7.82918 12.0295H6.16222C6.02218 12.0339 5.88498 11.9891 5.77402 11.9029C5.65871 11.8148 5.57787 11.6885 5.54567 11.5463L5.36299 10.879C5.34887 10.8121 5.31855 10.7498 5.27472 10.6976C5.23089 10.6454 5.17492 10.605 5.1118 10.5799C5.04237 10.5611 4.96975 10.5575 4.89884 10.5694C4.82794 10.5813 4.76039 10.6084 4.70077 10.6489L4.11847 11.0056C3.99621 11.0719 3.85705 11.1 3.71885 11.0861C3.56828 11.0685 3.42975 10.9946 3.33066 10.879L2.1889 9.7285C2.08816 9.62568 2.02394 9.49222 2.00622 9.34884C1.9805 9.20925 2.00912 9.06505 2.08614 8.94617L2.42867 8.34791C2.45914 8.28319 2.47494 8.21245 2.47494 8.14082C2.47494 8.06919 2.45914 7.99845 2.42867 7.93373C2.4054 7.86737 2.36769 7.80709 2.3183 7.75732C2.26891 7.70755 2.20909 7.66955 2.14323 7.64611L1.46959 7.48504C1.33184 7.45195 1.21035 7.37035 1.12707 7.25494C1.03656 7.14519 0.987967 7.00645 0.990055 6.86377V5.20706C0.987967 5.06437 1.03656 4.92564 1.12707 4.81589C1.21035 4.70048 1.33184 4.61887 1.46959 4.58579L2.14323 4.42472C2.20698 4.39985 2.26454 4.36121 2.31188 4.31151C2.35922 4.26182 2.39519 4.20229 2.41725 4.1371C2.44772 4.07238 2.46352 4.00164 2.46352 3.93001C2.46352 3.85837 2.44772 3.78764 2.41725 3.72292L2.07472 3.12466C1.99771 3.00578 1.96908 2.86157 1.9948 2.72199C2.01253 2.57861 2.07674 2.44514 2.17748 2.34232L3.31924 1.19183C3.41733 1.083 3.55113 1.01354 3.69602 0.996243C3.83421 0.982339 3.97338 1.01038 4.09563 1.07678L4.67793 1.43343C4.74412 1.46434 4.8162 1.48035 4.88916 1.48035C4.96211 1.48035 5.03419 1.46434 5.10038 1.43343C5.1635 1.40834 5.21947 1.36789 5.2633 1.31569C5.30713 1.2635 5.33746 1.20118 5.35157 1.1343L5.54567 0.52454C5.57787 0.38233 5.65871 0.256072 5.77402 0.167886C5.8839 0.0784095 6.02094 0.0296716 6.16222 0.0298267H7.79493C7.94692 0.0281611 8.09529 0.0766468 8.21738 0.167886C8.32916 0.257276 8.406 0.383599 8.43431 0.52454L8.60558 1.19183C8.62461 1.25993 8.65936 1.32252 8.70698 1.37451C8.75461 1.42649 8.81375 1.4664 8.8796 1.49096C8.94383 1.52166 9.01403 1.53758 9.08511 1.53758C9.1562 1.53758 9.2264 1.52166 9.29063 1.49096L9.88434 1.1343C10.0066 1.06791 10.1458 1.03986 10.284 1.05377C10.4288 1.07106 10.5627 1.14052 10.6607 1.24935L11.8025 2.39985C11.9032 2.50267 11.9675 2.63613 11.9852 2.77951C12.0055 2.91901 11.9773 3.06131 11.9053 3.18218L11.5513 3.74593C11.5203 3.80638 11.5041 3.87345 11.5041 3.94151C11.5041 4.00957 11.5203 4.07664 11.5513 4.1371C11.5762 4.2007 11.6164 4.2571 11.6682 4.30126C11.72 4.34543 11.7818 4.37598 11.8482 4.3902L12.5218 4.58579C12.6542 4.62383 12.7706 4.70468 12.8529 4.81589V4.81589ZM8.76542 9.15325C9.30993 8.84393 9.76005 8.39036 10.067 7.84169C10.3928 7.2967 10.5627 6.67159 10.558 6.03541C10.5627 5.39923 10.3928 4.77413 10.067 4.22914C9.75468 3.68459 9.30583 3.2323 8.76542 2.91757C8.22789 2.59138 7.61176 2.42025 6.98428 2.42286C6.35609 2.41476 5.7385 2.5863 5.20314 2.91757C4.65854 3.2307 4.20561 3.68313 3.89012 4.22914C3.57421 4.77776 3.40872 5.40113 3.41058 6.03541C3.40563 6.66495 3.56721 7.28445 3.8787 7.83019C4.18568 8.37886 4.6358 8.83242 5.1803 9.14175C5.72679 9.46524 6.35052 9.63231 6.98428 9.62496C7.60823 9.62355 8.22142 9.46116 8.76542 9.15325V9.15325ZM7.80635 6.85226C8.01589 6.63328 8.13084 6.33958 8.12604 6.03541C8.12948 5.8826 8.10073 5.73078 8.04168 5.58998C7.98264 5.44918 7.89465 5.32259 7.78351 5.21856C7.68056 5.11011 7.55633 5.02444 7.41873 4.967C7.28114 4.90956 7.13318 4.88161 6.98428 4.88492C6.8321 4.88251 6.68099 4.91079 6.53981 4.96809C6.39864 5.02539 6.27026 5.11055 6.16222 5.21856C5.95267 5.43755 5.83773 5.73125 5.84252 6.03541C5.84014 6.18875 5.8682 6.34103 5.92506 6.48328C5.98192 6.62554 6.06644 6.7549 6.17363 6.86377C6.28032 6.9704 6.40684 7.05476 6.54595 7.11201C6.68506 7.16927 6.83402 7.19829 6.98428 7.19741C7.13646 7.19982 7.28758 7.17154 7.42875 7.11424C7.56992 7.05694 7.69831 6.97179 7.80635 6.86377V6.85226Z" fill="#ACAFBD" />
        </svg>
      </button>
      <div class="dropdown-menu dropdown-menu-end ul-form">
        <div class="mb-3 d-flex justify-content-between">
            <div class="form-check pt-2">
                <input type="checkbox" class="form-check-input" id="allCapsCheckbox_${index}" data-input="allCaps">
                <label class="custom-control-label" for="allCapsCheckbox_${index}">USE ALL CAPS</label>
            </div>
            <div class="color d-flex">
                <input class="form-control border form-control-color p-0 text-end diraction-end" type="color" value="${options[index].fillColor}" data-input="fillColor" title="Fill color">
                <input class="form-control border form-control-color p-0 ms-2" type="color" value="${options[index].shadowColor}" data-input="shadowColor" title="Outline color">
            </div>
        </div>
        <div class="mb-3 d-flex justify-content-between gap-2 align-btn">
            <select class="form-select" data-input="font">
                <option value="Impact">Impact</option>
                <option value="Arial">Arial</option>
                <option value="Helvetica">Helvetica</option>
                <option value="Comic Sans MS">Comic Sans MS</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Times">Times</option>
                <option value="Courier New">Courier New</option>
                <option value="Verdana">Verdana</option>
                <option value="Georgia">Georgia</option>
                <option value="Palatino">Palatino</option>
                <option value="Garamond">Garamond</option>
                <option value="Bookman">Bookman</option>
                <option value="Trebuchet MS">Trebuchet MS</option>
                <option value="Arial Black">Arial Black</option>
            </select>
            <select class="form-select" data-input="textAlign">
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
            </select>
        </div>
        <div class="group-50">
            <div class="mb-3 w-50">
                <label class="mb-1">Vertical offset:</label>
                <input class="form-control" type="number" value="${options[index].offsetY}" data-input="offsetY">
            </div>
            <div class="mb-3 w-50">
                <label class="mb-1">Horizontal offset:</label>
                <input class="form-control" type="number" value="${options[index].offsetX}" data-input="offsetX">
            </div>
        </div>
        <div class="group-50">
            <div class="mb-3 w-50">
                <label class="mb-1">Font size:</label>
                <input class="form-control" type="number" min="1" max="100" value="${options[index].fontSize}" data-input="fontSize">
            </div>
            <div class="mb-3 w-50">
                <label class="mb-1">Shadow width:</label>
                <input class="form-control" type="number" min="0" max="10" value="${options[index].shadowBlur}" data-input="shadowBlur">
            </div>
        </div>
      </div>
    </div>
`;

        const fragment = document.createDocumentFragment();
        const div = document.createElement('div');
        div.setAttribute('data-section', 'textBox');
        div.setAttribute('data-index', index);
        div.innerHTML = inputTemplate;
        setTimeout(() => {
            selectedImage && div.querySelector('[data-input="text"]').focus();
        }, 100);
        div.querySelector('[data-input="font"]').value = options[index].font;
        div.querySelector('[data-input="textAlign"]').value = options[index].textAlign;
        div.querySelector('[data-input="allCaps"]').checked = options[index].allCaps;
        return fragment.appendChild(div);
    }

    function onAddTextboxBtnClicked() {
        const textBoxesLength = document.querySelectorAll('[data-input="text"]').length;
        options.push(Object.assign({}, defaultOptions));
        inputsContainer.appendChild(createNewInput(textBoxesLength));
    }

    function draw(image) {
        if (image == null) {
            return;
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

        options.forEach(function (item, index) {
            ctx.font = `${item.fontSize}px ${item.font}`;

            const multiplier = index + 1;
            const lineHeight = ctx.measureText('M').width + 20;
            const xPos = item.textAlign === 'center' || !item.textAlign ? canvas.width / 2 : item.textAlign === 'left' ? 0 : canvas.width;
            const shadowBlur = !Number.isNaN(Number(item.shadowBlur)) ? Number(item.shadowBlur) : 3;
            const text = item.allCaps === true ? item.text.toUpperCase() : item.text;

            ctx.fillStyle = item.fillColor;
            ctx.textAlign = item.textAlign;
            ctx.save();

            if (shadowBlur !== 0) {
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 0;
                ctx.shadowBlur = shadowBlur;
                ctx.shadowColor = item.shadowColor;
            }

            ctx.fillText(
                text,
                xPos + Number(item.offsetX),
                index === 1 ? canvas.height - 20 + Number(item.offsetY) : lineHeight * (multiplier - 1 || 1) + Number(item.offsetY)
            );

            ctx.restore();
        });
    }

    function urltoFile(url, filename, mimeType) {
        return fetch(url)
            .then(res => res.arrayBuffer())
            .then(buf => new File([buf], filename, { type: mimeType }))
            .catch(err => new Error(err));
    }

    // if (navigator.share) {
    //     shareSection.classList.remove('d-none');

    //     shareBtn.addEventListener('click', () => {
    //         urltoFile(canvas.toDataURL('image/png'), 'meme.png', 'image/png').then(file => {
    //             const filesArray = [file];

    //             if (navigator.canShare && navigator.canShare({ files: filesArray })) {
    //                 navigator.share({
    //                     title: document.title,
    //                     text: document.querySelector('meta[name="description"]').content,
    //                     files: filesArray
    //                 }).then(() => {
    //                     console.log('Share was successful.');
    //                 }).catch(error => {
    //                     if (error.name !== 'AbortError') {
    //                         showError('There was an error while trying to share your meme.');
    //                     }
    //                 });
    //             }
    //         }).catch(() => {
    //             showError('Unable to convert to file.');
    //         });
    //     }, false);
    // }

    addTextboxBtn.addEventListener('click', onAddTextboxBtnClicked, false);
    generateMemeBtn.addEventListener('click', generateMeme, false);

    // downloadMemeBtn.addEventListener('click', () => toggleModal(downloadModal, false), false);

    inputsContainer.appendChild(createNewInput(0));
    inputsContainer.appendChild(createNewInput(1));

    inputsContainer.addEventListener('input', evt => {
        const element = evt.target;
        const index = Number(element.closest('[data-section="textBox"]').getAttribute('data-index'));
        let prop;

        if (element.matches('[data-input="text"]')) {
            prop = 'text';
        } else if (element.matches('[data-input="fillColor"]')) {
            prop = 'fillColor';
        } else if (element.matches('[data-input="shadowColor"]')) {
            prop = 'shadowColor';
        } else if (element.matches('[data-input="font"]')) {
            prop = 'font';
        } else if (element.matches('[data-input="fontSize"]')) {
            prop = 'fontSize';
        } else if (element.matches('[data-input="textAlign"]')) {
            prop = 'textAlign';
        } else if (element.matches('[data-input="shadowBlur"]')) {
            prop = 'shadowBlur';
        } else if (element.matches('[data-input="offsetY"]')) {
            prop = 'offsetY';
        } else if (element.matches('[data-input="offsetX"]')) {
            prop = 'offsetX';
        }

        if (prop) {
            handleTextPropChange(element, index, prop);
        }
    }, false);

    inputsContainer.addEventListener('change', evt => {
        const element = evt.target;
        const index = Number(element.closest('[data-section="textBox"]').getAttribute('data-index'));
        let prop;

        if (element.matches('[data-input="allCaps"]')) {
            prop = 'allCaps';
        }

        if (prop) {
            handleTextPropChange(element, index, prop);
        }
    }, false);

    // inputsContainer.addEventListener('click', evt => {
    //     const element = evt.target;

    //     if (element.matches('[data-button="settings"]')) {
    //         element.classList.toggle('active');
    //     }
    // }, false);

    window.handleFileSelect = handleFileSelect
}());
