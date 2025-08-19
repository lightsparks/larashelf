import { createRouter, createWebHistory } from 'vue-router';
import { me } from './stores/auth';

const Login = () => import('./views/auth/Login.vue');
const Dashboard = () => import('./views/Dashboard.vue');

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/dashboard' },
        { path: '/login', component: Login, meta: { guest: true } },
        { path: '/dashboard', component: Dashboard, meta: { auth: true } },
    ],
});

router.beforeEach(async(to) => {
    const requiresAuth = !!to.meta.auth;
    const guestOnly = !!to.meta.guest;

    try {
        await me(); // 200 = logged in
        if ( guestOnly ) return '/dashboard';
        return true;
    } catch {
        if ( requiresAuth ) return '/login';
        return true;
    }
});

export default router;
