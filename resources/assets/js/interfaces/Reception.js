import indexReception from '../interfaces/reception/index';
import NewReservation from '../interfaces/reception/newReservation';
import ReservationsModals from '../interfaces/reception/ReservationModals';

export default class Reception {

    constructor(options) {
        this.prop = options;
    }

    init() {
        indexReception(this.prop);
        ReservationsModals(this.prop);
    }

    newReservation() {
        NewReservation(this.prop);
        ReservationsModals(this.prop);
    }
}
