<template>
    <div>
      <h1>Edit Category</h1>
      <form @submit.prevent="updateCategory">
        <div>
          <label for="name">Category Name</label>
          <input type="text" v-model="categoryName" required />
        </div>
        <button type="submit">Update Category</button>
      </form>
      <router-link to="/dashboard/product/category">Back to Categories</router-link>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter, useRoute } from 'vue-router';
  
  const categoryName = ref('');
  const router = useRouter();
  const route = useRoute();
  const categoryId = route.params.id;
  
  const fetchCategory = async () => {
    try {
      const response = await axios.get(`/api/categories/${categoryId}`);
      categoryName.value = response.data.category.name;
    } catch (error) {
      console.error('Failed to fetch category:', error);
    }
  };
  
  const updateCategory = async () => {
    try {
      await axios.put(`/api/categories/${categoryId}`, { name: categoryName.value });
      router.push('/dashboard/product/category'); // Redirect after successful update
    } catch (error) {
      console.error('Failed to update category:', error);
    }
  };
  
  onMounted(fetchCategory);
  </script>
  