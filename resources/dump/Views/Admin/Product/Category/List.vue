<template>
    <div>
      <h1>categorys</h1>
      <div v-if="categorys.length === 0">No categorys available</div>
      <ul v-else>
        <li v-for="(category, index) in categorys" :key="category.id">
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
  
  const categorys = ref([]);
  const router = useRouter();
  
  const fetchCategories = async () => {
    try {
      const response = await axios.get('/api/categorys');
      categorys.value = response.data.categorys;
    } catch (error) {
      console.error('Failed to fetch categorys:', error);
    }
  };
  
  const editCategory = (id) => {
    router.push(`/dashboard/product/category/edit/${id}`);
  };
  
  const deleteCategory = async (id) => {
    try {
      await axios.delete(`/api/categorys/${id}`);
      fetchCategories(); // Refresh the list after deletion
    } catch (error) {
      console.error('Failed to delete category:', error);
    }
  };
  
  onMounted(fetchCategories);
  </script>
  