import './bootstrap';

import Alpine from 'alpinejs';
import $, { ajaxSetup } from 'jquery';
window.$ = window.jQuery = $;

window.Alpine = Alpine;

Alpine.start();


jQuery(function($) {
    uploadMri();
    ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function uploadMri(){
    $("#uploadFiles").on("click", function(e) {
        e.preventDefault();

        let name = $("#project-name").val();
        if (name === "") {
            alert("Please enter a project name before uploading files.");
            return;
        }

        let filesInput = $("#dataNifty")[0];
        let files = filesInput.files;

        if (files.length === 0) {
            alert("Please select files to upload.");
            return;
        }

        let formData = new FormData();
        formData.append('name', name);
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        $.ajax({
            url: '/upload-image',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status === 'success') {
                    alert('Files uploaded successfully! You will be notified when the processing is complete.');
                    $("#project-name").val('');
                    $("#dataNifty").val('');
                    startCheckingLock();
                }

                if (response.status === 'busy') {
                    alert('The server is currently busy. You will be notified when the server is ready to process your files.');
                }

                if(response.status === 'error') {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error uploading files:', error);
                alert('Error uploading files');
            }
        });
    });
}

function startCheckingLock() {
    setInterval(function() {
        checkLockStatus().done(function(response) {
            if(!response.locked) {
                $.ajax({
                    url: '/processing.done',
                    type: 'POST',
                    success: function() {
                        alert('Processing is complete. You can now upload new files.');
                        $("#lockStatus").text("System is ready for new uploads.");
                    },
                });
            } 
        }).fail(function() {
            console.error('No se pudo verificar el estado del lock.');
            $("#lockStatus").text("Error al verificar el sistema.");
        });
    }, 3000);
}

