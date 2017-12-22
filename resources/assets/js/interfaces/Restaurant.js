import Sections from './restaurant/Sections';
import TablePlans from './restaurant/TablePlans';
import TablePlanSchedule from './restaurant/TablePlanSchedule';
import TablePlan from './restaurant/TablePlan';
import MenuItems from './restaurant/MenuItems';
import MenuItem from './restaurant/MenuItem';
import MenuCategories from './restaurant/MenuCategories';
import MenuCategory from './restaurant/MenuCategory';
import MenuGroups from './restaurant/MenuGroups';
import MenuGroup from './restaurant/MenuGroup';
import Menus from './restaurant/Menus';
import Menu from './restaurant/Menu';

export default class Restaurant {

    constructor(options) {
        this.prop = options;
    }

    sections() {
        Sections();
    }

    tablePlans() {
        TablePlans();
    }

    tablePlanSchedule() {
        TablePlanSchedule(this.prop);
    }

    tablePlan() {
        TablePlan(this.prop);
    }

    menuItems() {
        MenuItems();
    }

    menuItem() {
        MenuItem(this.prop);
    }

    menuCategories() {
        MenuCategories(this.prop);
    }

    menuCategory() {
        MenuCategory(this.prop);
    }

    menuGroups() {
        MenuGroups(this.prop);
    }

    menuGroup() {
        MenuGroup(this.prop);
    }

    menus() {
        Menus(this.prop);
    }

    menu() {
        Menu(this.prop);
    }

}