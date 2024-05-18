let widget;

export function loadCloudinaryWidget() {
    // Verifica si el script de Cloudinary ya ha sido cargado
    if (!window.cloudinary) {
        let script = document.createElement('script');
        script.src = "https://upload-widget.cloudinary.com/latest/global/all.js";
        script.type = "text/javascript";
        script.onload = () => {
            createWidget();
        };
        document.body.appendChild(script);
    } else {
        createWidget();
    }
}

function createWidget() {
    widget = window.cloudinary.createUploadWidget({
        cloud_name: "dalg6e6pv",
        upload_preset: "upload-laravel"
    }, (error, result) => {
        if (!error && result && result.event === "success") {
            // Obtén una referencia al editor TinyMCE por su ID
            let editor = tinymce.get('description');

            // Esto crea la etiqueta de la imagen para insertar en el editor
            let imgTag = `<p><img src="${result.info.secure_url}" alt="" width="${result.info.width}" height="${result.info.height}"></p>`;

            // Inserta la etiqueta de la imagen en el editor
            editor.insertContent(imgTag);
        }
    });
}

export function openUploadWidget(event) {
    // esto quita comportamiento predeterminado del botón, fue por error de duplicación de eventos
    event.preventDefault();

    if (widget) {
        widget.open();
    } else {
        console.error('El widget de Cloudinary aún no se ha cargado.');
    }
}