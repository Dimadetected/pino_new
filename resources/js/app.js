require('./bootstrap');

import Vue from 'vue';
// // import BillStatusChange from "@/components/BillStatusChange";
// Vue.component('bill_status_change', require('@/components/BillStatusChange').default);
//
//
// new Vue({
//     el: '#app',
// });
console.log(123);
// window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('bill-status-change-component', require('./components/BillStatusChange.vue').default);
Vue.component('message-create', require('./components/MessageCreate.vue').default);
Vue.component('bill-actions', require('./components/BillActions.vue').default);

Vue.component('chains-form', require('./components/chains/form.vue').default);
Vue.component('chains-index', require('./components/chains/index.vue').default);

Vue.component('organisations-form', require('./components/organisations/form.vue').default);
Vue.component('organisations-index', require('./components/organisations/index.vue').default);

Vue.component('users-form', require('./components/users/form.vue').default);
Vue.component('users-index', require('./components/users/index.vue').default);
new Vue({
    el: '#app',
});
