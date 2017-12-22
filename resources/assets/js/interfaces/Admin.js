import OnlineSettings from './../interfaces/admin/OnlineSettings';
import ReservationHours from './../interfaces/admin/ReservationHours';

export default class Admin {

    constructor(options) {
        this.prop = options;
    }

    onlineSettings() {
        OnlineSettings();
    }

    reservationHours() {
        ReservationHours(this.prop);
    }

}