import { router } from 'ui-utilities';

export default class {
    constructor({
        id,
        actions,
        events,
    }) {
        const el = document.getElementById(id);
        const modalLinks = el.querySelectorAll('article a[data-modal="true"]');

        function handleModalLink(e) {
            e.preventDefault();

            const url = e.currentTarget.href;

            function cb(res) {
                events.emit(actions.loadModal, { markup: res, background: 'white' });
            }

            router.get({ url, cb });
        }

        modalLinks.forEach(l => {
            l.addEventListener('click', handleModalLink);
        });
    }
}
