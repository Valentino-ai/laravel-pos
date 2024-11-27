<template>
  <DashboardLayout>
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Materials</h4>
          <p class="card-description"> Manage your materials here.</p>

          <!-- Circular Loading Indicator -->
          <div v-if="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading materials...</span>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="alert alert-danger">
            {{ errorMessage }}
          </div>

          <!-- Materials Table -->
          <table v-if="!loading && materials.length > 0" class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Material Name</th>
                <th>Stock</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(material, index) in materials" :key="material.id">
                <td>{{ index + 1 }}</td>
                <td>{{ material.name }}</td>
                <td>{{ material.stock }}</td>
                <td>
                  <button @click="editMaterial(material.id)" class="btn btn-warning btn-sm me-2">Edit</button>
                  <button @click="deleteMaterial(material.id)" class="btn btn-danger btn-sm">Delete</button>
                </td>

              </tr>
            </tbody>
          </table>

          <!-- No Materials Message -->
          <div v-if="!loading && materials.length === 0" class="text-center">
            No materials available
          </div>


          <router-link to="/dashboard/product/material/add" class="btn btn-primary mt-3">Add Material</router-link>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DashboardLayout from '../../../../layouts/DashboardLayout.vue';

const router = useRouter();
const materials = ref([]);
const loading = ref(true);
const errorMessage = ref('');

async function loadMaterials() {
  try {
    loading.value = true;
    const response = await axios.get('/api/materials');
    materials.value = response.data.materials;
    errorMessage.value = '';
  } catch (error) {
    errorMessage.value = 'Error loading materials. Please try again later.';
    console.error('Error loading materials:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(loadMaterials);

function editMaterial(id) {
  router.push(`/dashboard/product/material/edit/${id}`);
}

async function deleteMaterial(id) {
  try {
    await axios.delete(`/api/materials/${id}`);
    loadMaterials();
  } catch (error) {
    errorMessage.value = 'Error deleting material. Please try again later.';
    console.error('Error deleting material:', error);
  }
}
</script>
