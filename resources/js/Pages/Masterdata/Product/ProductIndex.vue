<template>
  <DashboardLayout>
    <div>
      <h1>Product List</h1>

      <form @submit.prevent="searchProducts" class="search-bar mb-3 d-flex align-items-center">
        <input type="text" v-model="searchQuery" placeholder="Search by product name..." class="form-control me-2" />
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-search"></i>
        </button>
      </form>

      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>

      <div class="p-4 bg-white">
        <div v-if="isLoading" class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>

        <table v-if="!isLoading && products.data.length > 0" class="table table-striped">
          <thead>
            <tr>
              <th><strong>No</strong></th>
              <th><strong>Image</strong></th>
              <th><strong>Product Name</strong></th>
              <th><strong>Size</strong></th>
              <th><strong>Category</strong></th>
              <th><strong>Material</strong></th>
              <th><strong>Color</strong></th>
              <th><strong>Unit Price</strong></th>
              <th><strong>Actions</strong></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in products.data" :key="product.id">
              <td>{{ index + 1 + (currentPage - 1) * 10 }}</td>
              <td>
                <img :src="product.image_url" alt="Product Image" class="img-thumbnail"
                  style="max-width: 100px; max-height: 100px; cursor: pointer;"
                  @click="previewImage(product.image_url)" />
              </td>
              <td>{{ product.name }}</td>
              <td>{{ product.size }}</td>
              <td>{{ product.category }}</td>
              <td>{{ product.material }}</td>
              <td>{{ product.color }}</td>
              <td><strong>Rp. </strong>{{ formatPrice(product.unit_price) }}</td>
              <td>
                <button @click="editProduct(product.id)" class="btn btn-warning btn-sm me-2">Edit</button>
                <button @click="deleteProduct(product.id)" class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="!isLoading && products.data.length === 0" class="text-center">
          <p>No products available</p>
        </div>

        <div v-if="products.total > 0" class="pagination-controls text-center mt-3">
          <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1" class="btn btn-secondary">
            Previous
          </button>
          <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
          <button @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages" class="btn btn-secondary">
            Next
          </button>
        </div>

        <router-link to="/dashboard/product/add" class="btn btn-primary mt-3">Add Product</router-link>
      </div>

      <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Preview</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="modal-body text-center">
              <img :src="selectedImageUrl" alt="Preview" class="img-fluid" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DashboardLayout from '../../../layouts/DashboardLayout.vue';

const router = useRouter();
const products = ref({ data: [], current_page: 1, total: 0 });
const isLoading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const showModal = ref(false);
const selectedImageUrl = ref('');

const formatPrice = (value) => {
  if (value === undefined || value === null) return 'Rp. 0';
  let formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  if (formattedValue.includes('.')) {
    formattedValue = formattedValue.replace(/\.00$/, '');
  }
  return formattedValue;
};

async function fetchProducts(page = currentPage.value, search = searchQuery.value) {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/products', { params: { page, search } });
    if (response.data?.data) {
      products.value = response.data;
      currentPage.value = response.data.current_page;
      totalPages.value = response.data.last_page;
    } else {
      console.error('Products not found in the response');
    }
  } catch (error) {
    console.error('Error fetching products:', error);
  } finally {
    isLoading.value = false;
  }
}

function searchProducts() {
  currentPage.value = 1;
  fetchProducts();
}

function editProduct(id) {
  router.push({ name: 'ProductEdit', params: { id } });
}

async function deleteProduct(id) {
  try {
    await axios.delete(`/api/products/${id}`);
    products.value.data = products.value.data.filter(product => product.id !== id);
  } catch (error) {
    console.error('Error deleting product:', error);
  }
}

function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    fetchProducts();
  }
}

function previewImage(imageUrl) {
  selectedImageUrl.value = imageUrl;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
}

onMounted(fetchProducts);
</script>
