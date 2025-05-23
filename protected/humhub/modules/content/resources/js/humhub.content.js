/**
 * This module provides an api for handling content objects e.g. Posts, Polls...
 *
 * @type undefined|Function
 */

humhub.module('content', function (module, require, $) {
    var client = require('client');
    var util = require('util');
    var string = util.string;
    var actions = require('action');
    var Component = actions.Component;
    var event = require('event');
    var modal = require('ui.modal');
    var status = require('ui.status');

    var DATA_CONTENT_KEY = "content-key";
    var DATA_CONTENT_DELETE_URL = "content-delete-url";
    var DATA_ADMIN_DELETE_MODAL_URL = "admin-delete-modal-url";


    Component.addSelector('content-component');

    var Content = Component.extend('Content');

    /**
     * Abstract loader function which can be used to activate or deactivate a
     * loader within a content entry.
     *
     * If $show is undefined or true the loader animation should be rendered
     * otherwise it should be removed.
     *
     * @param {type} $show
     * @returns {undefined}
     */
    Content.prototype.loader = function ($show) { /* Abstract loader function */
    };
    Content.prototype.edit = function (successHandler) {/** Abstract edit function **/
    };

    Content.getNodeByKey = function (key) {
        return $('[data-content-key="' + key + '"]');
    };

    Content.prototype.getKey = function () {
        return this.$.data(DATA_CONTENT_KEY);
    };

    Content.prototype.create = function (addContentHandler) {
        //Note that this Content won't have an id, so the backend will create an instance
        if (this.hasAction('create')) {
            return;
        }

        this.edit(addContentHandler);
    };

    Content.prototype.delete = function (options) {
        options = options || {};

        var that = this;
        return new Promise(function (resolve, reject) {

            var modalOptions = options.modal || module.config.modal.deleteConfirm;

            if (options.$trigger && options.$trigger.is('[data-action-confirm]')) {
                that.deleteContent(resolve, reject);
            } else {
                modal.confirm(modalOptions).then(function ($confirmed) {
                    if (!$confirmed) {
                        resolve(false);
                        return;
                    }

                    that.deleteContent(resolve, reject);
                });
            }
        });
    };

    Content.prototype.adminDelete = function (options) {
        var that = this;

        var loadModalUrl = that.data(DATA_ADMIN_DELETE_MODAL_URL) || module.config.adminDeleteModalUrl;

        if (!loadModalUrl) {
            that.delete(options);
            return;
        }

        return new Promise(function (resolve, reject) {
            client.post(loadModalUrl, {
                data: {
                    id: that.getKey()
                }
            }).then(function (response) {
                modal.confirm(response).then(function ($confirmed) {
                    if (!$confirmed) {
                        resolve(false);
                        return;
                    }

                    var form = modal.globalConfirm.$.find('form')[0];

                    that.deleteContent(resolve, reject, form);
                });
            }).catch(function (err) {
                reject(err);
            });
        });
    };

    Content.prototype.deleteContent = function (resolve, reject, form) {
        var that = this;
        that.loader();
        var deleteUrl = that.data(DATA_CONTENT_DELETE_URL) || module.config.deleteUrl;

        var postData = {
            id: that.getKey()
        };

        if (typeof form !== 'undefined') {
            Object.assign(postData, {
                ...$(form).serializeArray().reduce(function (a, e) {
                    a[e.name] = e.value;
                    return a;
                })
            });
        }

        if (deleteUrl) {
            client.post(deleteUrl, {
                data: postData
            }).then(function (response) {
                if (response.response.success) {
                    that.remove().then(function () {
                        resolve(true);
                    });
                } else {
                    status.error(response.response.error);
                }
            }).catch(function (err) {
                reject(err);
            }).finally(function () {
                that.loader(false);
            });
        } else {
            reject('Content delete was called, but no url could be determined for ' + that.base);
            that.loader(false);
        }
    };

    Content.prototype.remove = function () {
        var that = this;
        return new Promise(function (resolve, reject) {
            that.$.animate({height: 'toggle', opacity: 'toggle'}, 'fast', function () {
                that.$.remove();
                event.trigger('humhub:modules:content:afterRemove', that);
                resolve(that);
            });
        });
    };

    Content.prototype.permalink = function (evt) {
        permalink(evt);
    };

    var permalink = function (evt) {
        var options = module.config.modal.permalink;
        options.permalink = evt.$trigger.data('content-permalink');

        modal.global.set({
            header: evt.$trigger.data('content-permalink-title') || options.head,
            body: string.template(module.templates.permalinkBody, options),
            footer: string.template(module.templates.permalinkFooter, options),
            size: 'normal'
        }).show();

        modal.global.$.find('textarea').focus().select();

        // Make sure the modal is closed when pjax loads
        event.one('humhub:ready', function () {
            modal.global.close();
        });
    };

    var submitMove = function (evt) {
        modal.submit(evt).then(function (response) {
            if (response.success) {
                if (response.message) {
                    module.log.success(response.message);
                }
                event.trigger('humhub:content:afterMove', response);
            }
        });
    };

    var templates = {
        permalinkBody: '<div class="clearfix"><textarea rows="3" class="form-control permalink-txt" spellcheck="false" readonly>{permalink}</textarea><p class="help-block pull-right"><a href="#" data-action-click="copyToClipboard" data-action-target=".permalink-txt"><i class="fa fa-clipboard" aria-hidden="true"></i> {info}</a></p></div>',
        permalinkFooter: '<button data-modal-close class="btn btn-default">{buttonClose}</button><a href="{permalink}" class="btn btn-primary" data-ui-loader>{buttonOpen}</a>'
    };

    module.export({
        Content: Content,
        templates: templates,
        permalink: permalink,
        submitMove: submitMove
    });
});
