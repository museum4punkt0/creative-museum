define(['jquery', 'TYPO3/CMS/Backend/Modal', 'ckeditor'], function($, Modal, CKEDITOR) {

    var CmsContentHandler = {
        editors: []
    };

    CmsContentHandler.init = function() {
        CKEDITOR.timestamp += '-' + Math.floor(Date.now() / 1000);

        const fields = [
            'about-html-rte',
            'faq-html-rte',
            'simplelanguage-html-rte',
            'signlanguage-html-rte',
            'imprint-html-rte'
        ];

        for (const field of fields) {
            CKEDITOR.replace(field, {});
        }
    };

    CmsContentHandler.init();

    return CmsContentHandler;
});