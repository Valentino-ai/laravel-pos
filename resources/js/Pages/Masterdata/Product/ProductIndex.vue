<template>
  <DashboardLayout>
    <div>
      <h1>Product List</h1>

      <!-- Search Bar -->
      <div class="search-bar mb-3">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by product name..."
          class="form-control"
        />
        <button @click="searchProducts" class="btn btn-primary mt-2">Search</button>
      </div>
      
      <!-- Error Message -->
      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>

      <!-- Outer Container with Padding and White Background -->
      <div class="p-4 bg-white"> <!-- Outer container with padding and white background -->
        
        <div v-if="isLoading" class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        
        <!-- Product Table -->
        <table v-if="!isLoading && products.data.length > 0" class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Product Name</th>
              <th>Size</th>
              <th>Category</th>
              <th>Material</th>
              <th>Color</th>
              <th>Unit Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in products.data" :key="product.id">
              <td>{{ index + 1 + (currentPage - 1) * 10 }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.size }}</td>
              <td>{{ product.category }}</td>
              <td>{{ product.material }}</td>
              <td>{{ product.color }}</td>
              <td>{{ product.unit_price }}</td>
              <td>
                <button @click="editProduct(product.id)" class="btn btn-warning btn-sm me-2">Edit</button>
                <button @click="deleteProduct(product.id)" class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- No Products Message -->
        <div v-if="!isLoading && products.data.length === 0" class="text-center">
          <p>No products available</p>
        </div>

        <!-- Pagination Controls -->
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

      </div> <!-- End of Outer Container -->
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

onMounted(fetchProducts);

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
</script>