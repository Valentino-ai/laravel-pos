<template>
    <div>
      <h1>Add Category</h1>
      <form @submit.prevent="addCategory">
        <div>
          <label for="name">Category Name</label>
          <input type="text" v-model="categoryName" required />
        </div>
        <button type="submit">Add Category</button>
      </form>
      <router-link to="/dashboard/product/category">Back to categorys</router-link>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const categoryName = ref('');
  const router = useRouter();
  
  const addCategory = async () => {
    try {
      await axios.post('/api/categorys', { name: categoryName.value });
      router.push('/dashboard/product/category'); // Redirect to the list after successful addition
    } catch (error) {
      console.error('Failed to add category:', error);
    }
  };
  </script>
  