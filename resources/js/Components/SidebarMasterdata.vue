<template>
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <!-- Home -->
      <li class="nav-item">
        <router-link class="nav-link" to="/dashboard/home">
          <span class="menu-title">Home</span>
          <i class="mdi mdi-home menu-icon"></i>
        </router-link>
      </li>

      <!-- Masterdata with Collapsible Menu -->
      <li class="nav-item">
        <a
          class="nav-link"
          href="#masterdataMenu"
          @click.prevent="toggleMasterdataMenu"
        >
          <span class="menu-title">Masterdata</span>
          <i class="mdi mdi-database menu-icon"></i>
        </a>
        <div class="collapse" id="masterdataMenu" :class="{'show': isMasterdataOpen}">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <router-link class="nav-link" to="/dashboard/product">Product</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/dashboard/product/material">Material</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/dashboard/product/category">Category</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/dashboard/product/size">Size</router-link>
            </li>
          </ul>
        </div>
      </li>

      <!-- Checkout -->
      <li class="nav-item">
        <router-link class="nav-link" to="/dashboard/checkout">
          <span class="menu-title">Checkout</span>
          <i class="mdi mdi-cart menu-icon"></i>
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const isMasterdataOpen = ref(false);
const toggleMasterdataMenu = () => {
  isMasterdataOpen.value = !isMasterdataOpen.value;
  localStorage.setItem('masterdataMenuState', JSON.stringify(isMasterdataOpen.value));
};


onMounted(() => {
  const savedState = localStorage.getItem('masterdataMenuState');
  if (savedState !== null) {
    isMasterdataOpen.value = JSON.parse(savedState);
  }
});
</script>