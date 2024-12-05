<template>
    <DashboardLayout>
        <div class="container">
            <!-- Back Button -->
            <div class="p-3 mb-3">
                <router-link to="/dashboard/material" class="btn btn-success btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back to Material List
                </router-link>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Material</h4>
                        <p class="card-description"> Update the material details </p>
                        <form @submit.prevent="updateMaterial" class="forms-sample">
                            <div class="form-group">
                                <label for="name">Material Name</label>
                                <input type="text" id="name" v-model="material.name" class="form-control"
                                    placeholder="Enter material name" required />
                            </div>

                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" id="stock" v-model="material.stock" class="form-control"
                                    placeholder="Enter stock quantity" required />
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2">
                                Update Material
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
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();

const material = ref({
    name: '',
    stock: 0,
});

onMounted(fetchMaterial);

async function fetchMaterial() {
    try {
        const { data } = await axios.get(`/api/materials/${route.params.id}`);
        material.value = data.material;
    } catch (error) {
        console.error('Error fetching material:', error);
    }
}

async function updateMaterial() {
    try {
        await axios.put(`/api/materials/${route.params.id}`, material.value);
        router.push('/dashboard/product/material');
    } catch (error) {
        console.error('Error updating material:', error);
    }
}
</script>
