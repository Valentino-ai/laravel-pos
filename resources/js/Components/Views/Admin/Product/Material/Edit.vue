<template>
    <div>
      <h1>Edit Material</h1>
      <form @submit.prevent="updateMaterial">
        <div>
          <label for="name">Material Name:</label>
          <input type="text" id="name" v-model="name" required />
        </div>
        <div>
          <label for="stock">Stock:</label>
          <input type="number" id="stock" v-model="stock" required />
        </div>
        <button type="submit">Update Material</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  
  const route = useRoute();
  const router = useRouter();
  const name = ref('');
  const stock = ref(0);
  
  onMounted(async () => {
    await fetchMaterial();
  });
  
  async function fetchMaterial() {
    try {
      const response = await axios.get(`/api/materials/${route.params.id}`);
      name.value = response.data.material.name;
      stock.value = response.data.material.stock;
    } catch (error) {
      console.error('Error fetching material:', error);
    }
  }
  
  async function updateMaterial() {
    try {
      await axios.put(`/api/materials/${route.params.id}`, { name: name.value, stock: stock.value });
      router.push('/dashboard/product/material');
    } catch (error) {
      console.error('Error updating material:', error);
    }
  }
  </script>
  