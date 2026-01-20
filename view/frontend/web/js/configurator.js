define(['jquery', 'mustache', 'Flo_Configurator/js/gallery-updater'], function($, Mustache, updateGallery) {
    'use strict';
    return {
        updateProductGallery: function(optionId) {
            console.log('Cerem imagini pentru ID:', optionId);
            $.ajax({
                url: window.location.origin + '/en/flo_configurator/index/getoptions',
                data: { option_id: optionId },
                type: 'GET',
                success: function(response) {
                    if (response.success && response.gallery && response.gallery.length > 0) {
                        console.log('Incarcam galeria cu:', response.gallery);
                        updateGallery(response.gallery);
                    } else {
                        console.log('Nu am gasit imagini mapate pentru acest ID.');
                    }
                }
            });
        },
        renderOptions: function(container, options) {
            var template = '{{#items}}' +
                '<div class="option-item" data-option-id="{{id}}">' +
                    '{{#image}}<div class="option-image"><img src="{{image}}"></div>{{/image}}' +
                    '<div class="option-label">{{label}}</div>' +
                '</div>' +
            '{{/items}}';
            var html = Mustache.render(template, {items: options});
            $(container).find('.options-grid').html(html);
        }
    };
});
