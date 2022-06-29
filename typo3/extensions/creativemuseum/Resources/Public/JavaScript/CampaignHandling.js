define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/Creativemuseum/BadgeHandling'
], function(
    $,
    Modal,
    BadgeHandler
) {

    $('#campaign-form').on('submit', function(ev) {

        ev.preventDefault();

        const badges = BadgeHandler;
        const uploads = badges.uploadFields;

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
    });

});