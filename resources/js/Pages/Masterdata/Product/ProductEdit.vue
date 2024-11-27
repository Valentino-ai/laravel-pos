<template>
  <DashboardLayout>
      <div class="container">
          <!-- Back Button -->
          <div class="p-3 mb-3">
              <router-link to="/dashboard/product" class="btn btn-success btn-sm">
                  <i class="mdi mdi-arrow-left"></i> Back to Product List
              </router-link>
          </div>

          <div class="col-md-8 grid-margin stretch-card">
              <!-- Card for Edit Product Form -->
              <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">Edit Product</h4>
                      <p class="card-description">Update the product details</p>

                      <form @submit.prevent="updateProduct" class="forms-sample">
                          <!-- Product Type -->
                          <div class="form-group">
                              <label for="name">Product Type</label>
                              <input type="text" v-model="product.name" class="form-control" id="name"
                                  placeholder="Enter product type" required />
                          </div>

                          <!-- Description -->
                          <div class="form-group">
                              <label for="description">Description</label>
                              <textarea v-model="product.description" class="form-control" id="description"
                                  placeholder="Enter product description"></textarea>
                          </div>

                          <!-- Size -->
                          <div class="form-group">
                              <label for="size_id">Size</label>
                              <select v-model="product.size_id" class="form-control" id="size_id" required>
                                  <option value="" disabled>Select a size</option>
                                  <option v-for="size in sizes" :key="size.id" :value="size.id">
                                      {{ size.name }}
                                  </option>
                              </select>
                          </div>

                          <!-- Category -->
                          <div class="form-group">
                              <label for="category_id">Category</label>
                              <select v-model="product.category_id" class="form-control" id="category_id" required>
                                  <option value="" disabled>Select a category</option>
                                  <option v-for="category in categorys" :key="category.id" :value="category.id">
                                      {{ category.name }}
                                  </option>
                              </select>
                          </div>

                          <!-- Material -->
                          <div class="form-group">
                              <label for="material_id">Material</label>
                              <select v-model="product.material_id" class="form-control" id="material_id">
                                  <option value="" disabled>Select a material</option>
                                  <option v-for="material in materials" :key="material.id" :value="material.id">
                                      {{ material.name }}
                                  </option>
                              </select>
                          </div>

                          <!-- Color -->
                          <div class="form-group">
                              <label for="color">Color</label>
                              <input type="text" v-model="product.color" class="form-control" id="color"
                                  placeholder="Enter product color" required />
                          </div>

                          <!-- Image Upload -->
                          <div class="form-group">
                              <label for="image_url">Image</label>
                              <input type="file" class="form-control" @change="handleFileChange" id="image_url" />
                              <!-- Display current image if exists -->
                              <div v-if="product.image_url">
                                  <img :src="product.image_url" alt="Product Image" class="mt-2" width="100" />
                              </div>
                          </div>

                          <!-- Unit Price -->
                          <div class="form-group">
                              <label for="unit_price">Unit Price</label>
                              <input type="number" v-model="product.unit_price" class="form-control" id="unit_price"
                                  placeholder="Enter unit price" required />
                          </div>

                          <!-- Submit Button -->
                          <button type="submit" class="btn btn-gradient-primary me-2">
                              Update Product
                          </button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import DashboardLayout from '../../../layouts/DashboardLayout.vue';

const router = useRouter();
const route = useRoute();  // Get the route parameters (for product ID)
const product = ref({
    id: '',
    name: '',
    description: '',
    size_id: null,
    category_id: null,
    material_id: null,
    color: '',
    unit_price: 0, 
    image_url: ''
});

const imageFile = ref(null);
const sizes = ref([]);
const categorys = ref([]);
const materials = ref([]);

onMounted(async () => {
    await fetchSizes();
    await fetchCategorys();
    await fetchMaterials();
    await fetchProductData();

    // Ensure product data is populated before allowing form submission
    if (!product.value.id) {
        console.error('Product data not loaded');
    }
});
async function fetchSizes() {
    try {
        const response = await axios.get('/api/sizes');
        sizes.value = response.data.sizes;
    } catch (error) {
        console.error('Error fetching sizes:', error);
    }
}

async function fetchCategorys() {
    try {
        const response = await axios.get('/api/categorys');
        categorys.value = response.data.categorys;
    } catch (error) {
        console.error('Error fetching categorys:', error);
    }
}

async function fetchMaterials() {
    try {
        const response = await axios.get('/api/materials');
        materials.value = response.data.materials;
    } catch (error) {
        console.error('Error fetching materials:', error);
    }
}

async function fetchProductData() {
    const productId = route.params.id;
    try {
        const response = await axios.get(`/api/products/${productId}`);
        product.value = response.data.data || {};
        console.log(product.value);
    } catch (error) {
        console.error('Error fetching product data:', error);
    }
}

function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        imageFile.value = file;
    }
}

async function updateProduct() {
  try {
    if (!product.value.name || !product.value.color || !product.value.size_id || !product.value.unit_price) {
      alert('Please fill in all required fields');
      return;
    }

    const formData = new FormData();

    Object.entries(product.value).forEach(([key, value]) => {
      formData.append(key, value);
    });

    if (imageFile.value) {
      formData.append('image_url', imageFile.value);
    }

    await axios.put(`/api/products/${product.value.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    router.push('/dashboard/product');
  } catch (error) {
    console.error('Error updating product:', error);
  }
}

</script>