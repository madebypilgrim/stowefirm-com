import { styles } from '../map-styles';

export default class {
    constructor({
        id,
        mapHandle,
        address,
    }) {
        const el = document.getElementById(id);
        const mapEl = el.querySelector(mapHandle);

        // TODO: Get lat/lng from address
        // const geocoder = new google.maps.Geocoder();

        // geocoder.geocode({ address }, (results, status) => {
        //     if (status === 'OK') {
        //         const position = results[0].geometry.location;
        //     }
        // });

        // Hardcoded lat/lng
        const position = new google.maps.LatLng(35.085420, -80.845260);
        const map = new google.maps.Map(mapEl, {
            center: position,
            zoom: 12,
            // Dark Mode
            styles,
        });
        const marker = new google.maps.Marker({
            map,
            position,
        });
    }
}
