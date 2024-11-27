<template>
    <DashboardLayout>
        <div class="container">
            <div class="row">
                <!-- Product Cards Section -->
                <div class="col-lg-9">
                    <div class="row">
                        <div v-if="products.length > 0">
                            <div class="col-md-4" v-for="(product, index) in products" :key="product.id">
                                <div class="card" @click="addToCheckout(product)">
                                    <img :src="product.image_url" class="card-img-top" alt="Product Image">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ product.name }}</h5>
                                        <p class="card-text">{{ product.unit_price | currency }}</p>
                                        <p class="card-text"><strong>Size:</strong> {{ product.size }}</p>
                                        <p class="card-text"><strong>Category:</strong> {{ product.category }}</p>
                                        <p class="card-text"><strong>Material:</strong> {{ product.material }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            No products available.
                        </div>
                    </div>
                </div>

                <!-- Checkout Form Section -->
                <div class="col-lg-3">
                    <h4>Checkout</h4>
                    <ul class="list-group">
                        <li class="list-group-item" v-for="item in checkoutDetails" :key="item.id">
                            {{ item.name }} - {{ item.quantity }} x {{ item.unit_price | currency }}
                        </li>
                    </ul>
                    <div class="mt-3">
                        <h5>Total: {{ totalAmount }}</h5>
                        <button class="btn btn-primary w-100" @click="submitCheckout">Submit Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import DashboardLayout from '../../../layouts/DashboardLayout.vue';

// State
const products = ref([]);
const checkoutDetails = ref([]);

// Computed: Calculate the total amount
const totalAmount = computed(() =>
    checkoutDetails.value.reduce((total, item) => total + item.quantity * item.unit_price, 0)
);

const fetchProducts = async () => {
    try {
        console.log("Fetching products...");
        const response = await axios.get('/api/products');
        console.log("Full API Response:", response.data);

        // Check if the response is paginated
        if (response.data.data) {
            products.value = response.data.data; // Extract products from paginated response
            console.log("Extracted Products:", products.value);
        } else if (Array.isArray(response.data)) {
            products.value = response.data; // For non-paginated APIs
            console.log("Direct Products:", products.value);
        } else {
            console.error("Unexpected API response structure:", response.data);
        }
    } catch (error) {
        console.error("Error fetching products:", error);
    }
};

// Add product to checkout details
const addToCheckout = (product) => {
    const existing = checkoutDetails.value.find((item) => item.id === product.id);
    if (existing) {
        existing.quantity++;
    } else {
        checkoutDetails.value.push({
            id: product.id,
            name: product.name,
            unit_price: product.unit_price,
            quantity: 1,
        });
    }
};

// Submit checkout
const submitCheckout = async () => {
    try {
        const payload = {
            products: checkoutDetails.value.map((item) => ({
                id: item.id,
                quantity: item.quantity,
            })),
        };
        await axios.post('/api/checkout', payload);
        alert('Checkout successful!');
        checkoutDetails.value = [];
    } catch (error) {
        console.error('Failed to submit checkout:', error);
    }
};

// Lifecycle hook: Fetch products on component mount
onMounted(fetchProducts);
</script>
