<template>
    <DashboardLayout>
        <div class="container py-4">
            <div class="row mb-4">
                <div class="col-md-8">
                    <input type="text" v-model="searchQuery" class="form-control" placeholder="Search products by name..." />
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <h4>Available Products</h4>
                    <div class="row">
                        <div v-for="product in paginatedProducts" :key="product.id" class="col-md-4 mb-3">
                            <div class="card">
                                <img :src="product.image_url" alt="Product Image" class="card-img-top" style="object-fit: cover; width: 100%; height: 200px;" />
                                <div class="card-body">
                                    <h5 class="card-title">{{ product.name }}</h5>
                                    <p class="card-text">
                                        <strong>Category :</strong> {{ product.category || 'N/A' }}<br />
                                        <strong>Material :</strong> {{ product.material || 'N/A' }}<br />
                                        <strong>Unit Price :</strong> {{ formatCurrency(product.unit_price) }}
                                    </p>
                                    <button class="btn btn-primary w-100" @click="addToCheckout(product)">
                                        Add to Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pagination-controls text-center mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ 'disabled': currentPage <= 1 }">
                                    <button class="btn btn-secondary" @click="changePage(currentPage - 1)" :disabled="currentPage <= 1">
                                        Previous
                                    </button>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link bg-white">Page {{ currentPage }} of {{ totalPages }}</span>
                                </li>
                                <li class="page-item" :class="{ 'disabled': currentPage >= totalPages }">
                                    <button class="btn btn-secondary" @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages">
                                        Next
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="col-md-4">
                    <h4>Checkout</h4>
                    <form @submit.prevent="submitCheckout">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Customer Name</label>
                            <input type="text" id="customerName" v-model="customerName" class="form-control" placeholder="Input Customer Name Here" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Selected Products</label>
                            <ul class="list-group">
                                <li v-for="item in checkoutDetails" :key="item.product_id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <img :src="item.image_url" alt="Product Image" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" />
                                        {{ item.name }} (x{{ item.quantity }})
                                    </span>
                                    <div class="mt-2">
                                        <strong>Price :</strong> {{ formatCurrency(item.unit_price) }} <br />
                                        <strong>Total :</strong> {{ formatCurrency(item.unit_price * item.quantity) }}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-danger me-2" @click="removeFromCheckout(item.product_id)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary me-2" @click="decrementQuantity(item.product_id)">
                                            -
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" @click="incrementQuantity(item.product_id)">
                                            +
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <h5>Total Checkout Amount: {{ formatCurrency(totalAmount) }}</h5>
                        </div>
                        <button type="submit" class="btn btn-success w-100" :disabled="checkoutDetails.length === 0">
                            Submit Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import DashboardLayout from '../../../layouts/DashboardLayout.vue';

const products = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(6);
const customerName = ref('');
const checkoutDetails = ref([]);
const searchQuery = ref('');

const filteredProducts = computed(() =>
    products.value.filter((product) =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
);

const paginatedProducts = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage.value;
    const endIndex = startIndex + itemsPerPage.value;
    return filteredProducts.value.slice(startIndex, endIndex);
});

const totalPages = computed(() =>
    Math.ceil(filteredProducts.value.length / itemsPerPage.value)
);

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const fetchProducts = async () => {
    try {
        const response = await axios.get('/api/checkout/products', {
            params: { search: searchQuery.value },
        });
        products.value = response.data;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

watch(searchQuery, fetchProducts);

const addToCheckout = (product) => {
    const existingItem = checkoutDetails.value.find(
        (item) => item.product_id === product.id
    );
    if (existingItem) {
        existingItem.quantity++;
    } else {
        checkoutDetails.value.push({
            product_id: product.id,
            name: product.name,
            quantity: 1,
            unit_price: product.unit_price,
            image_url: product.image_url,
        });
    }
};

const removeFromCheckout = (productId) => {
    checkoutDetails.value = checkoutDetails.value.filter(
        (item) => item.product_id !== productId
    );
};

const incrementQuantity = (productId) => {
    const item = checkoutDetails.value.find(
        (product) => product.product_id === productId
    );
    if (item) {
        item.quantity++;
    }
};

const decrementQuantity = (productId) => {
    const item = checkoutDetails.value.find(
        (product) => product.product_id === productId
    );
    if (item) {
        if (item.quantity === 1) {
            removeFromCheckout(productId);
        } else {
            item.quantity--;
        }
    }
};

const totalAmount = computed(() =>
    checkoutDetails.value.reduce(
        (total, item) => total + item.quantity * item.unit_price,
        0
    )
);

const formatCurrency = (value) => {
    if (value === undefined || value === null) return 'Rp. 0';

    let formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    if (formattedValue.includes('.')) {
        formattedValue = formattedValue.replace(/\.00$/, '');
    }

    return 'Rp. ' + formattedValue;
};

const submitCheckout = async () => {
    try {
        const payload = {
            customer_name: customerName.value,
            products: checkoutDetails.value.map((item) => ({
                product_id: item.product_id,
                quantity: item.quantity,
            })),
        };
        await axios.post('/api/checkout/create', payload);
        alert('Checkout submitted successfully!');
        checkoutDetails.value = [];
    } catch (error) {
        console.error('Error submitting checkout:', error);
    }
};

onMounted(fetchProducts);
</script>
