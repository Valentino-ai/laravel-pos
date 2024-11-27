<template>
    <div>
      <h1>Categories</h1>
      <div v-if="categories.length === 0">No categories available</div>
      <ul v-else>
        <li v-for="(category, index) in categories" :key="category.id">
          {{ index + 1 }}. {{ category.name }}
          <button @click="editCategory(category.id)">Edit</button>
          <button @click="deleteCategory(category.id)">Delete</button>
        </li>
      </ul>
      <router-link to="/dashboard/product/category/add">Add Category</router-link>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const categories = ref([]);
  const router = useRouter();
  
  const fetchCategories = async () => {
    try {
      const response = await axios.get('/api/categories');
      categories.value = response.data.categories;
    } catch (error) {
      console.error('Failed to fetch categories:', error);
    }
  };
  
  const editCategory = (id) => {
    router.push(`/dashboard/product/category/edit/${id}`);
  };
  
  const deleteCategory = async (id) => {
    try {
      await axios.delete(`/api/categories/${id}`);
      fetchCategories(); // Refresh the list after deletion
    } catch (error) {
      console.error('Failed to delete category:', error);
    }
  };
  
  onMounted(fetchCategories);
  </script>
  