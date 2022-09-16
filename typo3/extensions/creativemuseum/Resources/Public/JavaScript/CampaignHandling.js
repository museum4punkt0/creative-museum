define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/Backend/ColorPicker',
    'TYPO3/CMS/Creativemuseum/BadgeHandling',
    'TYPO3/CMS/Creativemuseum/FeedbackOptionHandling'
], function(
    $,
    Modal,
    ColorPicker,
    BadgeHandler,
    FeedbackOptionHandling
) {

    const colorPickerElement = document.querySelector('.t3js-colorpicker');

    ColorPicker.initialize(colorPickerElement);

    $('#campaign-form').on('submit', function(ev) {
        /*
        ev.preventDefault();

        const badges = BadgeHandler;
        const uploads = badges.uploadFields;

        console.log(uploads);

        uploads.forEach((item) => {
            const file = item.cachedFileArray[0];
            let formData = new FormData();
            formData.append('file', file, file.name);

            $.ajax({
                type: "POST",
                url: TYPO3.settings.ajaxUrls.cm_uploader,
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    console.log(response);
                },
                dataType: 'json'
            });
        })
        */
    });

});