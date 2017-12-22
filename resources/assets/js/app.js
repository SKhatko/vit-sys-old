require('./bootstrap');

import Auth from './interfaces/Auth';
import Reception from './interfaces/Reception';
import Kitchen from './interfaces/Kitchen';
import Restaurant from './interfaces/Restaurant';
import Crm from './interfaces/Crm';
import Analytic from './interfaces/Analytic';
import Admin from './interfaces/Admin';

import Header from './layouts/Header';
import Sidebar from './layouts/Sidebar';
import Alert from './layouts/Alert';

import Tooltip from './components/tooltip'

Header();
Sidebar();
Alert();

Tooltip();

window.Reception = Reception;
window.Kitchen = Kitchen;
window.Restaurant = Restaurant;
window.Crm = Crm;
window.Analytic = Analytic;
window.Admin = Admin;
window.Auth = Auth;