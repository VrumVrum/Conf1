define(['jquery', 'fotorama/fotorama'], function($) {
    'use strict';
    return function(galleryData) {
        // Încercăm ambele selectoare posibile în Magento 2
        var $gallery = $('[data-gallery-role="gallery-placeholder"], .gallery-placeholder');
        
        if ($gallery.length) {
            var fotorama = $gallery.data('fotorama');
            if (fotorama) {
                fotorama.load(galleryData);
            } else {
                // Dacă nu e inițializat, așteptăm evenimentul nativ
                $gallery.on('gallery:loaded', function() {
                    $(this).fotorama('load', galleryData);
                });
            }
        }
    };
});
