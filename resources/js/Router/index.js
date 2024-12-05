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

import checkoutPage from '../Pages/Masterdata/Checkout/Checkout.vue';
import checkoutHistory from '../Pages/Masterdata/Checkout/CheckoutHistory.vue';

const routes = [
  {
    path: '/dashboard/home',
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
  {
    path: '/dashboard/checkout',
    name: 'Checkout',
    component: checkoutPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/checkout/history',
    name: 'CheckoutHistory',
    component: checkoutHistory,
    meta: { requiresAuth: true },
  },
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
  {
    path: '/:pathMatch(.*)*',
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

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
