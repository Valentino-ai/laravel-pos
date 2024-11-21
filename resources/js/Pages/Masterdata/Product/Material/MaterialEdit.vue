<template>
  <div>
      <h1>Edit Material</h1>
      <form @submit.prevent="updateMaterial">
          <div>
              <label for="name">Material Name:</label>
              <input type="text" id="name" v-model="material.name" required />
          </div>
          
          <div>
              <label for="stock">Stock:</label>
              <input type="number" id="stock" v-model="material.stock" required />
          </div>
          
          <button type="submit">Update Material</button>
      </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();

const material = ref({
  name: '',
  stock: 0,
});

onMounted(fetchMaterial);

async function fetchMaterial() {
  try {
      const { data } = await axios.get(`/api/materials/${route.params.id}`);
      material.value = data.material;
  } catch (error) {
      console.error('Error fetching material:', error);
  }
}

async function updateMaterial() {
  try {
      await axios.put(`/api/materials/${route.params.id}`, material.value);
      router.push('/dashboard/product/material');
  } catch (error) {
      console.error('Error updating material:', error);
  }
}
</script>
