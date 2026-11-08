import Screenshot  from '/js/modules/Screenshot/Screenshot.js';

if (Screenshot.isSupported()) {
    $('.document_item .upload-from-section').removeClass('d-none');
    $('.document_item .fileupload').each((i, fileupload) => {
        Screenshot.listenOnFileUpload(fileupload);
    });
    const btn_screenshot = $('.document_item button[name="add_screenshot"]');
    const btn_screenrecording = $('.document_item button[name="add_screenrecording"]');
    const btn_stop_recording = $('.document_item button[name="stop_recording"]');
    const preview_container = $('#screen_capture_preview');
    btn_screenshot.removeClass('d-none');
    btn_screenrecording.removeClass('d-none');

    btn_screenshot.on('click', () => {
        btn_stop_recording.addClass('d-none');
        Screenshot.captureScreenshot().then((temp_canvas) => {
            const img_index = preview_container.find('.previews').children().length;
            const fake_filename = `screenshot${img_index}.png`;
            Screenshot.appendPreviewImg(preview_container.find('.previews'), temp_canvas, '200px', fake_filename);
            // Add the blob to the file upload control
            temp_canvas.toBlob((blob) => {
                // Add fake name to the blob with '.png' extension as matching on "blob.type" isn't working
                blob.name = fake_filename;
                window.uploadFile(blob, {
                    getElement: () => {
                        return preview_container.get(0);
                    }
                });
                temp_canvas.remove();
            }, 'image/png');
        });
    });

    btn_screenrecording.on('click', () => {
        const chunks = [];
        Screenshot.startRecording().then((recorder) => {
            btn_stop_recording.removeClass('d-none');
            recorder.ondataavailable = (e) => {
                // No time slice specified when starting the recorder, so this event fires only once when the recording stops
                chunks.push(e.data);
                const img_index = preview_container.find('.previews').children().length;
                const fake_filename = `screen_capture${img_index}.webm`;
                e.data.name = fake_filename;
                Screenshot.appendPreviewVideo(preview_container.find('.previews'), e.data, '200px', fake_filename);
                window.uploadFile(e.data, {
                    getElement: () => {
                        return preview_container.get(0);
                    }
                });
            };
            btn_stop_recording.on('click', () => {
                recorder.stop();
                recorder.stream.getTracks().forEach((track) => track.stop());
                btn_stop_recording.addClass('d-none');
            });
        });
    });
}

