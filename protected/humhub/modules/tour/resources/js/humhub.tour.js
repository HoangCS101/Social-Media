humhub.module('tour', function (module, requrie, $) {

    var client = requrie('client');
    var tourOptions;
    var completedUrl;
    var nextUrl;

    var start = function (options) {
        new Tour({
            storage: false,
            template: module.config.template,
            steps: options.steps,
            framework: "bootstrap3",
            name: options.name,
            sanitizeWhitelist: {'a': ['data-action-click']},
            onShown: function () {
                $('.tour button[data-role].disabled').remove();
            },
            onEnd: tourCompleted
        }).start();

        tourOptions = options;
        completedUrl = options.completedUrl;
        nextUrl = options.nextUrl;
    };

    /**
     * Set tour as seen
     */
    function tourCompleted(next) {
        // load user spaces
        client.post(module.config.completedUrl, {data: {section: tourOptions.name}}).then(function () {
            // cross out welcome tour entry
            $('#interface_entry').addClass('completed');

            if (next === true && nextUrl) {
                window.location.href = nextUrl;
            } else {
                window.location.href = module.config.dashboardUrl;
            }
        });
    }

    var next = function () {
        tourCompleted(true);
    };

    var hidePanel = function (event) {
        $(".panel-tour").slideToggle("slow");
        client.post(event)
    }

    module.export({
        start: start,
        next: next,
        hidePanel: hidePanel,
    });
});
