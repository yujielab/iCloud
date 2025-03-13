import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/admin/theme-settings',
            name: 'admin-theme-settings',
            component: () => import('@/components/Admin/ThemeSettings.vue'),
            meta: {
                requiresAuth: true,
                requiresAdmin: true
            }
        }
    ]
}); 