import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import AboutView from '../views/AboutView.vue';
import PrivacyView from '../views/PrivacyView.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { 
      path: '/login', 
      component: Login,
      meta: { guest: true }
    },
    { 
      path: '/dashboard', 
      component: Dashboard,
      meta: { requiresAuth: true }
    },
    { path: '/about', component: AboutView },
    { path: '/privacy', component: PrivacyView },
    { path: '/', redirect: '/login' }
  ]
});

router.beforeEach((to, _from, next) => {
  const auth = useAuthStore();
  const isAuthenticated = auth.isAuthenticated;

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else if (to.meta.guest && isAuthenticated) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router;