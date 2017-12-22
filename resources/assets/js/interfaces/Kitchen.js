import indexKitchen from '../interfaces/kitchen/index';

export default class Kitchen {

    constructor(options) {
        this.prop = options;
    }

    init() {
        console.log('indexKitchen');
        indexKitchen(this.prop);
    }
}