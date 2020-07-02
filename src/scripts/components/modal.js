export default class {
    constructor({
        id,
        overlayHandle,
        closeHandle,
        contentHandle,
        activeClass,
        actions,
        events,
        refresh,
    }) {
        // Elements and class variables
        const el = document.getElementById(id);
        const overlay = el.querySelector(overlayHandle);
        const content = el.querySelector(contentHandle);
        const close = el.querySelector(closeHandle);

        // Event handler functions
        function handleKeyup(e) {
            // Only care about escape key
            if (e.keyCode !== 27) return;

            events.emit(actions.closeModal);
        }
        function handleOpenModal() {
            events.emit(actions.lockScroll);
            el.classList.add(activeClass);

            document.addEventListener('keyup', handleKeyup);
        }
        function handleCloseModal() {
            events.emit(actions.unlockScroll);
            el.classList.remove(activeClass);

            document.removeEventListener('keyup', handleKeyup);
        }
        function handleLoadModal(e) {
            const {
                markup,
                size = 'full',
                background = 'default',
            } = e.detail;

            el.setAttribute('data-size', size);
            el.setAttribute('data-background', background);

            content.innerHTML = markup;
            refresh(content);
            events.emit(actions.openModal);
        }
        function handleClick(e) {
            e.preventDefault();

            events.emit(actions.closeModal);
        }

        // Add event listeners
        events.on(actions.openModal, handleOpenModal);
        events.on(actions.closeModal, handleCloseModal);
        events.on(actions.loadModal, handleLoadModal);
        close.addEventListener('click', handleClick);
        overlay.addEventListener('click', handleClick);
    }
}
