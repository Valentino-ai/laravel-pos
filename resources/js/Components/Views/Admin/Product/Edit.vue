<template>
    <div>
        <h1>Edit Product</h1>

        <form @submit.prevent="submitForm">
            <!-- Product Type -->
            <div>
                <label for="name">Product Type</label>
                <input type="text" v-model="product.name" id="name" required />
            </div>

            <!-- Product Description -->
            <div>
                <label for="description">Description</label>
                <textarea v-model="product.description" id="description"></textarea>
            </div>

            <!-- Size Select -->
            <div>
                <label for="size_id">Size</label>
                <select v-model="product.size_id" id="size_id" required>
                    <option v-for="size in sizes" :key="size.id" :value="size.id">{{ size.name }}</option>
                </select>
            </div>

            <!-- Category Select -->
            <div>
                <label for="category_id">Category</label>
                <select v-model="product.category_id" id="category_id" required>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Material Select -->
            <div>
                <label for="material_id">Material</label>
                <select v-model="product.material_id" id="material_id">
                    <option v-for="material in materials" :key="material.id" :value="material.id">{{ material.name }}
                    </option>
                </select>
            </div>

            <!-- Product Color -->
            <div>
                <label for="color">Color</label>
                <input type="text" v-model="product.color" id="color" required />
            </div>

            <!-- Product Image URL -->
            <div>
                <label for="image_url">Image</label>
                <input type="file" @change="handleFileUpload" id="image_url" />
            </div>
            <!-- Unit Price -->
            <div>
                <label for="unit_price">Unit Price</label>
                <input type="number" v-model="product.unit_price" id="unit_price" required />
            </div>

            <!-- Submit Button -->
            <button type="submit">Update Product</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();

const product = ref({
    id: '',
    name: '',
    description: '',
    size_id: '',
    category_id: '',
    material_id: '',
    color: '',
    image_url: '',
    unit_price: 0,
});

const sizes = ref([]);
const categories = ref([]);
const materials = ref([]);

onMounted(async () => {
    await fetchSizes();
    await fetchCategories();
    await fetchMaterials();
    await fetchProduct();
});

async function fetchSizes() {
    try {
        const response = await axios.get('/api/sizes');
        sizes.value = response.data.sizes;
    } catch (error) {
        console.error('Error fetching sizes:', error);
    }
}

async function fetchCategories() {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.categories;
    } catch (error) {
        console.error('Error fetching categories:', error);
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

async function fetchProduct() {
    const { id } = route.params;
    try {
        const response = await axios.get(`/api/products/${id}`);
        product.value = response.data.data; 
    } catch (error) {
        console.error('Error fetching product:', error);
    }
}


async function submitForm() {
    try {
        await axios.put(`/api/products/${product.value.id}`, product.value);
        router.push({ name: 'ProductList' });
    } catch (error) {
        console.error('Error updating product:', error);
    }
}
</script>