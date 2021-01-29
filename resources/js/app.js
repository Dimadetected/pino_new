require('./bootstrap');
window.$ = window.jQuery = require('jquery');
import Vue from 'vue';
require('jquery-ui')
require('jquery-mask-plugin/dist/jquery.mask.min')

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VModal from 'vue-js-modal'
Vue.config.productionTip = false

// Install BootstrapVue
Vue.use(BootstrapVue)
Vue.use(VModal)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('kanban', require('./components/kanban/index.vue').default);
Vue.component('info-modal', require('./components/kanban/infoModal.vue').default);


Vue.component('bill-status-change-component', require('./components/BillStatusChange.vue').default);
Vue.component('bill-actions', require('./components/BillActions.vue').default);

Vue.component('message-create', require('./components/MessageCreate.vue').default);

Vue.component('chains-form', require('./components/chains/form.vue').default);
Vue.component('chains-index', require('./components/chains/index.vue').default);

Vue.component('organisations-form', require('./components/organisations/form.vue').default);
Vue.component('organisations-index', require('./components/organisations/index.vue').default);

Vue.component('users-form', require('./components/users/form.vue').default);
Vue.component('users-index', require('./components/users/index.vue').default);

Vue.component('clients-index', require('./components/clients/index.vue').default);
new Vue({
    el: '#app',
});
