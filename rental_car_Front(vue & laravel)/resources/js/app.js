/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});
const pinia = createPinia();

import LoginComponent from './components/LoginComponent.vue';
import HomeComponent from './components/HomeComponent.vue';
import BrandCarComponent from './components/BrandCarComponent.vue';
import CarComponent from './components/CarComponent.vue'
import LoadingComponent from './components/LoadingComponent.vue'
import ModelCarComponent from './components/ModelCarComponent.vue';
import FieldCreateComponent from './components/FieldCreateComponent.vue';
import FieldShowComponent from './components/FieldShowComponent.vue';
import TableComponent from './components/TableComponent.vue';
import ModalSaveComponent from './components/ModalSaveComponent.vue';
import ModalShowComponent from './components/ModalShowComponent.vue';
import ModalDeleteComponent from './components/ModalDeleteComponent.vue';
import AlertComponent from './components/AlertComponent.vue';
import PaginationComponent from './components/PaginationComponent.vue';
app.component('login-component', LoginComponent);
app.component('home-component', HomeComponent);
app.component('brand-component', BrandCarComponent);
app.component('model-component', ModelCarComponent);
app.component('car-component', CarComponent);
app.component('loading-component', LoadingComponent);
app.component('field-create-component', FieldCreateComponent);
app.component('field-show-component', FieldShowComponent);
app.component('table-component',TableComponent);
app.component('modal-save-component',ModalSaveComponent);
app.component('modal-show-component', ModalShowComponent);
app.component('modal-delete-component', ModalDeleteComponent);
app.component('alert-component',AlertComponent);
app.component('pagination-component', PaginationComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/LoginComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
app.use(pinia)
app.mount('#app');
