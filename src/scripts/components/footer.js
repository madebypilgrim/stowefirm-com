import { throttle } from 'ui-utilities';

export default class {
    constructor({
        id,
        activeClass,
    }) {
        const el = document.getElementById(id);

        const handleScroll = throttle(() => {
            el.classList.toggle(
                activeClass,
                document.body.getBoundingClientRect().bottom < window.innerHeight + 5,
            );
        }, 500);

        window.addEventListener('scroll', handleScroll);

        handleScroll();
    }
}
