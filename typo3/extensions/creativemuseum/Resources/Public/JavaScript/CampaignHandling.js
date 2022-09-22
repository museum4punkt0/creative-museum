define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/Backend/Notification',
    'TYPO3/CMS/Backend/Severity',
    'TYPO3/CMS/Backend/ColorPicker',
    'TYPO3/CMS/Creativemuseum/BadgeHandling',
    'TYPO3/CMS/Creativemuseum/FeedbackOptionHandling'
], function(
    $,
    Modal,
    Notification,
    Severity,
    ColorPicker,
    BadgeHandler,
    FeedbackOptionHandling
) {

    const colorPickerElement = document.querySelector('.t3js-colorpicker');

    ColorPicker.initialize(colorPickerElement);

    $('#campaign-form').on('submit', function(ev) {

        ev.preventDefault();

        const feedbackOptionsValid = FeedbackOptionHandling.validate();

        if (! feedbackOptionsValid) {
            Notification.error('Fehler', 'Die Feedback Optionen sind nicht gültig.');
            return;
        }

        const uploads = BadgeHandler.uploadFields;
        const form = this;

        const uploadsCount = uploads.length;
        let i = 0;

        uploads.forEach((item) => {
            const file = item.cachedFileArray[0];

            if (file === undefined) {
                Notification.error('Fehler', 'Badges müssen eine Grafik besitzen', 5);
                i = 99999;
                return;
            }

            if (file.name.indexOf('preset-file:upload:') > -1) {
                ++i;
                if (i === uploadsCount) {
                    form.submit();
                }
                return;
            }

            let formData = new FormData();
            let filename = file.name.split(':upload:')[0];
            formData.append('file', file, filename);

            $.ajax({
                type: "POST",
                url: TYPO3.settings.ajaxUrls.cm_uploader,
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    const id = item.el.dataset.badgeId;

                    const fileIri = $('<input type="hidden" />');
                    fileIri.attr(
                        'name',
                        `tx_creativemuseum_system_creativemuseumcmadm[campaignDto][badges][${id}][pictureIRI]`
                    );
                    fileIri.attr(
                        'value',
                        response.file
                    );
                    fileIri.appendTo(form);
                    ++i;

                    if (i === uploadsCount) {
                        form.submit();
                    }
                },
                dataType: 'json'
            });
        });
    });

});