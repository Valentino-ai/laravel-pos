<template>
    <DashboardLayout>
      <div class="p-4 bg-white">
        <div v-if="currentMode === 'list'" class="p-4 bg-white">
          <h1>{{ resourceName }} List</h1>
          <p class="card-description"> Manage your {{ resourceName.toLowerCase() }}s here.</p>
  
          <!-- Circular Loading Indicator -->
          <div v-if="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading {{ resourceName.toLowerCase() }}s...</span>
            </div>
          </div>
  
          <!-- Error Message -->
          <div v-if="errorMessage" class="alert alert-danger">
            {{ errorMessage }}
          </div>
  
          <!-- Resources Table -->
          <table v-if="!loading && resources.length > 0" class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>{{ resourceName }} Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(resource, index) in resources" :key="resource.id">
                <td>{{ index + 1 }}</td>
                <td>{{ resource.name }}</td>
                <td>
                  <button @click="editResource(resource.id)" class="btn btn-warning btn-sm me-2">Edit</button>
                  <button @click="deleteResource(resource.id)" class="btn btn-danger btn-sm">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
  
          <!-- No Resources Message -->
          <div v-if="!loading && resources.length === 0" class="text-center p-4 bg-white">
            No {{ resourceName.toLowerCase() }}s available
          </div>
  
          <router-link :to="`/dashboard/product/${resourceName.toLowerCase()}/add`" class="btn btn-primary mt-3">Add {{ resourceName }}</router-link>
        </div>
  
        <div v-if="currentMode === 'add'" class="p-4 bg-white">
          <h1>Add {{ resourceName }}</h1>
          <form @submit.prevent="saveResource" class="forms-sample">
            <div class="form-group">
              <label :for="`${resourceName.toLowerCase()}-name`">{{ resourceName }} Name</label>
              <input type="text" v-model="resourceData.name" :id="`${resourceName.toLowerCase()}-name`" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-gradient-primary me-2">Add {{ resourceName }}</button>
          </form>
          <router-link :to="`/dashboard/product/${resourceName.toLowerCase()}`" class="btn btn-link">Back to {{ resourceName }} List</router-link>
        </div>
  
        <div v-if="currentMode === 'edit'" class="p-4 bg-white">
          <h1>Edit {{ resourceName }}</h1>
          <form @submit.prevent="saveResource" class="forms-sample">
            <div class="form-group">
              <label :for="`${resourceName.toLowerCase()}-name`">{{ resourceName }} Name</label>
              <input type="text" v-model="resourceData.name" :id="`${resourceName.toLowerCase()}-name`" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-gradient-primary me-2">Update {{ resourceName }}</button>
          </form>
          <router-link :to="`/dashboard/product/${resourceName.toLowerCase()}`" class="btn btn-link">Back to {{ resourceName }} List</router-link>
        </div>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  import DashboardLayout from '../layouts/DashboardLayout.vue';
  
  const props = defineProps({
    resourceName: {
      type: String,
      required: true,
    },
  });
  
  const route = useRoute();
  const router = useRouter();
  
  const resources = ref([]);
  const resourceData = ref({ name: '' });
  const resourceId = ref(null);
  const currentMode = ref('list');
  const loading = ref(false);
  const errorMessage = ref('');
  
  const fetchResources = async () => {
    loading.value = true;
    try {
      const response = await axios.get(`/api/${props.resourceName.toLowerCase()}s`);
      resources.value = response.data[`${props.resourceName.toLowerCase()}s`] || [];
    } catch (error) {
      console.error(`Failed to fetch ${props.resourceName.toLowerCase()}s:`, error);
      errorMessage.value = `Failed to load ${props.resourceName.toLowerCase()}s!`;
    } finally {
      loading.value = false;
    }
  };
  
  const fetchResource = async () => {
    try {
      const response = await axios.get(`/api/${props.resourceName.toLowerCase()}s/${resourceId.value}`);
      resourceData.value = response.data[props.resourceName.toLowerCase()] || {};
    } catch (error) {
      console.error(`Failed to fetch ${props.resourceName.toLowerCase()}:`, error);
      errorMessage.value = `Failed to load ${props.resourceName.toLowerCase()}!`;
    }
  };
  
  const saveResource = async () => {
    try {
      if (currentMode.value === 'edit') {
        await axios.put(`/api/${props.resourceName.toLowerCase()}s/${resourceId.value}`, resourceData.value);
        alert(`${props.resourceName} updated successfully!`);
      } else {
        await axios.post(`/api/${props.resourceName.toLowerCase()}s`, resourceData.value);
        alert(`${props.resourceName} added successfully!`);
      }
      router.push(`/dashboard/product/${props.resourceName.toLowerCase()}`);
    } catch (error) {
      console.error(`Failed to save ${props.resourceName.toLowerCase()}:`, error);
      alert(`Failed to save ${props.resourceName.toLowerCase()}!`);
    }
  };
  
  const deleteResource = async (id) => {
    try {
      await axios.delete(`/api/${props.resourceName.toLowerCase()}s/${id}`);
      alert(`${props.resourceName} deleted successfully!`);
      fetchResources();
    } catch (error) {
      console.error(`Failed to delete ${props.resourceName.toLowerCase()}:`, error);
      alert(`Failed to delete ${props.resourceName.toLowerCase()}!`);
    }
  };
  
  const editResource = (id) => {
    router.push(`/dashboard/product/${props.resourceName.toLowerCase()}/edit/${id}`);
  };
  
  onMounted(() => {
    resourceId.value = route.params.id || null;
  
    if (route.path.includes('edit')) {
      currentMode.value = 'edit';
      fetchResource();
    } else if (route.path.includes('add')) {
      currentMode.value = 'add';
      resourceData.value = { name: '' };
    } else {
      currentMode.value = 'list';
      fetchResources();
    }
  });
  
  watch(
    () => route.path,
    () => {
      resourceId.value = route.params.id || null;
  
      if (route.path.includes('edit')) {
        currentMode.value = 'edit';
        fetchResource();
      } else if (route.path.includes('add')) {
        currentMode.value = 'add';
        resourceData.value = { name: '' };
      } else {
        currentMode.value = 'list';
        fetchResources();
      }
    }
  );
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
    margin: 20px auto;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .btn {
    margin-top: 15px;
  }
  
  button {
    margin-left: 10px;
  }
  </style>
  