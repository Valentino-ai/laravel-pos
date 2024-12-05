<template>
  <DashboardLayout>
      <div class="container">
          <!-- Back Button -->
          <div class="p-3 mb-3">
              <router-link to="/dashboard/product/material" class="btn btn-success btn-sm">
                  <i class="mdi mdi-arrow-left"></i> Back to Material List
              </router-link>
          </div>

          <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">Add New Material</h4>
                      <p class="card-description"> Fill in the details to add a new material </p>
                      <form @submit.prevent="addMaterial" class="forms-sample">
                          <div class="form-group">
                              <label for="name">Material Name</label>
                              <input
                                  type="text"
                                  id="name"
                                  v-model="name"
                                  class="form-control"
                                  placeholder="Enter material name"
                                  required
                              />
                          </div>

                          <div class="form-group">
                              <label for="stock">Stock</label>
                              <input
                                  type="number"
                                  id="stock"
                                  v-model="stock"
                                  class="form-control"
                                  placeholder="Enter stock quantity"
                                  required
                              />
                          </div>

                          <button type="submit" class="btn btn-gradient-primary me-2">
                              Add Material
                          </button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </DashboardLayout>
</template>


<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DashboardLayout from '../../../../layouts/DashboardLayout.vue';

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
