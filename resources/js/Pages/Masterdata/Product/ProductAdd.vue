<template>
    <div>
        <h1>Add New Product</h1>
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
                <label for="categorys_id">Category</label>
                <select v-model="product.categorys_id" id="categorys_id" required>
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
            <button type="submit">Add Product</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const product = ref({
    name: '',
    description: '',
    size_id: '',
    categorys_id: '',
    material_id: '',
    color: '',
    image_url: '',
    unit_price: 0,
});

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

async function submitForm() {
    try {
        await axios.post('/api/products', {
            ...product.value,
            category_id: product.value.categorys_id,
        });
        router.push({ name: 'ProductList' });
    } catch (error) {
        console.error('Error adding product:', error);
    }
}
</script>
