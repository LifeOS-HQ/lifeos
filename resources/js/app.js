/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();
window.Highcharts = require('highcharts');

/**
 * Number.prototype.format(n, x, s, c)
 *
 * @param integer n: length of decimal
 * @param integer x: length of whole part
 * @param mixed   s: sections delimiter
 * @param mixed   c: decimal delimiter
 */
Number.prototype.format = function(decimals, dec_point, thousands_sep) {
    var number = (this + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + (Math.round(n * k) / k).toFixed(prec);
    };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
};

Highcharts.setOptions({
    lang: {
        decimalPoint: ',',
        thousandsSep: '.'
    }
});

import Flash from './plugins/flash.js';

Vue.use(Flash);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('flash-message', require('./components/partials/flashmessage.vue').default);

Vue.component('home-work-show', require('./components/home/work/show.vue').default);
Vue.component('home-rentablo-index', require('./components/home/rentablo/index.vue').default);
Vue.component('home-server-index', require('./components/home/server/index.vue').default);

Vue.component('activity-table', require('./components/activity/table.vue').default);

Vue.component('comment-index', require('./components/comment/index.vue').default);

Vue.component('contact-table', require('./components/contact/table.vue').default);

Vue.component('blog-post-table', require('./components/blog/post/table.vue').default);

Vue.component('finance-independence-index', require('./components/finance/independence/index.vue').default);

Vue.component('journal-index', require('./components/journal/index.vue').default);
Vue.component('journal-gratitude-table', require('./components/journal/gratitude/table.vue').default);

Vue.component('lifearea-table', require('./components/lifearea/table.vue').default);
Vue.component('lifearea-scale-index', require('./components/lifearea/scale/index.vue').default);
Vue.component('lifearea-scale-goals-table', require('./components/lifearea/scale/goals/table.vue').default);
Vue.component('lifearea-rating-chart', require('./components/lifearea/rating/chart.vue').default);
Vue.component('portfolio-table', require('./components/portfolio/table.vue').default);
Vue.component('portfolio-dividend-month', require('./components/portfolio/dividend/month.vue').default);
Vue.component('review-table', require('./components/review/table.vue').default);
Vue.component('review-show', require('./pages/review/show.vue').default);

Vue.component('work-time-table', require('./components/work/time/table.vue').default);
Vue.component('work-month-table', require('./components/work/month/table.vue').default);
Vue.component('work-year-table', require('./components/work/year/table.vue').default);
Vue.component('work-index', require('./components/work/index.vue').default);

Vue.component('workout-table', require('./components/fitness/workouts/table.vue').default);
Vue.component('workout-exercises-index', require('./components/fitness/workouts/exercises/index.vue').default);
Vue.component('workout-exercises-sets-table', require('./components/fitness/workouts/exercises/sets/table.vue').default);

Vue.component('workout-histories-table', require('./components/fitness/workouts/histories/table.vue').default);
Vue.component('workout-histories-exercises-index', require('./components/fitness/workouts/histories/exercises/index.vue').default);


// Widgets
Vue.component('widget-index', require('./components/widgets/index.vue').default);

Vue.component('widget-health-sleep', require('./components/widgets/health/sleep.vue').default);
Vue.component('widget-health-steps', require('./components/widgets/health/steps.vue').default);
Vue.component('widget-health-weight', require('./components/widgets/health/weight.vue').default);
Vue.component('widget-health-calories-show', require('./components/widgets/health/calories/show.vue').default);
Vue.component('widget-health-macros-show', require('./components/widgets/health/macros/show.vue').default);
Vue.component('widget-health-weight-development-show', require('./components/widgets/health/weight-development/show.vue').default);
Vue.component('widget-time-show', require('./components/widgets/time/show.vue').default);
Vue.component('widget-day-show', require('./components/widgets/day/show.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

$('#menu-toggle').click(function() {
    $('#nav, #content-container').toggleClass('active');
    $('.collapse.in').toggleClass('in');
    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
});

$('[data-toggle="popover"]').popover({
    html: true,
});
