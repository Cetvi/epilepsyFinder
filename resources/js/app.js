import './bootstrap';

import Alpine from 'alpinejs';
import $, { ajaxSetup } from 'jquery';
window.$ = window.jQuery = $;

window.Alpine = Alpine;

Alpine.start();


jQuery(function ($) {
    uploadMri();
    lastProject();
    ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    tutorial();
    hideImages();
    controlImagesMoreInfo();
    window.deleteProject = function (projectId) {

        if (confirm("Are you sure you want to delete this project? This action cannot be undone.")) {
            $.ajax({
                url: "delete-project",
                data: { "projectId": projectId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert('Project deleted successfully.');
                        location.reload();
                    } else {
                        alert('Error deleting project: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error deleting project:', error);
                    alert('Error deleting project');
                }
            });
        }
    }

});

function tutorial() {
    $(document).on('click', '#tutorialButton', function () {
        window.location.href= '/newProjectTutorial';
    });

    $(function () {

        if(window.location.pathname == '/newProjectTutorial') {
            introJs()
            .oncomplete(function() {
                window.location.href = '/projectsTutorial';
            })
            .onexit(function() {
                window.location.href = '/projectsTutorial';
            })
            .start();
        }else if(window.location.pathname == '/projectsTutorial') {
            introJs()
            .oncomplete(function() {
                window.location.href = '/dashboard';
            })
            .onexit(function() {
                window.location.href = '/dashboard';
            })
            .start();
        } 
    }
    );
}

function lastProject() {
    if(window.location.pathname == '/dashboard') {
        $.ajax({
            url: '/last-project',
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                $('#cardLastProject').html(data);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching last project:', error);
            }
        });
    }
}

function uploadMri() {
    $("#uploadFiles").on("click", function (e) {
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
        formData.append('file0', files[0]);
        formData.append('file1', files[1]);

        
        $("#loadingSpinner").show();
        $("#uploadFiles").prop("disabled", true);

        $.ajax({
            url: '/upload-image',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loadingSpinner").hide();
                $("#uploadFiles").prop("disabled", false);

                if (response.status === 'success') {
                    alert('Files uploaded successfully! You will be notified when the processing is complete.');
                    window.location.href = '/show-projects';
                } else if (response.status === 'busy') {
                    alert('The server is currently busy. Your files have been processed. You will be notified when the processing is complete.');
                    window.location.href = '/show-projects';
                } else if (response.status === 'error') {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                $("#loadingSpinner").hide();
                $("#uploadFiles").prop("disabled", false);
                console.error('Error uploading files:', error);
                alert('Error uploading files');
            }
        });
    });
}


function hideImages() {
    $("#optionForm").on('change', async function () {
        const selected = $('input[name="option"]:checked').val();

        $('.diffOptions:visible').addClass('hidden');
        $('.' + selected).removeClass('hidden');

        if (selected === 'segmentation') {
            $('#vtk-container').removeClass('hidden');

            setTimeout(async () => {
                if (!window.vtkReady) {
                    await initVTK();
                } else {
                    window.renderWindow.resize();
                    window.renderWindow.render();
                }
            }, 10);
        } else {
            $('#vtk-container').addClass('hidden');
        }
    });

    $('#optionForm').trigger('change');
}


async function initVTK() {
    const vtk = window.vtk;
    if (!vtk) return console.error("vtk.js no cargado");
    let extraData = $('#extraData').val();
    const response = await fetch('/json/volume_data' + extraData + '.json');
    const json = await response.json();

    const { dimensions, mri, mask } = json;
    const [xDim, yDim, zDim] = dimensions;

    const fullScreenRenderer = vtk.Rendering.Misc.vtkFullScreenRenderWindow.newInstance({
        rootContainer: document.getElementById('vtk-container'),
        containerStyle: {
            height: '100%',
            width: '100%',
            position: 'relative'
        },
    });

    const renderer = fullScreenRenderer.getRenderer();
    const renderWindow = fullScreenRenderer.getRenderWindow();
    window.renderWindow = renderWindow;
    window.vtkReady = true;


    const imageDataMRI = vtk.Common.DataModel.vtkImageData.newInstance();
    imageDataMRI.setDimensions(xDim, yDim, zDim);
    imageDataMRI.getPointData().setScalars(
        vtk.Common.Core.vtkDataArray.newInstance({
            name: 'MRI',
            values: new Float32Array(mri),
            numberOfComponents: 1,
        })
    );

    const mriMapper = vtk.Rendering.Core.vtkVolumeMapper.newInstance();
    mriMapper.setInputData(imageDataMRI);
    const mriActor = vtk.Rendering.Core.vtkVolume.newInstance();
    mriActor.setMapper(mriMapper);

    const mriProperty = mriActor.getProperty();
    mriProperty.getRGBTransferFunction(0).addRGBPoint(0, 0.0, 0.0, 0.0);
    mriProperty.getRGBTransferFunction(0).addRGBPoint(1000, 1.0, 1.0, 1.0);
    mriProperty.getScalarOpacity(0).addPoint(0, 0.0);
    mriProperty.getScalarOpacity(0).addPoint(1000, 1.0);
    mriProperty.setScalarOpacityUnitDistance(0, 1.0);

    renderer.addVolume(mriActor);


    const imageDataMask = vtk.Common.DataModel.vtkImageData.newInstance();
    imageDataMask.setDimensions(xDim, yDim, zDim);
    imageDataMask.getPointData().setScalars(
        vtk.Common.Core.vtkDataArray.newInstance({
            name: 'mask',
            values: new Uint16Array(mask),
            numberOfComponents: 1,
        })
    );

    const maskMapper = vtk.Rendering.Core.vtkVolumeMapper.newInstance();
    maskMapper.setInputData(imageDataMask);

    const maskActor = vtk.Rendering.Core.vtkVolume.newInstance();
    maskActor.setMapper(maskMapper);

    const maskColorTransfer = maskActor.getProperty().getRGBTransferFunction(0);
    const maskOpacityTransfer = maskActor.getProperty().getScalarOpacity(0);

    for (const [labelIdStr, color] of Object.entries(colorLut)) {
        const labelId = Number(labelIdStr);
        const [r, g, b] = color;
        maskColorTransfer.addRGBPoint(labelId, r, g, b);
        maskOpacityTransfer.addPoint(labelId, labelId === 0 ? 0.0 : 0.3);
    }

    maskActor.getProperty().setScalarOpacityUnitDistance(0, 3.0);
    renderer.addVolume(maskActor);

    const camera = renderer.getActiveCamera();
    camera.setFocalPoint(0, 0, 0);
    camera.setPosition(10, -1, -2);
    camera.setViewUp(0, -2, 0);

    renderer.resetCamera();
    renderWindow.render();
}


function controlImagesMoreInfo(){
    $(document).on('click', '.toggle-btn', function () {
        console.log('Toggle button clicked');
        const targetId = $(this).data('target');
        const $img = $('#' + targetId);

        if ($img.is(':visible')) {
            $img.hide();
            $(this).text('+');
        } else {
            $img.show();
            $(this).text('-');
        }

    });
}