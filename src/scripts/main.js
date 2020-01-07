import pop from 'compop';
import { spy } from 'ui-utilities';
import './polyfills';

// Components
import Footer from './components/footer';
import Form from './components/form';
import Header from './components/header';

const { SITE_HANDLE } = process.env;

// Define map of component handles to component classes
/* eslint-disable quote-props */
const classMap = {
    'footer': Footer,
    'form': Form,
    'header': Header,
};
/* eslint-enable quote-props */

// Define all actions/commands that components pub/sub
const actions = {
    // Action events
    lockScroll: 'lock-scroll',
    unlockScroll: 'unlock-scroll',
    showHeader: 'show-header',
    hideHeader: 'hide-header',
    openModal: 'open-modal',
    closeModal: 'close-modal',
    loadModal: 'load-modal',
    showInputErrors: 'show-input-errors',
};

// Event handler functions
function handleDOMConentLoaded() {
    const scaffold = window[SITE_HANDLE];

    // Functionality for after components initialize
    function cb() {
        // Check lazy loaded images
        spy.images();
    }

    // Call component constructors
    pop({ scaffold, classMap, actions, cb });
}

// Add event listeners
document.addEventListener('DOMContentLoaded', handleDOMConentLoaded);
