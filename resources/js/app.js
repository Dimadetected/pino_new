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
new Vue({
    el: '#app',
});
