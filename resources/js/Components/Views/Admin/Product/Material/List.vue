<template>
    <div>
      <h1>Materials</h1>
      <div v-if="materials.length === 0">No materials available</div>
      <ul v-else>
        <li v-for="(material, index) in materials" :key="material.id">
          {{ index + 1 }}. {{ material.name }} - Stock: {{ material.stock }}
          <button @click="editMaterial(material.id)">Edit</button>
          <button @click="deleteMaterial(material.id)">Delete</button>
        </li>
      </ul>
      <router-link to="/dashboard/product/material/add">Add Material</router-link>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  
  const router = useRouter();
  const materials = ref([]);
  
  onMounted(async () => {
    await fetchMaterials();
  });
  
  async function fetchMaterials() {
    try {
      const response = await axios.get('/api/materials');
      materials.value = response.data.materials;
    } catch (error) {
      console.error('Error fetching materials:', error);
    }
  }
  
  function editMaterial(id) {
    router.push(`/dashboard/product/material/edit/${id}`);
  }
  
  async function deleteMaterial(id) {
    try {
      await axios.delete(`/api/materials/${id}`);
      materials.value = materials.value.filter(material => material.id !== id);
    } catch (error) {
      console.error('Error deleting material:', error);
    }
  }
  </script>
  