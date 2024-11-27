import { createRouter, createWebHistory } from 'vue-router';
import NotFound from '../Pages/NotFound.vue';

import homeAdminIndex from '../Pages/Masterdata/HomeIndex.vue';
import homePageIndex from '../Pages/Home/Index.vue';

import register from '../Pages/Auth/Register.vue';
import login from '../Pages/Auth/Login.vue';

import editProfile from '../Pages/Account/Edit.vue';

import RefMasterdata from '../Components/RefMasterdata.vue';

import materialAdd from '../Pages/Masterdata/Product/Material/MaterialAdd.vue';
import materialList from '../Pages/Masterdata/Product/Material/MaterialIndex.vue';
import materialEdit from '../Pages/Masterdata/Product/Material/MaterialEdit.vue';

import productAdd from '../Pages/Masterdata/Product/ProductAdd.vue';
import productList from '../Pages/Masterdata/Product/ProductIndex.vue';
import productEdit from '../Pages/Masterdata/Product/ProductEdit.vue';

// Import the Checkout Page
import checkoutPage from '../Pages/Masterdata/Checkout/Checkout.vue';

const routes = [
  // Home and Dashboard Routes
  {
    path: '/dashboard/',
    component: homeAdminIndex,
    name: 'AdminHome',
    meta: { requiresAuth: true },
  },
  {
    path: '/',
    component: homePageIndex,
    name: 'Home',
    meta: { requiresAuth: false },
  },

  // Authentication Routes
  {
    path: '/login',
    name: 'Login',
    component: login,
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    name: 'Register',
    component: register,
    meta: { requiresAuth: false },
  },
  {
    path: '/dashboard/profile/edit',
    name: 'EditProfile',
    component: editProfile,
    meta: { requiresAuth: true },
  },

  // Routes for managing materials
  {
    path: '/dashboard/product/material',
    name: 'MaterialList',
    component: materialList,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/material/add',
    name: 'MaterialAdd',
    component: materialAdd,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/material/edit/:id',
    name: 'MaterialEdit',
    component: materialEdit,
    meta: { requiresAuth: true },
  },

  // Routes for managing products
  {
    path: '/dashboard/product',
    name: 'ProductList',
    component: productList,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/add',
    name: 'ProductAdd',
    component: productAdd,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/edit/:id',
    name: 'ProductEdit',
    component: productEdit,
    meta: { requiresAuth: true },
  },

  // Checkout Page Route
  {
    path: '/dashboard/checkout',
    name: 'Checkout',
    component: checkoutPage,
    meta: { requiresAuth: true },
  },

  // Dynamic routes for reusable RefMasterdata
  {
    path: '/dashboard/product/:resourceName', 
    name: 'ResourceList',
    component: RefMasterdata,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/:resourceName/add',
    name: 'ResourceAdd',
    component: RefMasterdata,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/:resourceName/edit/:id',
    name: 'ResourceEdit',
    component: RefMasterdata,
    props: true,
    meta: { requiresAuth: true },
  },

  // Fallback route for 404
  {
    path: '/:pathMatch(.*)*',
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Route Guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token');

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'Login' });
  } else if (to.name === 'Login' && isAuthenticated) {
    next({ name: 'AdminHome' });
  } else {
    next();
  }
});

export default router;
