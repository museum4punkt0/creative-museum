define(['jquery', 'TYPO3/CMS/Backend/Modal'], function($, Modal) {

    $(document).on('click', '[data-delete-user-button]', (evt) => {
        evt.preventDefault();
        const $anchorElement = $(evt.currentTarget);
        $anchorElement.tooltip('hide');
        const $modal = Modal.confirm($anchorElement.data('title'), $anchorElement.data('message'), 1, [
            {
                text: $anchorElement.data('button-close-text') || 'Cancel',
                active: true,
                btnClass: 'btn-default',
                name: 'cancel',
            },
            {
                text: $anchorElement.data('button-ok-text') || 'Delete',
                btnClass: 'btn-warning',
                name: 'delete',
            },
        ]);
        $modal.on('button.clicked', (e) => {
            if (e.target.getAttribute('name') === 'cancel') {
                Modal.dismiss();
            } else if (e.target.getAttribute('name') === 'delete') {
                Modal.dismiss();
                window.location.href = $anchorElement.attr('href');
            }
        });
    });

});