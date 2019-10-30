export default class {
    constructor({
        id,
    }) {
        const el = document.getElementById(id);
        const toggle = el.querySelector('[name="nav-toggle"]');

        toggle.checked = true;
    }
}
