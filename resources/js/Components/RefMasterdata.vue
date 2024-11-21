<template>
    <DashboardLayout>
        <div>
            <div v-if="currentMode === 'list'">
                <h1>{{ resourceName }} List</h1>
                <div v-if="resources.length === 0">
                    No {{ resourceName.toLowerCase() }}s available
                </div>
                <ul v-else>
                    <li v-for="(resource, index) in resources" :key="resource.id">
                        {{ index + 1 }}. {{ resource.name }}
                        <button @click="editResource(resource.id)">
                            Edit
                        </button>
                        <button @click="deleteResource(resource.id)">Delete</button>
                    </li>
                </ul>
                <router-link :to="`/dashboard/product/${resourceName.toLowerCase()}/add`">
                    Add {{ resourceName }}
                </router-link>
            </div>

            <div v-if="currentMode === 'add'">
                <h1>Add {{ resourceName }}</h1>
                <form @submit.prevent="saveResource">
                    <div>
                        <label :for="`${resourceName.toLowerCase()}-name`">{{ resourceName }} Name</label>
                        <input type="text" v-model="resourceData.name" :id="`${resourceName.toLowerCase()}-name`"
                            required />
                    </div>
                    <button type="submit">Add {{ resourceName }}</button>
                </form>
                <router-link :to="`/dashboard/product/${resourceName.toLowerCase()}`">
                    Back to {{ resourceName }} List
                </router-link>
            </div>

            <div v-if="currentMode === 'edit'">
                <h1>Edit {{ resourceName }}</h1>
                <form @submit.prevent="saveResource">
                    <div>
                        <label :for="`${resourceName.toLowerCase()}-name`">{{ resourceName }} Name</label>
                        <input type="text" v-model="resourceData.name" :id="`${resourceName.toLowerCase()}-name`"
                            required />
                    </div>
                    <button type="submit">Update {{ resourceName }}</button>
                </form>
                <router-link :to="`/dashboard/product/${resourceName.toLowerCase()}`">
                    Back to {{ resourceName }} List
                </router-link>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import DashboardLayout from '../layouts/DashboardLayout.vue';

const props = defineProps({
    resourceName: {
        type: String,
        required: true,
    },
});

const route = useRoute();
const router = useRouter();

const resources = ref([]);
const resourceData = ref({ name: '' });
const resourceId = ref(null);
const currentMode = ref('list');

const fetchResources = async () => {
    try {
        const response = await axios.get(`/api/${props.resourceName.toLowerCase()}s`);
        resources.value = response.data[`${props.resourceName.toLowerCase()}s`] || [];
    } catch (error) {
        console.error(`Failed to fetch ${props.resourceName.toLowerCase()}s:`, error);
        resources.value = [];
    }
};

const fetchResource = async () => {
    try {
        const response = await axios.get(`/api/${props.resourceName.toLowerCase()}s/${resourceId.value}`);
        resourceData.value = response.data[props.resourceName.toLowerCase()] || {};
    } catch (error) {
        console.error(`Failed to fetch ${props.resourceName.toLowerCase()}:`, error);
    }
};

const saveResource = async () => {
    try {
        if (currentMode.value === 'edit') {
            await axios.put(`/api/${props.resourceName.toLowerCase()}s/${resourceId.value}`, resourceData.value);
            alert(`${props.resourceName} updated successfully!`);
        } else {
            await axios.post(`/api/${props.resourceName.toLowerCase()}s`, resourceData.value);
            alert(`${props.resourceName} added successfully!`);
        }
        router.push(`/dashboard/product/${props.resourceName.toLowerCase()}`);
    } catch (error) {
        console.error(`Failed to save ${props.resourceName.toLowerCase()}:`, error);
        alert(`Failed to save ${props.resourceName.toLowerCase()}!`);
    }
};

const deleteResource = async (id) => {
    try {
        await axios.delete(`/api/${props.resourceName.toLowerCase()}s/${id}`);
        alert(`${props.resourceName} deleted successfully!`);
        fetchResources();
    } catch (error) {
        console.error(`Failed to delete ${props.resourceName.toLowerCase()}:`, error);
        alert(`Failed to delete ${props.resourceName.toLowerCase()}!`);
    }
};

const editResource = (id) => {
    router.push(`/dashboard/product/${props.resourceName.toLowerCase()}/edit/${id}`);
};

onMounted(() => {
    resourceId.value = route.params.id || null;

    if (route.path.includes('edit')) {
        currentMode.value = 'edit';
        fetchResource();
    } else if (route.path.includes('add')) {
        currentMode.value = 'add';
        resourceData.value = { name: '' };
    } else {
        currentMode.value = 'list';
        fetchResources();
    }
});

watch(
    () => route.path,
    () => {
        resourceId.value = route.params.id || null;

        if (route.path.includes('edit')) {
            currentMode.value = 'edit';
            fetchResource();
        } else if (route.path.includes('add')) {
            currentMode.value = 'add';
            resourceData.value = { name: '' };
        } else {
            currentMode.value = 'list';
            fetchResources();
        }
    }
);
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: 20px auto;
}

.form-group {
    margin-bottom: 15px;
}

.btn {
    margin-top: 15px;
}

button {
    margin-left: 10px;
}
</style>
