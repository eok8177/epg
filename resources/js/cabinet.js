try {
    window.$ = window.jQuery = require('jquery');
    // require('bootstrap');
} catch (e) {}

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

import App     from './cabinet/App.vue';
import Main    from './cabinet/MainComponent.vue';
import Chanel from './cabinet/ChanelComponent.vue';

const routes = [
  {
    path: '/home',
    name: "Main",
    component: Main,
  },
  {
    path: '/home/chanel/:chanel',
    name: "Chanel",
    component: Chanel,
    props: true,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
})

const app = createApp(App).use(router).mount('#app')
