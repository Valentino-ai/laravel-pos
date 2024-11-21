<template>
  <RefMasterdata
    resourceName="Categorys" 
    :isEditMode="isEditMode"
    :resourceId="resourceId"
    :resourceData="resourceData"
    :resources="categorys || []"
    @submit="handleSubmit"
    @edit="editCategory"
    @delete="deleteCategory"
  />
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/path-to/api.js';
import RefMasterdata from '../Components/RefMasterdata.vue';

const route = useRoute();
const router = useRouter();

const resourceName = 'Categorys';
const resourceId = ref(null);
const isEditMode = ref(false);
const categorys = ref([]);
const resourceData = ref({ name: '' });

const fetchCategorys = async () => {
  try {
    const response = await api.getResource('categorys');
    categorys.value = Array.isArray(response) ? response : [];
  } catch (error) {
    console.error(`Failed to fetch ${resourceName.toLowerCase()}:`, error);
    categorys.value = [];
  }
};

const fetchCategory = async () => {
  try {
    resourceData.value = await api.getResourceById('categorys', resourceId.value);
  } catch (error) {
    console.error(`Failed to fetch ${resourceName.toLowerCase()}:`, error);
  }
};

const handleSubmit = async (data) => {
  try {
    if (isEditMode.value) {
      await api.updateResource('categorys', resourceId.value, data);
    } else {
      await api.createResource('categorys', data);
    }
    router.push(`/dashboard/product/categorys`);
  } catch (error) {
    console.error(`Failed to save ${resourceName.toLowerCase()}:`, error);
  }
};

const editCategory = (id) => {
  router.push(`/dashboard/product/categorys/edit/${id}`);
};

const deleteCategory = async (id) => {
  try {
    await api.deleteResource('categorys', id);
    fetchCategorys();
  } catch (error) {
    console.error(`Failed to delete ${resourceName.toLowerCase()}:`, error);
  }
};

watch(
  () => route.params.id,
  async (newId) => {
    resourceId.value = newId || null;
    isEditMode.value = !!resourceId.value;

    if (isEditMode.value) {
      await fetchCategory();
    } else if (route.path.includes('add')) {
      resourceData.value = { name: '' };
    } else {
      fetchCategorys();
    }
  },
  { immediate: true }
);

onMounted(() => {
  if (!route.params.id && !route.path.includes('add')) {
    fetchCategorys();
  }
});
</script>
