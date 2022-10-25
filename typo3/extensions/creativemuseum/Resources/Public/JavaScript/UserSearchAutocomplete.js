define(['jquery', 'jquery/autocomplete', 'TYPO3/CMS/Backend/Notification',], function($, autocomplete, Notification) {

    $('#user-autocomplete').autocomplete({
        minChars: 3,
        deferRequestBy: 500,
        serviceUrl: TYPO3.settings.ajaxUrls.cm_usersearch,
        formatResult: (suggestion) => {
            return ''
                + '<div class="dropdown-table">'
                + '<div class="dropdown-table-row">'
                + '<div class="dropdown-table-column dropdown-table-title">'
                + suggestion.value
                + '</div>'
                + '</div>'
                + '</div>'
                + '';
        },
        containerClass: 'cm-usersearch-dropdown',
        onSelect: (selection) => {
            const selectedUuid = selection.uuid;
            $('#user-autocomplete-value').val(selectedUuid);
        }
    });

    $('#notificationUserCreateForm').on('submit', function(ev) {
       if ($('#user-autocomplete-value').val() === '') {
           Notification.error('Fehler', 'Es muss ein gültiger User aus dem Autocomplete Dropdown gewählt werden.', 5);
           ev.preventDefault();
       }
    });

});