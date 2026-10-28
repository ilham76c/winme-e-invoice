import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/invoices',
      name: 'invoices',
      meta: { hideNav: true },
      component: () => import('../views/InvoiceListView.vue'),
    },
    {
      path: '/invoices/new',
      name: 'invoice-create',
      meta: { hideNav: true },
      component: () => import('../views/CreateInvoiceView.vue'),
    },
  ],
})

export default router
