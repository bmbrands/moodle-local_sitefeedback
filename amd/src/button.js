/**
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   image_editable
 * @copyright 2020 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import * as Str from 'core/str';
import Templates from 'core/templates';
import Notification from 'core/notification';
import ModalEvents from 'core/modal_events';
import ModalFactory from 'core/modal_factory';


const selectors = {
    regions: {
        feedbacktext: '[data-region="feedbacktext"]',
        emailaddress: '[data-region="emailaddress"]',
        includescreenshot: '[data-region="includescreenshot"]',
        like: '[data-region="like"]',
        ajaxing: '[data-region="ajaxing"]',
        form: '[data-region="form"]'
    },
    actions: {
        like: '[data-action="like"]',
        dislike: '[data-action="dislike"]'
    },
    classes: {
        hidden: 'd-none',
    }
};

const getFormValues = (modal) => {
    const form = modal[0];
    const message = form.querySelector(selectors.regions.feedbacktext);
    const emailaddress = form.querySelector(selectors.regions.emailaddress);
    const includescreenshot = form.querySelector(selectors.regions.includescreenshot);
    const like = form.querySelector(selectors.regions.like);

    return {
        'message': message.value,
        'emailaddress' : emailaddress.value,
        'includescreenshot' : includescreenshot.value,
        'like' : like.value
    };
};


/**
 * Show a spinner to indicate the form is waiting for the webservice.
 * @param  {HTMLElement} target DOM node of the editable image
 * @param  {Bool} show.
 */
const ajaxing = (modal, show) => {
    const form = modal[0];
    const ajaxing = form.querySelector(selectors.regions.ajaxing);
    if (show) {
        ajaxing.classList.remove(selectors.classes.hidden);
    } else {
        ajaxing.classList.add(selectors.classes.hidden);
    }
};

/**
 * Show a spinner to indicate the form is waiting for the webservice.
 * @param  {HTMLElement} target DOM node of the editable image
 * @param  {Bool} show.
 */
const modalFormEvents = (modal) => {
    const content = modal[0];
    const like = content.querySelector(selectors.actions.like);
    const dislike = content.querySelector(selectors.actions.dislike);
    const form = content.querySelector(selectors.regions.form);

    like.addEventListener('click', function(e) {
        form.classList.remove(selectors.classes.hidden);
        content.classList.remove('hidesend');
        e.preventDefault();
    });

    dislike.addEventListener('click', function(e) {
        form.classList.remove(selectors.classes.hidden);
        content.classList.remove('hidesend');
        e.preventDefault();
    });
};

/**
 * Show an alert on the form.
 * @param  {HTMLElement} target DOM node of the editable image
 * @param  {String} msg Message to show in the alert
 */
const showWarning = (modal, msg) => {
    const form = modal[0].querySelector('form');
    return Templates.render('core/notification', {
        message: msg,
        closebutton: true,
        iswarning: true
    }).then(function(html, js) {
        Templates.prependNodeContents(form, html, js);
    });
};

/**
 * Show an alert on the image.
 * @param  {HTMLElement} target DOM node of the editable image
 * @param  {String} msg Message to show in the alert
 */
const showModal = (target, externallinks) => {
    const strings = [
        {
            key: 'yourfeedback',
            component: 'local_sitefeedback'
        },
        {
            key: 'send',
            component: 'core_message'
        },
    ];

    let saveButtonText = '';
    Str.get_strings(strings).then(function(langStrings) {
        const modalTitle = langStrings[0];
        saveButtonText = langStrings[1];

        return ModalFactory.create({
            title: modalTitle,
            body: Templates.render('local_sitefeedback/feedbackform',
                {'externallinks':
                    JSON.parse(externallinks)
                }
            ),
            type: ModalFactory.types.SAVE_CANCEL,
            large: true
        });
    }).done(function(modal) {
        modal.setSaveButtonText(saveButtonText);

        const modalroot = modal.getRoot();

        modalFormEvents(modalroot);

        modalroot[0].classList.add('hidesend');

        modalroot.on(ModalEvents.save, function(e) {
            ajaxing(modalroot, true);
            e.preventDefault();
            // The action is now confirmed, sending an action for it.
            sendFeedback(getFormValues(modalroot)).then(function(result) {
                if (result.success) {
                    modal.destroy();
                } else {
                    showWarning(modalroot, result.warning);
                }
                ajaxing(modalroot, false);
            });
        });

        // Handle hidden event.
        modalroot.on(ModalEvents.hidden, function() {
            // Destroy when hidden.
            modal.destroy();
        });

        // Show the modal.
        modal.show();

        return;
    }).catch(Notification.exception);
};

/**
 * Send a feedback message
 * @param {Object} args The request arguments
 * @return {Promise} Resolved with a promise.
 */
const sendFeedback = (args) => {
    const request = {
        methodname: 'local_sitefeedback_sendmessage',
        args: args
    };

    let promise = Ajax.call([request])[0]
        .fail(Notification.exception);

    return promise;
};

/**
 * Initiate the editable image controls.
 *
 * @param {HTMLElement} target DOM node of the button
 * @param {int} siteMaxBytes
 */
export const init = (target) => {

    const externallinks = target.getAttribute('data-externallinks');
    // Actions on cropping
    target.addEventListener('click', (e) => {
        showModal(target, externallinks);
        e.preventDefault();
    });

    target.classList.add('js-enabled');
};
