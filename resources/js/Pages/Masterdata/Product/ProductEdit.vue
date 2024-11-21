<template>
    <div>
      <h1>Edit Product</h1>
  
      <form @submit.prevent="submitForm">
        <div>
          <label for="name">Product Type</label>
          <input type="text" v-model="product.name" id="name" required />
        </div>
  
        <div>
          <label for="description">Description</label>
          <textarea v-model="product.description" id="description"></textarea>
        </div>
  
        <div>
          <label for="size_id">Size</label>
          <select v-model="product.size_id" id="size_id" required>
            <option v-for="size in sizes" :key="size.id" :value="size.id">{{ size.name }}</option>
          </select>
        </div>
  
        <div>
          <label for="category_id">Category</label>
          <select v-model="product.category_id" id="category_id" required>
            <option v-for="category in categorys" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
  
        <div>
          <label for="material_id">Material</label>
          <select v-model="product.material_id" id="material_id">
            <option v-for="material in materials" :key="material.id" :value="material.id">{{ material.name }}</option>
          </select>
        </div>
  
        <div>
          <label for="color">Color</label>
          <input type="text" v-model="product.color" id="color" required />
        </div>
  
        <div>
          <label for="image_url">Image</label>
          <input type="file" @change="handleFileUpload" id="image_url" />
        </div>
  
        <div>
          <label for="unit_price">Unit Price</label>
          <input type="number" v-model="product.unit_price" id="unit_price" required />
        </div>
  
        <button type="submit">Update Product</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  
  const router = useRouter();
  const route = useRoute();
  
  const product = ref({
    id: '',
    name: '',
    description: '',
    size_id: '',
    category_id: '',
    material_id: '',
    color: '',
    image_url: '',
    unit_price: 0,
  });
  
  const sizes = ref([]);
  const categorys = ref([]);
  const materials = ref([]);
  
  onMounted(loadInitialData);
  
  async function loadInitialData() {
    try {
      const { id } = route.params;
  
      const [sizesData, categoriesData, materialsData, productData] = await Promise.all([
        axios.get('/api/sizes'),
        axios.get('/api/categorys'),
        axios.get('/api/materials'),
        axios.get(`/api/products/${id}`)
      ]);
  
      sizes.value = sizesData.data;
      categorys.value = categoriesData.data;
      materials.value = materialsData.data;
      product.value = productData.data.data;
    } catch (error) {
      console.error('Error loading data:', error);
    }
  }
  
  async function submitForm() {
    try {
      await axios.put(`/api/products/${product.value.id}`, product.value);
      router.push({ name: 'ProductList' });
    } catch (error) {
      console.error('Error updating product:', error);
    }
  }
  
  function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
      product.value.image_url = URL.createObjectURL(file);
    }
  }
  </script>
  