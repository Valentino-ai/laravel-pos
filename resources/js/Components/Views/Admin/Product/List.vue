<template>
    <div>
        <h1>Product List</h1>
        <input
            type="text"
            v-model="searchQuery"
            placeholder="Search by product name..."
        />
        <button @click="searchProducts">Search</button>

        <div v-if="isLoading">Loading products...</div>
        <div v-else-if="products.data.length === 0">No products available</div>
        
        <ul v-else>
            <li v-for="(product, index) in products.data" :key="product.id">
                <strong>{{ index + 1 + (currentPage - 1) * 5 }}. {{ product.name }}</strong> - {{ product.description }}
                <br />
                <span>Size: {{ product.size }}</span> |
                <span>Category: {{ product.category }}</span> |
                <span>Material: {{ product.material }}</span>
                <br />
                <span>Color: {{ product.color }}</span> |
                <span>Unit Price: {{ product.unit_price }}</span> |
                <img :src="product.image_url" alt="Product Image" v-if="product.image_url" />
                <br />
                <button @click="editProduct(product.id)">Edit</button>
                <button @click="deleteProduct(product.id)">Delete</button>
            </li>
        </ul>

        <div v-if="products.total > 0">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1">Previous</button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages">Next</button>
        </div>

        <router-link to="/dashboard/product/add">Add Product</router-link>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const products = ref({ data: [], current_page: 1, total: 0 });
const isLoading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref(''); 

onMounted(async () => {
    await fetchProducts();
    isLoading.value = false;
});

async function fetchProducts(page = 1, search = '') {
    try {
        const response = await axios.get(`/api/products`, { params: { page, search } });
        console.log(response.data); 

        if (response.data && response.data.data) {
            products.value = response.data;  
            currentPage.value = response.data.current_page;
            totalPages.value = response.data.last_page;
        } else {
            console.error('Products not found in the response');
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

function searchProducts() {
    fetchProducts(1, searchQuery.value);
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
        fetchProducts(page, searchQuery.value);
    }
}
</script>
