class ArtisanUpload {
    constructor(element, { filesGrid = null, maxFiles = 1, maxSize = null, multiple = false, fileExtensions, dropOnBody = true }) {
        this.element = document.querySelector(element);
        this.maxFiles = maxFiles;
        this.maxSize = maxSize;
        this.multiple = multiple;
        this.dropOnBody = dropOnBody;
        this.fileExtensions = fileExtensions;
        this.filesGrid = filesGrid;
    }

    create() {
        const fileSelect = this.element.querySelector('[type=file]');
        var fileDrag = this.dropOnBody ? document.body : this.element.querySelector('label.file-drag');

        fileSelect.addEventListener('change', (e) => { this.fileSelectHandler(e) }, false);
        var xhr = new XMLHttpRequest();
        if (xhr.upload) {
            fileDrag.addEventListener('dragover', (e) => { this.fileDragHover(e) }, false);
            fileDrag.addEventListener('dragleave', (e) => { this.fileDragHover(e) }, false);
            fileDrag.addEventListener('drop', (e) => { this.fileSelectHandler(e) }, false);
        }
    }

    fileDragHover(event) {
        event.stopPropagation();
        event.preventDefault();

        this.element.className = (event.type === 'dragover' ? 'hover file-drag' : 'file-drag');
    }

    fileSelectHandler(e) {
        this.fileDragHover(e);

        var files = e.target.files || e.dataTransfer.files;
        ([...files]).forEach(file => {
            parseFile(e, file);
        });
    }

    output(file) {
        this.element.querySelector('.uploader-wrapper').classList.add('d-none')

        const filesList = !this.filesGrid ? this.element.querySelector('.files-grid') : document.querySelector(this.filesGrid)
        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.title = file.name
        img.dataset.bsToggle = 'tooltip'
        filesList.appendChild(img);
        new Tooltip(img, {})
    }

    parseFile(event, file) {
        const uploader = uploadElements.length === 1 ? document.querySelector('.artisan-uploader') : event.target.closest('.artisan-uploader')
        const maxFiles = uploader.dataset.maxFiles;
        const allowed = uploader.dataset.allowed;
        var imageName = file.name;
        var isGood = (/\.(?=gif|jpg|png|jpeg)/gi).test(imageName);
        if (isGood) {
            uploader.querySelector('.uploader-wrapper').classList.add("d-none");
            uploader.querySelector('.add-more')?.classList.remove("d-none");
            uploader.querySelector('.uploader-error')?.classList.add("d-none");

            output(uploader, file)
        } else {
            uploader.querySelector('.uploader-error').classList.remove("d-none");
            uploader.querySelector('label.file-drag').classList.remove("d-none");
        }
    }

}


export default ArtisanUpload;
