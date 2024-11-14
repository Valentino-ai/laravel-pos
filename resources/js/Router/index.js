import { createRouter, createWebHistory } from 'vue-router';
import NotFound from '../Components/NotFound.vue';

import homeAdminIndex from '../Components/Views/Admin/Home/Index.vue';
import homePageIndex from '../Components/Views/Pages/Home/Index.vue';

import register from '../Components/Views/Auth/register.vue';
import login from '../Components/Views/Auth/Login.vue';

import editProfile from '../Components/Views/Admin/Profile/Edit.vue';

import sizeAdd from '../Components/Views/Admin/Product/Size/Add.vue';
import sizeList from '../Components/Views/Admin/Product/Size/List.vue';

import categoryAdd from '../Components/Views/Admin/Product/Category/Add.vue';
import categoryList from '../Components/Views/Admin/Product/Category/List.vue';
import categoryEdit from '../Components/Views/Admin/Product/Category/Edit.vue';

import materialAdd from '../Components/Views/Admin/Product/Material/Add.vue';
import materialList from '../Components/Views/Admin/Product/Material/List.vue';
import materialEdit from '../Components/Views/Admin/Product/Material/Edit.vue';

import productAdd from '../Components/Views/Admin/Product/Add.vue';
import productList from '../Components/Views/Admin/Product/List.vue';
import productEdit from '../Components/Views/Admin/Product/Edit.vue';

const routes = [
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

  // Routes for Auth
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

  // Routes for managing sizes
  {
    path: '/dashboard/product/size',
    name: 'SizeList',
    component: sizeList,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/size/add',
    name: 'SizeAdd',
    component: sizeAdd,
    meta: { requiresAuth: true },
  },

  // Routes for managing categories
  {
    path: '/dashboard/product/category/add',
    name: 'CategoryAdd',
    component: categoryAdd,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/category',
    name: 'CategoryList',
    component: categoryList,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/category/edit/:id',
    name: 'CategoryEdit',
    component: categoryEdit,
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
