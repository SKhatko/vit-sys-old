import DailyStats from './analytic/DailyStats';
import MonthlyStats from './analytic/MonthlyStats';
import ReservationsStats from './analytic/ReservationsStats';
import StatusStats from './analytic/StatusStats';

export default class Analytic {

    constructor(options) {
        this.prop = options;
    }

    dailyStats() {
        DailyStats(this.prop)
    }

    monthlyStats() {
        MonthlyStats(this.prop);
    }

    reservationsStats() {
        ReservationsStats(this.prop);
    }

    statusStats() {
        StatusStats(this.prop);
    }

}