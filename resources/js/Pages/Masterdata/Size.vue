<template>
  <DashboardLayout>
    <RefMasterdata
      resourceName="Size"
      :isEditMode="isEditMode"
      :resourceId="resourceId"
      :resourceData="resourceData"
      :resources="resources"
      @submit="handleSubmit"
      @edit="editResource"
      @delete="deleteResource"
    />
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../../../layouts/DashboardLayout.vue';
import RefMasterdata from '../Components/RefMasterdata.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const resourceName = 'Size';
const resourceId = ref(route.params.id || null);
const resources = ref([]);
const resourceData = ref({ name: '' });
const isEditMode = ref(false);

const fetchResources = async () => {
  try {
    const response = await axios.get(`/api/${resourceName.toLowerCase()}s`);
    resources.value = response.data[`${resourceName.toLowerCase()}s`];
  } catch (error) {
    console.error(`Failed to fetch ${resourceName.toLowerCase()}s:`, error);
  }
};

const fetchResource = async () => {
  try {
    const response = await axios.get(`/api/${resourceName.toLowerCase()}s/${resourceId.value}`);
    resourceData.value = response.data[resourceName.toLowerCase()];
  } catch (error) {
    console.error(`Failed to fetch ${resourceName.toLowerCase()}:`, error);
  }
};

const handleSubmit = async (resourceData) => {
  if (isEditMode.value) {
    updateResource(resourceData);
  } else {
    addResource(resourceData);
  }
};

const addResource = async (resourceData) => {
  try {
    await axios.post(`/api/${resourceName.toLowerCase()}s`, { name: resourceData.name });
    router.push(`/dashboard/product/${resourceName.toLowerCase()}`);
  } catch (error) {
    console.error(`Failed to add ${resourceName.toLowerCase()}:`, error);
  }
};

const updateResource = async (resourceData) => {
  try {
    await axios.put(`/api/${resourceName.toLowerCase()}s/${resourceId.value}`, { name: resourceData.name });
    router.push(`/dashboard/product/${resourceName.toLowerCase()}`);
  } catch (error) {
    console.error(`Failed to update ${resourceName.toLowerCase()}:`, error);
  }
};

const editResource = (id) => {
  router.push(`/dashboard/product/${resourceName.toLowerCase()}/edit/${id}`);
};

const deleteResource = async (id) => {
  try {
    await axios.delete(`/api/${resourceName.toLowerCase()}s/${id}`);
    fetchResources();
  } catch (error) {
    console.error(`Failed to delete ${resourceName.toLowerCase()}:`, error);
  }
};

onMounted(() => {
  const currentPath = route.path;

  if (currentPath.includes('edit')) {
    isEditMode.value = true;
    resourceId.value = route.params.id;
    fetchResource();
  } else if (currentPath.includes('add')) {
    isEditMode.value = false;
    resourceData.value = { name: '' };
  } else {
    isEditMode.value = false;
    fetchResources();
  }
});
</script>
