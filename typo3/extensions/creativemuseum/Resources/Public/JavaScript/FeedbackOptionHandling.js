define(['TYPO3/CMS/Backend/Modal'], function(Modal) {

    var FeedbackOptionHandler = {
        options: [],
        container: null,
    };

    FeedbackOptionHandler.init = function() {
        this.container = document.querySelector('.t3js-feedback-options-container');

        if (this.container === null) {
            return;
        }

        this.options = document.querySelectorAll('.t3js-feedback-options-container .t3js-item-container');

        this.addRemoveButtons();
        this.addNewButton();
        console.log('Feedback Options Handler initialized');
    };

    FeedbackOptionHandler.itemCount = function() {
        return this.options.length;
    };

    FeedbackOptionHandler.addRemoveButtons = function() {

        this.container.addEventListener('click', function(ev) {
            let node = ev.target;
            if (! node.classList.contains('t3js-options-item-remove')) {
                return;
            }

            let targetItem = document.querySelector('.' + node.dataset.target);
            targetItem.remove();
            this.options = document.querySelectorAll('.t3js-feedback-options-container .t3js-item-container');

            if (this.options.length === 2) {
                this.setRemoveButtonDisplay('none');
            }

            if (this.options.length < 5) {
                document.querySelector('#add-feedback-option-button').style.display = 'inline-block';
            }
        }.bind(this));

        this.options.forEach((item, index) => {

            item.classList.add('t3js-options-item-' + index);
            let btn = document.createElement('a');
            btn.classList.add(
                'btn',
                'btn-default',
                'btn-sm',
                't3js-options-item-remove',
                't3js-iterable',
                'mt-4'
            );
            if (this.options.length <= 2) {
                btn.style.display = 'none';
            }
            btn.dataset.template = 't3js-options-item-[i]';
            btn.dataset.target = 't3js-options-item-' + index;
            btn.dataset.iterationTarget = 'target';
            btn.href = 'javascript:;';
            btn.innerHTML = '<span class="fa fa-close"></span>';

            item.querySelector('.t3js-options-controls-container').append(btn);
        })
    };

    FeedbackOptionHandler.setIterations = function (item, iteration) {
        let iterables = item.querySelectorAll('.t3js-iterable');

        if (item.classList.contains('t3js-iterable')) {
            console.log('this should do rly crazy stuff! For i = ' + iteration);
            item.classList.remove(item.dataset.template.replace('[i]', iteration - 1));
            item.classList.add(item.dataset.template.replace('[i]', iteration));
        }
        console.log(item);

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

    FeedbackOptionHandler.setRemoveButtonDisplay = function (displayToSet) {
        const buttons = document.querySelectorAll('.t3js-options-item-remove');

        for (const button of buttons) {
            button.style.display = displayToSet;
        }
    };

    FeedbackOptionHandler.addNewButton = function () {

        this.container.addEventListener('click', function(ev) {

            let node = ev.target;
            if (! node.classList.contains('t3js-options-item-new')) {
                return;
            }

            let itemContainer = this.options.item(this.itemCount() - 1).cloneNode(true);
            let inputFields = itemContainer.querySelectorAll('input');
            inputFields.forEach( (item) => item.value = '' );
            this.setIterations(itemContainer, this.itemCount());

            function insertAfter(newNode, existingNode) {
                existingNode.parentNode.insertBefore(newNode, existingNode.nextSibling);
            }

            insertAfter(itemContainer, this.options.item(this.itemCount() - 1));
            this.options = document.querySelectorAll('.t3js-feedback-options-container .t3js-item-container');
            if (this.options.length > 2) {
                this.setRemoveButtonDisplay('inline-block');
            }
            if (this.options.length === 5) {
                document.querySelector('#add-feedback-option-button').style.display = 'none';
            }

        }.bind(this));

        let btn = document.createElement('a');
        btn.id = 'add-feedback-option-button';
        btn.classList.add('btn');
        btn.classList.add('btn-default');
        btn.classList.add('btn-sm');
        btn.classList.add('t3js-options-item-new');
        btn.classList.add('mt-3');
        btn.href = 'javascript:;';
        btn.textContent = 'Option hinzufügen';
        this.container.append(btn);
    };

    FeedbackOptionHandler.init();

    return FeedbackOptionHandler;
});