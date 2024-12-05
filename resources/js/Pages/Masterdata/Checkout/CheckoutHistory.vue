<template>
    <DashboardLayout>
      <div class="container py-4">
        <h4>Checkout History</h4>
    
        <div v-if="loading">Loading...</div>
        <div v-else>
          <div v-if="checkouts.length === 0">
            No history found.
          </div>
          <div v-else>
            <div v-for="checkout in checkouts" :key="checkout.id" class="card mb-3">
              <div class="card-body">
                <h5 class="card-title">{{ checkout.customer_name }}</h5>
                <p><strong>Checkout Code:</strong> {{ checkout.checkout_code }}</p>
                <p><strong>Date:</strong> {{ checkout.created_at }}</p>
    
                <div>
                  <h6>Selected Products</h6>
                  <ul class="list-group">
                    <li v-for="item in checkout.products" :key="item.product_id" class="list-group-item">
                      <div>
                        <strong>{{ item.name }} (x{{ item.quantity }})</strong>
                      </div>
                      <div>
                        <strong>Price:</strong> {{ formatCurrency(item.unit_price) }}
                      </div>
                      <div>
                        <strong>Total:</strong> {{ formatCurrency(item.unit_price * item.quantity) }}
                      </div>
                    </li>
                  </ul>
                </div>
    
                <p><strong>Total Amount:</strong> {{ formatCurrency(checkout.total_amount) }}</p>
    
                <button @click="deleteCheckout(checkout.checkout_code)">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import DashboardLayout from '../../../layouts/DashboardLayout.vue';
  
  const checkouts = ref([]);
  const loading = ref(true);
  
  const fetchCheckoutHistory = async () => {
    try {
      const response = await axios.get('/api/checkout/history');
      checkouts.value = response.data.checkouts;
    } catch (error) {
      console.error('Error fetching checkout history:', error);
    } finally {
      loading.value = false;
    }
  };
  
  const formatCurrency = (value) => {
    if (value === undefined || value === null) return 'Rp. 0';
  
    let formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    if (formattedValue.includes('.')) {
      formattedValue = formattedValue.replace(/\.00$/, '');
    }
  
    return 'Rp. ' + formattedValue;
  };
  
  async function deleteCheckout(checkoutCode) {
    try {
      console.log('Deleting checkout with Checkout Code:', checkoutCode);
      await axios.delete(`/api/checkout/history/${checkoutCode}`);
      checkouts.value = checkouts.value.filter(checkout => checkout.checkout_code !== checkoutCode);
    } catch (error) {
      console.error('Error deleting checkout:', error);
    }
  }
  
  onMounted(fetchCheckoutHistory);
  </script>
  