import Clients from '../interfaces/crm/Clients';
import Client from '../interfaces/crm/Client';
import Companies from '../interfaces/crm/Companies';
import Company from '../interfaces/crm/Company';

export default class Crm {

    constructor(options) {
        this.prop = options;
    }

    clients() {
        Clients(this.prop);
    }

    client() {
        Client(this.prop);
    }

    companies() {
        Companies(this.prop);
    }

    company() {
        Company(this.prop);
    }
}