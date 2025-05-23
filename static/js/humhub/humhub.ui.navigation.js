humhub.module('ui.navigation', function (module, require, $) {

    var event = require('event');

    var init = function () {
        module.initTopNav();
    };

    var initTopNav = function () {
        // Default implementation for topbar. Activate li on click.
        $('#top-menu-nav a:not(#space-menu):not(#top-dropdown-menu):not([data-action-click="ui.modal.load"])').on('click', function () {
            module.setActiveItem($(this));
        });

        event.on('humhub:ready', function () {
            // Activate by config
            $.each(module.config['active'], function (id, url) {
                module.setActive(id, url);
            });
            // Reset active config.
            module.config['active'] = undefined;
        });
    };

    var setActive = function (id, item) {
        if (!id) {
            return;
        }

        if (!item) {
            module.setActiveItem(null);
            return;
        }

        var $menu = $('#' + id);
        var $item = null;

        if (item.id) {
            $item = $menu.find('[data-menu-id="' + item.id + '"]');
        }

        if ((!$item || !$item.length) && item.url) {
            $item = $menu.find('[href="' + item.url + '"]');
        }

        module.setActiveItem($item);
    };

    var setActiveItem = function ($item) {
        $('#top-menu-nav li').removeClass('active');

        if (!$item || !$item.length) {
            return;
        }

        $item.each(function () {
            var $this = $(this);
            $this.closest('ul').find('li').removeClass('active');
            $this.closest('ul').find('a').removeClass('active');
            $this.closest('li').addClass('active');
            $this.addClass('active');
            $this.trigger('blur');
        });
    };

    module.export({
        init: init,
        sortOrder: 100,
        setActive: setActive,
        initTopNav: initTopNav,
        setActiveItem: setActiveItem
    });
});
