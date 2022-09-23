define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/Backend/Notification',
    'TYPO3/CMS/Backend/Severity',
    'TYPO3/CMS/Backend/ColorPicker',
    'TYPO3/CMS/Creativemuseum/BadgeHandling',
    'TYPO3/CMS/Creativemuseum/AwardHandling',
    'TYPO3/CMS/Creativemuseum/FeedbackOptionHandling'
], function(
    $,
    Modal,
    Notification,
    Severity,
    ColorPicker,
    BadgeHandler,
    AwardHandler,
    FeedbackOptionHandling
) {

    const colorPickerElement = document.querySelector('.t3js-colorpicker');

    ColorPicker.initialize(colorPickerElement);

    $('#campaign-form').on('submit', function(ev) {

        ev.preventDefault();

        if (! FeedbackOptionHandling.validate()) {
            Notification.error('Fehler', 'Die Feedback Optionen sind nicht gültig.', 5);
            return;
        }

        const form = this;

        const uploadsCount = BadgeHandler.uploadFields.length + AwardHandler.uploadFields.length;
        let i = 0;

        BadgeHandler.uploadFields.forEach((item) => {
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

        AwardHandler.uploadFields.forEach((item) => {
            const file = item.cachedFileArray[0];

            if (file === undefined) {
                Notification.error('Fehler', 'Awards müssen eine Grafik besitzen', 5);
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
                    const id = item.el.dataset.awardId;

                    const fileIri = $('<input type="hidden" />');
                    fileIri.attr(
                        'name',
                        `tx_creativemuseum_system_creativemuseumcmadm[campaignDto][awards][${id}][pictureIRI]`
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