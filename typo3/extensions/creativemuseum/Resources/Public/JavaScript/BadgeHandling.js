define(['jquery', 'TYPO3/CMS/Backend/Modal'], function($, Modal) {

    var BadgeHandler = {
        badges: [],
        container: null,
        uploadFields: []
    };

    BadgeHandler.init = function() {
        this.container = document.querySelector('.t3js-badges-container');

        if (this.container === null) {
            return;
        }

        const uploadContainers = document.querySelectorAll('.custom-file-container');

        uploadContainers.forEach((item) => {
           const uploadId = item.dataset.uploadId;
           this.uploadFields.push(new FileUploadWithPreview.FileUploadWithPreview(uploadId));
        });

        this.badges = document.querySelectorAll('.t3js-badges-container .t3js-item-container');

        this.addRemoveButtons();
        this.addNewButton();
    };

    BadgeHandler.itemCount = function() {
        return this.badges.length;
    }

    BadgeHandler.addRemoveButtons = function() {

        this.container.addEventListener('click', function(ev) {
            let node = ev.target;
            if (! node.classList.contains('t3js-badges-item-remove')) {
                return;
            }

            let targetItem = document.querySelector('.' + node.dataset.target);
            targetItem.remove();
            this.badges = document.querySelectorAll('.t3js-badges-container .t3js-item-container');
        }.bind(this));

        this.badges.forEach((item, index) => {

            item.classList.add('t3js-badge-item-' + index);
            let btn = document.createElement('a');
            btn.classList.add(
                'btn',
                'btn-default',
                'btn-sm',
                't3js-badges-item-remove',
                't3js-iterable'
            );
            btn.dataset.template = 't3js-badge-item-[i]';
            btn.dataset.target = 't3js-badge-item-' + index;
            btn.dataset.iterationTarget = 'target';
            btn.href = 'javascript:;';
            btn.textContent = 'DELETE';

            item.prepend(btn);
        })
    };

    BadgeHandler.setIterations = function (item, iteration) {
        let iterables = item.querySelectorAll('.t3js-iterable');

        if (item.classList.contains('t3js-iterable')) {
            item.classList.remove(item.dataset.template.replace('[i]', iteration - 1));
            item.classList.add(item.dataset.template.replace('[i]', iteration));
        }

        iterables.forEach((item) => {
            switch (item.dataset.iterationTarget) {
                case 'value':
                    item.innerHTML = item.dataset.template.replace('[i]', iteration + 1);
                    break;
                case 'name':
                    item.name = item.dataset.template.replace('[i]', iteration);
                case 'target':
                    item.dataset.target = item.dataset.template.replace('[i]', iteration);
                    break;
                case 'class':
                    item.classList.remove(item.dataset.template.replace('[i]', iteration - 1));
                    item.classList.add(item.dataset.template.replace('[i]', iteration));
                    break;
            }
        });
    };

    BadgeHandler.addNewButton = function () {

        this.container.addEventListener('click', function(ev) {

            let node = ev.target;
            if (! node.classList.contains('t3js-badges-item-new')) {
                return;
            }

            let itemContainer = this.badges.item(this.badges.length - 1).cloneNode(true);
            let inputFields = itemContainer.querySelectorAll('input');
            inputFields.forEach( (item) => item.value = '' );
            this.setIterations(itemContainer, this.itemCount() + 1);

            function insertAfter(newNode, existingNode) {
                existingNode.parentNode.insertBefore(newNode, existingNode.nextSibling);
            }

            insertAfter(itemContainer, this.badges.item(this.badges.length - 1));
            this.badges = document.querySelectorAll('.t3js-badges-container .t3js-item-container');

        }.bind(this));

        let btn = document.createElement('a');
        btn.classList.add('btn');
        btn.classList.add('btn-default');
        btn.classList.add('btn-sm');
        btn.classList.add('t3js-badges-item-new');
        btn.href = 'javascript:;';
        btn.textContent = 'Badge hinzufügen';
        this.container.append(btn);
    };

    BadgeHandler.init();

    return BadgeHandler;
});