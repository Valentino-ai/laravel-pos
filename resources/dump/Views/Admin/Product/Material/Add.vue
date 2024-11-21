<template>
  <div>
    <h1>Add Material</h1>
    <form @submit.prevent="addMaterial">
      <div>
        <label for="name">Material Name:</label>
        <input type="text" id="name" v-model="name" required />
      </div>
      <div>
        <label for="stock">Stock:</label>
        <input type="number" id="stock" v-model="stock" required />
      </div>
      <button type="submit">Add Material</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const name = ref('');
const stock = ref(0);

async function addMaterial() {
  try {
    await axios.post('/api/materials', { name: name.value, stock: stock.value });
    router.push('/dashboard/product/material');
  } catch (error) {
    console.error('Error adding material:', error);
  }
}
</script>
