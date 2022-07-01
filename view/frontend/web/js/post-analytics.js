/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
define([
    'uiElement',
    'mage/storage',
    'mage/url',
    'domReady!'
], function (Element, storage, urlBuilder) {
    return Element.extend({
        defaults: {
            endpoint: '',
            postId: null
        },

        initialize: function () {
            this._super();
            this.sendTick();
        },

        sendTick: function () {
            storage.post(
                urlBuilder.build(this.endpoint + this.postId),
                JSON.stringify({ "postId": this.postId })
            );
        }
    });
});
