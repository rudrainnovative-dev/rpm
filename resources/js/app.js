import Vue from "vue";
import App from "./App.vue";
import router from "./routes";

//pagination 
Vue.component('pagination', require('laravel-vue-pagination'));

//tooltip
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)


// alert plugin on vue
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const Options = {
    timeout: 5000,
    draggable: false
};
              
Vue.use(Toast, Options)

Vue.config.productionTip = false;

new Vue({
  router,
  render: h => h(App)
}).$mount("#app");


router.afterEach((to, from) => {
    // Use next tick to handle router history correctly
    // see: https://github.com/vuejs/vue-router/issues/914#issuecomment-384477609
    Vue.nextTick(() => {
        document.title = to.meta.title || DEFAULT_TITLE;
    });
});