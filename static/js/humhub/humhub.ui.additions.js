/**
 * This module manages UI-Additions registered by other modules. Additions can be applied to DOM elements
 * and are used to add a specific behaviour or manipulate the element itself. e.g: Richtext, Autosize input...
 *
 * An addition can be registered for a specific selector e.g: <input data-addition-richtext ... />
 * It is possible to register multiple additions for the same selector.
 */
humhub.module('ui.additions', function (module, require, $) {

    var event = require('event');
    var object = require('util.object');

    var _additions = {};
    var _order = [];

    /**
     * Registers an addition for a given jQuery selector. There can be registered
     * multiple additions for the same selector.
     *
     * @returns {undefined}
     * @param id
     * @param selector
     * @param handler
     * @param options
     */
    var register = function (id, selector, handler, options) {
        // Register an addition without selector data-ui-addition="additionId"
        if(object.isFunction(selector)) {
            options = handler;
            handler = selector;
            selector = null;
        }

        var hasSelector = selector != null && object.isDefined(selector);

        options = options || {};

        if (!_additions[id] || options.overwrite) {
            _additions[id] = {
                'selector': selector,
                'handler': handler
            };

            if(hasSelector && options.after && _additions[options.after]) {
                _order.splice(_order.indexOf(options.after) + 1, 0, id);
            } else if(hasSelector && options.before &&  _additions[options.before]) {
                _order.splice(_order.indexOf(options.before), 0, id);
            } else if(hasSelector) {
                _order.push(id);
            }

            // Make sure additions registrated after humhub:ready also affect element
            if (humhub.initialized) {
                if(hasSelector) {
                    apply($('body'), id);
                } else {

                    apply($('body'), 'addition', '[data-ui-addition="'+id+'"]');
                }

            }
        } else if (options.extend) {
            options.selector = selector;
            module.extend(id, handler, options);
        }
    };

    /**
     * Applies all matched additions to the given element and its children
     * @param {type} element
     * @param options
     * @returns {undefined}
     */
    var applyTo = function (element, options) {
        options = options || {};

        var $element = (element instanceof $) ? element : $(element);

        $.each(_order, function (index, id) {
            // Only apply certain filter if filter option is set
            if ((options.filter && options.filter.indexOf(id) < 0) || (options.include && options.include.indexOf(id) < 0)) {
                return;
            }

            if (options.exclude && options.exclude.indexOf(id) >= 0) {
                return;
            }

            try {
                module.apply($element, id);
            } catch (e) {
                module.log.error('Error while applying addition ' + id, e);
            }
        });
    };

    /**
     * Applies a given addition to all matches of the given $element.
     * @param {type} $element
     * @param {type} selector
     * @param {type} addition
     * @returns {undefined}
     */
    var apply = function ($element, id, selector) {
        var addition = _additions[id];

        if (!addition) {
            return;
        }

        selector = object.isDefined(selector) ? selector : addition.selector;

        var $match = $element.find(selector).addBack(selector);

        // only apply addition if we actually find a match
        if (!$match.length) {
            return;
        }

        addition.handler.apply($match, [$match, $element]);
    };

    var applyAddition = function($element, id) {
        var addition = _additions[id];

        if (!addition) {
            return;
        }

        addition.handler.apply($element, [$element, $element]);
    };

    var init = function () {
        event.on('humhub:ready', function (evt) {
            module.applyTo($('body'));
        });

        require('action').registerHandler('copyToClipboard', function (evt) {
            clipboard.writeText(evt.$target.text()).then(function () {
                require('ui.status').success(module.text('success.clipboard'));
            }).catch(function (err) {
                require('ui.status').error(module.text('error.clipboard'), true);
            });
        });

        // Workaround: Bootstrap bug with dropdowns in responsive tables
        // See: https://github.com/twbs/bootstrap/issues/11037
        $(document).on('shown.bs.dropdown', '.table-responsive', function (e) {
            var t = $(this),
                    m = $(e.target).find('.dropdown-menu'),
                    tb = t.offset().top + t.height(),
                    mb = m.offset().top + m.outerHeight(true),
                    d = 20; // Space for shadow + scrollbar.
            if (t[0].scrollWidth > t.innerWidth()) {
                if (mb + d > tb) {
                    t.css('padding-bottom', ((mb + d) - tb));
                }
            } else {
                t.css('overflow', 'visible');
            }
        }).on('hidden.bs.dropdown', '.table-responsive', function () {
            $(this).css({'padding-bottom': '', 'overflow': ''});
        });


        // workaround for jp-player since it sets display to inline which results in a broken view...
        $(document).on('click.humhub-jp-play', '.jp-play', function () {
            $(this).closest('.jp-controls').find('.jp-pause').css('display', 'block');
        });

        module.register('addition', '[data-ui-addition]', function($match) {
            $match.each(function(i, e) {
                var $this = $(this);
                applyAddition($(this), $this.data('uiAddition'));
            });
        });

        // Autosize textareas
        module.register('autosize', '.autosize', function ($match) {
            $match.autosize();
        });

        module.register('timeago', function($match) {
            $match.timeago();
        });

        module.register('select2', '[data-ui-select2]', function ($match) {
            const templateItem = function (item) {
                const element = $(item.element);
                return element.data('color')
                    ? $('<span><span class="picker-color" style="background:' + element.data('color') + '"></span> ' + item.text + '</span>')
                    : item.text;
            };

            $match.each(function () {
                $(this).select2({
                    theme: 'humhub',
                    tags: typeof $(this).data('ui-select2-allow-new') !== 'undefined',
                    insertTag: function (data, tag) {
                        if (typeof $(this).data('ui-select2-new-sign') !== 'undefined') {
                            tag.text += ' ' + $(this).data('ui-select2-new-sign');
                        }
                        data.unshift(tag);
                    },
                    templateResult: templateItem,
                    templateSelection: templateItem,
                });
            });
        });

        module.register('highlightCode', 'pre code', function($match) {
            $match.each(function (i, e) {
                if(window.hljs) {
                    hljs.highlightBlock(e);
                }
            });
        });

        // Show tooltips on elements
        module.register('tooltip', '.tt', function ($match) {
            $match.tooltip({
                container: 'body'
            });

            $match.on('click.tooltip', function () {
                $('.tooltip').remove();
            });
        });

        $(document).on('click.humhub-ui-additions', function () {
            $('.tooltip').remove();
            $('.popover:not(.tour,.prevClose)').remove();
        });

        // Show popovers on elements
        module.register('popover', '.po', function ($match) {
            $match.popover({html: true});
        });

        // Deprecated!
        module.register('', 'a[data-loader="modal"], button[data-loader="modal"]', function ($match) {
            $match.loader();
        });
    };

    var extend = function (id, handler, options) {
        options = options || {};

        if (_additions[id]) {
            var addition = _additions[id];
            if (options.prepend) {
                addition.handler = object.chain(addition.handler, handler, addition.handler);
            } else {
                addition.handler = object.chain(addition.handler, addition.handler, handler);
            }

            if (options.selector && options.selector !== addition.selector) {
                addition.selector += ',' + options.selector;
            }

            if (options.applyOnInit) {
                module.apply('body', id);
            }

        } else if (options.selector) {
            options.extend = false; // Make sure we don't get caught in a loop somehow.
            module.register(id, options.selector, handler, options);
        }
    };

    /**
     * Cleanup some nodes required to prevent memoryleaks in pjax mode.
     * @returns {undefined}
     */
    var unload = function () {
        // Tooltip issue
        // http://stackoverflow.com/questions/24841028/jquery-tooltip-add-div-role-log-in-my-page
        // https://bugs.jqueryui.com/ticket/10689
        $(".ui-helper-hidden-accessible").remove();

        // Jquery date picker div is not removed...
        $('#ui-datepicker-div').remove();

        $('.popover').remove();
        $('.tooltip').remove();
    };

    var switchButtons = function (outButton, inButton, cfg) {
        cfg = cfg || {};
        var animation = cfg.animation || 'bounceIn';
        var $out = (outButton instanceof $) ? outButton : $(outButton);
        var $in = (inButton instanceof $) ? inButton : $(inButton);

        $out.hide();
        if (cfg.remove) {
            $out.remove();
        }

        $in.addClass('animated ' + animation).show();
    };

    var highlight = function (node) {
        var $node = (node instanceof $) ? node : $(node);
        $node.addClass('highlight');
        $node.delay(200).animate({backgroundColor: 'transparent'}, 1000, function () {
            $node.removeClass('highlight');
            $node.css('backgroundColor', '');
        });
    };

    var highlightWords = function (node, words, minWordLength) {
        var $node = node instanceof $ ? node : $(node);
        if (!$node.length || typeof($node.highlight) !== 'function') {
            return;
        }

        if (typeof words === 'string' && words !== '') {
            words = words.match(/[^\s]+\/[^\s]+|"[^"]+"|[\p{L}\d]+(?:['’`]\p{L}+)?/gu);
            if (Array.isArray(words)) {
                words = words.map(item => item.replace(/"/g, ''));
                words = [...new Set(words)].sort((a, b) => b.length - a.length);
            }
        }
        if (!Array.isArray(words)) {
            return;
        }

        if (typeof minWordLength !== 'number') {
            minWordLength = 3;
        }

        words.forEach(function (word) {
            if (word.length < minWordLength) {
                return;
            }
            $node.highlight(word);
            word.indexOf("'") > -1 && $node.highlight(word.replace("'", '’'));
            word.indexOf("’") > -1 && $node.highlight(word.replace('’', "'"));
        });
    };

    var observe = function (node, options) {
        if (object.isBoolean(options)) {
            options = {applyOnInit: options};
        } else if (!options) {
            options = {};
        }

        var $node = $(node);
        node = $node[0];
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                var $nodes = $(mutation.addedNodes).filter(function () {
                    return this.nodeType === 1 && !$(this).closest('.humhub-ui-richtext').length; // filter out text nodes and ignore richtext changes
                });

                $nodes.each(function () {
                    var $this = $(this);
                    module.applyTo($this);
                })
            });
        });

        observer.observe(node, {childList: true, subtree: true});

        if (options.applyOnInit) {
            module.applyTo(node, options);
        }

        return node;
    };

    module.export({
        init: init,
        sortOrder: 100,
        observe: observe,
        unload: unload,
        applyTo: applyTo,
        apply: apply,
        extend: extend,
        register: register,
        switchButtons: switchButtons,
        highlight: highlight,
        highlightWords: highlightWords,
    });
});

/**
 * Context Menu
 */
(function ($, window) {
    $.fn.contextMenu = function (settings) {
        return this.each(function () {

            // Open context menu
            $(this).on("contextmenu",
                    function (e, togglerEvent) {
                        // return native menu if pressing control
                        if (e.ctrlKey) {
                            return;
                        }

                        // Make sure all menus are hidden
                        $('.contextMenu').hide();

                        var menuSelector = settings.getMenuSelector.call(this, $(e.target));

                        var oParent = $(menuSelector).parent().offsetParent().offset();
                        var posTop = (togglerEvent && togglerEvent.clientY ? togglerEvent.clientY : e.clientY) - oParent.top;
                        var posLeft = (togglerEvent && togglerEvent.clientX ? togglerEvent.clientX : e.clientX) - oParent.left;

                        // open menu
                        var $menu = $(menuSelector).data("invokedOn", $(e.target)).show().css({
                            position: "absolute",
                            left: getMenuPosition(posLeft, 'width', 'scrollLeft'),
                            top: getMenuPosition(posTop, 'height', 'scrollTop')
                        }).off('click').on('click', 'a', function (e) {
                            $menu.hide();

                            var $invokedOn = $menu.data("invokedOn");
                            var $selectedMenu = $(e.target);

                            settings.menuSelected.call(this, $invokedOn, $selectedMenu, e);
                        });

                        var menuShift = $menu.offset().left + $menu.outerWidth() - $(window).width();
                        if (menuShift > 0) {
                            $menu.css('left', $menu.position().left - menuShift - 5);
                        }

                        return false;
                    });

            $(document).on('click', '[data-contextmenu-toggler]', function (e) {
                $(this).closest($(this).data('contextmenu-toggler')).triggerHandler('contextmenu', e);
            });

            // make sure menu closes on any click
            $(document).click(function (e) {
                if (!$(e.target).closest('[data-contextmenu-toggler]').length) {
                    $('.contextMenu').hide();
                }
            });
        });

        function getMenuPosition(mouse, direction, scrollDir) {
            var win = $(window)[direction]();
            var scroll = $(window)[scrollDir]();
            var menu = $(settings.menuSelector)[direction]();
            var position = mouse + scroll;

            // opening menu would pass the side of the page
            if (mouse + menu > win && menu < mouse)
                position -= menu;

            return position;
        }

    };
})(jQuery, window);
