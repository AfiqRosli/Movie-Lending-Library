/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
require("@fortawesome/fontawesome-free/css/all.min.css");
require("@fortawesome/fontawesome-free/js/all.min.js");

window.Vue = require("vue");

global.$ = require("jquery");
global.jQuery = global.$;

window.Swal = require("sweetalert2");
window.dayjs = require("dayjs");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue")
);

const app = new Vue({
    el: "#app",
});
