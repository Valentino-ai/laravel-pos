<template>
    <DashboardLayout>
        <div class="container">
            <div class="p-3 mb-3">
                <router-link to="/dashboard/product" class="btn btn-success btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back to Product List
                </router-link>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add New Product</h4>
                        <p class="card-description">Fill in the details to add a new product</p>

                        <form @submit.prevent="addProduct" class="forms-sample">
                            <div class="form-group">
                                <label for="name">Product Type</label>
                                <input type="text" v-model="product.name" class="form-control" id="name"
                                    placeholder="Enter product type" required />
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea v-model="product.description" class="form-control" id="description"
                                    placeholder="Enter product description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="size_id">Size</label>
                                <select v-model="product.size_id" class="form-control" id="size_id" required>
                                    <option value="" disabled>Select a size</option>
                                    <option v-for="size in sizes" :key="size.id" :value="size.id">
                                        {{ size.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select v-model="product.category_id" class="form-control" id="category_id" required>
                                    <option value="" disabled>Select a category</option>
                                    <option v-for="category in categorys" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="material_id">Material</label>
                                <select v-model="product.material_id" class="form-control" id="material_id">
                                    <option value="" disabled>Select a material</option>
                                    <option v-for="material in materials" :key="material.id" :value="material.id">
                                        {{ material.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="text" v-model="product.color" class="form-control" id="color"
                                    placeholder="Enter product color" required />
                            </div>

                            <div class="form-group">
                                <label for="image_url">Image</label>
                                <input type="file" class="form-control" @change="handleFileChange" id="image_url" />
                                <div v-if="imagePreview" class="mt-3">
                                    <img :src="imagePreview" alt="Image preview" class="img-fluid" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="unit_price">Unit Price</label>
                                <input type="number" v-model="product.unit_price" class="form-control" id="unit_price"
                                    placeholder="Enter unit price" required />
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2">Add Product</button>
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
import { useRouter } from 'vue-router';
import DashboardLayout from '../../../layouts/DashboardLayout.vue';

const router = useRouter();
const product = ref({
    name: '',
    description: '',
    size_id: '',
    category_id: '',
    material_id: '',
    color: '',
    unit_price: 0,
});

const imageFile = ref(null);
const imagePreview = ref(null);
const sizes = ref([]);
const categorys = ref([]);
const materials = ref([]);

onMounted(async () => {
    await fetchSizes();
    await fetchCategorys();
    await fetchMaterials();
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

function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

async function addProduct() {
    const formData = new FormData();
    for (const [key, value] of Object.entries(product.value)) {
        formData.append(key, value);
    }
    if (imageFile.value) {
        formData.append('image_url', imageFile.value);
    }
    try {
        await axios.post('/api/products', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        router.push({ name: 'ProductList' });
    } catch (error) {
        console.error('Error adding product:', error.response ? error.response.data : error);
    }
}
</script>
