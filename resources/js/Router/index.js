import { createRouter, createWebHistory } from 'vue-router';
import NotFound from '../Pages/NotFound.vue';

import homeAdminIndex from '../Pages/Masterdata/HomeIndex.vue';
import homePageIndex from '..//Pages/Home/Index.vue';

import register from '../Pages/Auth/Register.vue';
import login from '../Pages/Auth/Login.vue';

import editProfile from '../Pages/Account/Edit.vue';

// import sizeAdd from '../Components/Views/Admin/Product/Size/Add.vue';
// import sizeList from '../Components/Views/Admin/Product/Size/List.vue';

// import categoryAdd from '../Components/Views/Admin/Product/Category/Add.vue';
// import categoryList from '../Components/Views/Admin/Product/Category/List.vue';
// import categoryEdit from '../Components/Views/Admin/Product/Category/Edit.vue';

import RefMasterdata from '../Components/RefMasterdata.vue'

import materialAdd from '../Pages/Masterdata/Product/Material/MaterialAdd.vue';
import materialList from '../Pages/Masterdata/Product/Material/MaterialIndex.vue';
import materialEdit from '../Pages/Masterdata/Product/Material/MaterialEdit.vue';

import productAdd from '../Pages/Masterdata/Product/ProductAdd.vue';
import productList from '../Pages/Masterdata/Product/ProductIndex.vue';
import productEdit from '../Pages/Masterdata/Product/ProductEdit.vue';

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

  // // Routes for managing sizes
  // {
  //   path: '/dashboard/product/size',
  //   name: 'SizeList',
  //   component: sizeList,
  //   meta: { requiresAuth: true },
  // },
  // {
  //   path: '/dashboard/product/size/add',
  //   name: 'SizeAdd',
  //   component: sizeAdd,
  //   meta: { requiresAuth: true },
  // },

  // // Routes for managing categorys
  // {
  //   path: '/dashboard/product/category/add',
  //   name: 'CategoryAdd',
  //   component: categoryAdd,
  //   meta: { requiresAuth: true },
  // },
  // {
  //   path: '/dashboard/product/category',
  //   name: 'CategoryList',
  //   component: categoryList,
  //   meta: { requiresAuth: true },
  // },
  // {
  //   path: '/dashboard/product/category/edit/:id',
  //   name: 'CategoryEdit',
  //   component: categoryEdit,
  //   meta: { requiresAuth: true },
  // },

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

  {
    path: '/dashboard/product/:resourceName',  // Dynamic route for List view
    name: 'ResourceList',
    component: RefMasterdata,
    props: true,  // Pass route params as props to RefMasterdata
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/:resourceName/add',
    name: 'ResourceAdd',
    component: RefMasterdata,
    props: true,  // Pass route params as props to RefMasterdata
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/product/:resourceName/edit/:id',
    name: 'ResourceEdit',
    component: RefMasterdata,
    props: true,  // Pass route params as props to RefMasterdata
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
