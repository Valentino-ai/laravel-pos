<template>
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile">
        <a href="#" class="nav-link">
          <div class="nav-profile-image">
            <img src="../../assets/images/faces/face1.jpg" alt="profile" />
            <span class="login-status online"></span>
          </div>
          <div class="nav-profile-text d-flex flex-column">
            <span class="font-weight-bold mb-2">David Grey. H</span>
            <span class="text-secondary text-small">Project Manager</span>
          </div>
          <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
        </a>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/dashboard/product">
          <span class="menu-title">Product</span>
          <i class="mdi mdi-view-list menu-icon"></i>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/dashboard/product/material">
          <span class="menu-title">Material</span>
          <i class="mdi mdi-package-variant-closed menu-icon"></i>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/dashboard/product/category">
          <span class="menu-title">Category</span>
          <i class="mdi mdi-tag-outline menu-icon"></i>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/dashboard/product/size">
          <span class="menu-title">Size</span>
          <i class="mdi mdi-ruler menu-icon"></i>
        </router-link>
      </li>
    </ul>
  </nav>
</template>
