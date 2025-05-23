/**
 * Manages the client/server communication. Handles humhub json api responses and
 * pjax requests.
 */
humhub.module('client', function (module, require, $) {
    var object = require('util').object;
    var event = require('event');
    var action = require('action');
    var additions = require('ui.additions');
    var view = require('ui.view');

    var HEADER_VIEW_CONTEXT = 'HUMHUB-VIEW-CONTEXT';

    /**
     * Response Wrapper Object for easily accessing common data
     */
    var Response = function (xhr, url, textStatus, dataType) {
        this.url = url;
        this.status = xhr.status;
        this.response = xhr.responseJSON || xhr.responseText;
        //Textstatus = "timeout", "error", "abort", "parsererror", "application"
        this.textStatus = textStatus;
        this.xhr = xhr;

        if (!dataType) {
            var responseType = this.header('content-type');
            if (responseType && responseType.indexOf('json') > -1) {
                dataType = 'json';
            } else if (responseType && responseType.indexOf('html') > -1) {
                dataType = 'html';
            } else if (!this.isAbort() && !this.isError()) {
                console.error('Unable to determine dataType from response, this may cause problems.');
            }
        }

        this.dataType = dataType;

        // If we expect json and received json we merge the json result with our response object.
        if (this.dataType === 'json') {
            $.extend(this, this.response);
        } else if (this.dataType === 'html') {
            this[dataType] = this.response;
        }
    };

    Response.prototype.isAbort = function () {
        return this.textStatus === "abort";
    };

    Response.prototype.isError = function () {
        return this.textStatus === "error";
    };

    Response.prototype.header = function (key) {
        return this.xhr.getResponseHeader(key);
    };

    Response.prototype.setSuccess = function (data) {
        this.data = data;
        return this;
    };

    Response.prototype.setError = function (errorThrown) {
        try {
            this.error = JSON.parse(this.response);
        } catch (e) {/* Nothing todo... */
        }

        this.error = this.error || {};
        this.errorThrown = errorThrown;
        this.validationError = (this.status === 400);
        return this;
    };

    Response.prototype.isError = function () {
        return this.status >= 400;
    };

    Response.prototype.getLog = function () {
        var result = $.extend({}, this);

        if (this.response && object.isString(this.response)) {
            result.response = this.response.substr(0, 500)
            result.response += (this.response.length > 500) ? '...' : '';
        }

        if (this.html && object.isString(this.html)) {
            result.html = this.html.substr(0, 500)
            result.html += (this.html.length > 500) ? '...' : '';
        }

        return result;
    };

    var reload = function (preventPjax) {
        if (!preventPjax && module.pjax.config.active) {
            module.pjax.reload();
        } else {
            location.reload(true);
        }
    };

    var submit = function ($form, cfg, originalEvent) {
        if ($form instanceof $.Event && $form.$form && $form.length) {
            originalEvent = $form;
            $form = $form.$form;
        } else if ($form instanceof $.Event && $form.$trigger) {
            originalEvent = $form;
            $form = $form.$trigger.closest('form');
        } else if (cfg instanceof $.Event) {
            originalEvent = cfg;
            cfg = {};
        } else if ($form.url) {
            // Create a post form
            $form = $('<form>', {
                action: $form.url,
                method: 'post'
            });
            $form.appendTo('body');
        }

        cfg = cfg || {};
        $form = object.isString($form) ? $($form) : $form;

        if (!$form || !$form.length) {
            return Promise.reject('Could not determine form for submit action.');
        }

        cfg.type = $form.attr('method') || 'post';
        cfg.data = $form.serialize();


        var url = cfg.url;
        if (!url && (originalEvent && originalEvent.url)) {
            url = originalEvent.url;
        } else if (!url && $form) {
            url = $form.attr('action');
        }

        return ajax(url, cfg, originalEvent);
    };

    var actionPost = function (evt) {
        post(evt).catch(function (e) {
            module.log.error(e, true);
        });
    };

    var post = function (url, cfg, originalEvent) {
        if (url instanceof $.Event) {
            originalEvent = url;
            url = originalEvent.url || cfg.url;
        } else if (cfg instanceof $.Event) {
            originalEvent = cfg;
            cfg = {};
        } else if (!object.isString(url)) {
            cfg = url;
            url = cfg.url;
        }

        cfg = cfg || {};
        cfg.type = cfg.method = 'POST';
        return ajax(url, cfg, originalEvent);
    };

    var html = function (url, cfg, originalEvent) {
        if (url instanceof $.Event) {
            originalEvent = url;
            url = originalEvent.url || cfg.url;
        } else if (cfg instanceof $.Event) {
            originalEvent = cfg;
            cfg = {};
        } else if (!object.isString(url)) {
            cfg = url;
            url = cfg.url;
        }

        cfg = cfg || {};
        cfg.type = cfg.method = 'GET';
        cfg.dataType = 'html';
        return get(url, cfg, originalEvent);
    };

    var get = function (url, cfg, originalEvent) {
        if (url instanceof $.Event) {
            originalEvent = url;
            url = originalEvent.url || cfg.url;
        } else if (cfg instanceof $.Event) {
            originalEvent = cfg;
            cfg = {};
        } else if (url && !object.isString(url)) {
            cfg = url;
            url = cfg.url;
        }

        cfg = cfg || {};
        cfg.type = cfg.method = 'GET';
        return ajax(url, cfg, originalEvent);
    };

    var json = function (url, cfg, originalEvent) {
        return new Promise(function (resolve, reject) {
            get(url, cfg, originalEvent).then(function (response) {
                resolve(response.data);
            }).catch(reject)
        });
    };

    var ajax = function (url, cfg, originalEvent) {
        // support for ajax(url, event) and ajax(path, successhandler);
        if (cfg instanceof $.Event) {
            originalEvent = cfg;
            cfg = {};
        } else if (object.isFunction(cfg)) {
            cfg = {'success': cfg};
        }

        var viewContext = cfg.viewContext || view.getViewContext();

        if (viewContext) {
            if (!cfg['headers']) {
                cfg['headers'] = {};
            }
            cfg['headers'][HEADER_VIEW_CONTEXT] = viewContext
        }

        var requestXhr = null;

        var promise = new Promise(function (resolve, reject) {
            cfg = cfg || {};

            // allows data-action-data-type="json" on $trigger
            if (originalEvent && object.isFunction(originalEvent.data)) {
                cfg.dataType = originalEvent.data('data-type', cfg.dataType);
            }

            var errorHandler = cfg.error;
            var error = function (xhr, textStatus, errorThrown) {
                var response = new Response(xhr, url, textStatus, cfg.dataType).setError(errorThrown);
                if (response.status == 302) {
                    _redirect(xhr);
                    resolve(response);
                    return;
                }

                if (errorHandler && object.isFunction(errorHandler)) {
                    errorHandler(response);
                }

                finish(originalEvent);
                reject(response);
            };

            var successHandler = cfg.success;
            var success = function (data, textStatus, xhr) {
                var response = new Response(xhr, url, textStatus, cfg.dataType).setSuccess(data);
                if (successHandler) {
                    successHandler(response);
                }

                finish(originalEvent);
                resolve(response);

                // Other modules can register global handler by the response type given by the backend.
                // For example {type:'modal', 'content': '...')
                if (response.type) {
                    event.trigger('humhub:modules:client:response:' + response.type);
                }

                promise.done(function () {
                    // If content with <link> tags are inserted in resolve, the ajaxComplete handler in yii.js
                    // makes sure redundant stylesheets are removed. Here we make sure it is called after inserting the response.
                    $(document).trigger('ajaxComplete');
                });
            };

            var beforeSendHandler = cfg.beforeSend;
            var beforeSend = function (xhr, settings) {
                if (beforeSendHandler) {
                    beforeSendHandler(xhr, settings);
                }

                requestXhr = xhr;
            };

            //Overwriting the handler with our wrapper handler
            cfg.success = success;
            cfg.error = error;
            cfg.beforeSend = beforeSend;
            cfg.url = url;

            $.ajax(cfg);
        });

        promise.status = function (setting) {
            return new Promise(function (resolve, reject) {
                promise.then(function (response) {
                    try {
                        if (setting[response.status]) {
                            setting[response.status](response);
                        }
                        resolve(response);
                    } catch (e) {
                        reject(e);
                    }
                }).catch(function (response) {
                    try {
                        if (setting[response.status]) {
                            setting[response.status](response);
                            resolve(response);
                        } else {
                            reject(response);
                        }
                    } catch (e) {
                        reject(e);
                    }
                });
            });
        };

        promise.abort = function () {
            if (requestXhr) {
                requestXhr.abort();
            }
        };

        return promise;
    };

    var _redirect = function (xhr) {
        var url = null;
        if (xhr.getResponseHeader('X-Pjax-Url')) {
            url = xhr.getResponseHeader('X-Pjax-Url');
        } else {
            url = xhr.getResponseHeader('X-Redirect');
        }

        redirect(url);
    };

    var redirect = function (url) {
        if (!url) {
            return;
        }

        url = object.isString(url) ? url : url.url;

        if (object.isString(url)) {
            if (module.pjax && module.pjax.config.active) {
                module.pjax.redirect(url);
            } else {
                document.location = url;
            }
            return;
        }
    };

    var finish = function (originalEvent) {
        if (originalEvent && object.isFunction(originalEvent.finish) && originalEvent.block !== 'manual') {
            originalEvent.finish();
        }
    };

    var back = function () {
        history.back();
    };

    var onBeforeLoad = function (form, msg, msgModal) {
        var $form = $(form);

        if (!$form.is('form')) {
            $form = $form.find('form');
        }

        if (!$form.length || !$form.is('form')) {
            return;
        }

        $form.data('state', serializeFormState($form));

        msg = msg || module.text('warn.onBeforeLoad');
        msgModal = msgModal || module.text('warn.onBeforeCloseModal');

        $form.resetChanges = function() {
            $form.data('state', null);
        }
        $form.on('submit', $form.resetChanges);
        $form.find('[type=submit]').on('click', $form.resetChanges);

        // Note some browser do not support custom messages for this event.
        $(window).on('beforeunload.humhub_client', function () {
            if (formStateChanged($form)) {
                return msg;
            }
        });

        var confirmFormChanges = function (evt, message) {
            if (unloadForm($form, message)) {
                $form.resetChanges();
            } else {
                evt.preventDefault();
            }
        }

        $(document).on('pjax:beforeSend.humhub_client', function (evt) {
            confirmFormChanges(evt, msg);
        });

        $(document).on('hide.bs.modal', '.modal', function (evt) {
            if ($form.closest('.modal').length) {
                confirmFormChanges(evt, msgModal);
            }
        });
    };

    var serializeFormState = function ($form) {
        return $form.find('input, select, textarea').not('[data-prevent-statechange]').serialize();
    };

    var unloadForm = function ($form, msg) {
        return !formStateChanged($form) || confirmUnload(msg)
    };

    var formStateChanged = function ($form) {
        $form.find(':focus').blur(); // Unfocus in order to update textarea value from RichText editor
        return $form.data('state') && $form.data('state') !== serializeFormState($form);
    };

    var confirmUnload = function (msg) {
        if (!module.confirmedMessages) {
            module.confirmedMessages = {};
        }

        msg = msg || module.text('warn.onBeforeLoad');

        if (module.confirmedMessages[msg] && (Date.now() - module.confirmedMessages[msg].time < 100)) {
            // Don't ask the same confirmation message twice if it was answered recently
            // because several forms exist on the current page with confirm option
            return module.confirmedMessages[msg].result;
        }

        var confirmedResult = window.confirm(msg);

        module.confirmedMessages[msg] = {time: Date.now(), result: confirmedResult};

        return confirmedResult;
    };

    var offBeforeLoad = function () {
        $(window).off('beforeunload.humhub_client');
        $(document).off('pjax:beforeSend.humhub_client');
    };

    module.initOnPjaxLoad = true;

    var init = function (isPjax) {
        if (!isPjax) {
            if (module.config.reloadableScripts) {
                $.extend(yii.reloadableScripts, module.config.reloadableScripts)
            }

            action.registerHandler('post', function (evt) {
                evt.block = 'manual';
                module.post(evt).then(function (resp) {
                    module.log.debug('post success', resp, true);
                }).catch(function (err) {
                    evt.finish();
                    module.log.error(err, true);
                });
            });

            additions.register('acknowledgeForm', function ($match) {
                onBeforeLoad($match, ($match.data('acknowledgeMessage') || null));
            });

        } else {
            offBeforeLoad();
        }
    };

    module.export({
        ajax: ajax,
        back: back,
        actionPost: actionPost,
        post: post,
        get: get,
        html: html,
        reload: reload,
        submit: submit,
        init: init,
        sortOrder: 100,
        json: json,
        Response: Response,
        onBeforeLoad: onBeforeLoad,
        offBeforeLoad: offBeforeLoad,
        unloadForm: unloadForm,
        redirect: redirect
    });
});
