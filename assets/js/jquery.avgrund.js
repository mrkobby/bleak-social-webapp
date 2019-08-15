(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory;
    } else {
        factory(jQuery);
    }
}(function ($) {
    $.fn.avgrund = function (options) {
        var defaults = {
            width: 640, // max = 640
            height: 350, // max = 350
            showClose: false,
            showCloseText: '',
            closeByEscape: true,
            closeByDocument: true,
            holderClass: '',
            overlayClass: '',
            enableStackAnimation: false,
            onBlurContainer: '',
            openOnEvent: true,
            setEvent: 'click',
            onLoad: false,
            onUnload: false,
            onClosing: false,
            template: '<p>This is test popin content!</p>'
        };

        options = $.extend(defaults, options);

        return this.each(function () {
            var self = $(this),
                body = $('body'),
                maxWidth = options.width > 640 ? 640 : options.width,
                maxHeight = options.height > 350 ? 350 : options.height,
                template = typeof options.template === 'function' ? options.template(self) : options.template;

            body.addClass('blurme-ready');

            if ($('.blurme-overlay').length === 0) {
                body.append('<div class="blurme-overlay ' + options.overlayClass + '"></div>');
            }

            if (options.onBlurContainer !== '') {
                $(options.onBlurContainer).addClass('blurme-blur');
            }

            function onDocumentKeyup (e) {
                if (options.closeByEscape) {
                    if (e.keyCode === 27) {
                        deactivate();
                    }
                }
            }

            function onDocumentClick (e) {
                if (options.closeByDocument) {
                    if ($(e.target).is('.blurme-overlay, .blurme-close')) {
                        e.preventDefault();
                        deactivate();
                    }
                } else if ($(e.target).is('.blurme-close')) {
                        e.preventDefault();
                        deactivate();
                }
            }

            function activate () {
                if (typeof options.onLoad === 'function') {
                    options.onLoad(self);
                }

                setTimeout(function () {
                    body.addClass('blurme-active');
                }, 100);

                var $popin = $('<div class="blurme-popin ' + options.holderClass + '"></div>');
                $popin.append(template);
                body.append($popin);

                $('.blurme-popin').css({
                    'width': maxWidth + 'px',
                    'height': maxHeight + 'px',
                    'margin-left': '-' + (maxWidth / 2 + 10) + 'px',
                    'margin-top': '-' + (maxHeight / 2 + 10) + 'px'
                });

                if (options.showClose) {
                    $('.blurme-popin').append('<a href="#" class="blurme-close">' + options.showCloseText + '</a>');
                }

                if (options.enableStackAnimation) {
                    $('.blurme-popin').addClass('stack');
                }

                body.bind('keyup', onDocumentKeyup)
                    .bind('click', onDocumentClick);
            }

            function deactivate () {
                if (typeof options.onClosing === 'function') {
                    if (!options.onClosing(self)) {
                        return false;
                    }
                }

                body.unbind('keyup', onDocumentKeyup)
                    .unbind('click', onDocumentClick)
                    .removeClass('blurme-active');

                setTimeout(function () {
                    $('.blurme-popin').remove();
                }, 500);

                if (typeof options.onUnload === 'function') {
                    options.onUnload(self);
                }
            }

            if (options.openOnEvent) {
                self.bind(options.setEvent, function (e) {
                    e.stopPropagation();

                    if ($(e.target).is('a')) {
                        e.preventDefault();
                    }

                    activate();
                });
            } else {
                activate();
            }
        });
    };
}));
