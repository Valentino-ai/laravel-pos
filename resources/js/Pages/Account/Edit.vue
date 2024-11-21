<template>
    <div>
        <h2>Edit Account</h2>
        <form @submit.prevent="updateAccount">
            <div>
                <label for="name">Name:</label>
                <input type="text" v-model="form.name" required />
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" v-model="form.email" required />
            </div>
            <div>
                <label for="password">New Password:</label>
                <input type="password" v-model="form.password" placeholder="Leave blank if not changing" />
            </div>
            <div>
                <label for="password_confirmation">Confirm New Password:</label>
                <input type="password" v-model="form.password_confirmation" />
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Account</button>
        </form>

        <h2>Delete Account</h2>
        <div v-if="showConfirmPassword">
            <form @submit.prevent="deleteAccount" class="flex items-end gap-4">
                <div>
                    <label for="current_password">Confirm Password:</label>
                    <input type="password" v-model="form.current_password" required />
                </div>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Confirm</button>
                <button @click="showConfirmPassword = false" class="text-indigo-500 underline font-medium">
                    Cancel
                </button>
            </form>
        </div>

        <button v-if="!showConfirmPassword" @click="showConfirmPassword = true"
            class="bg-red-500 text-white px-6 py-2 rounded-lg">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Delete Account
        </button>

        <p v-if="success" class="success">{{ success }}</p>
        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = reactive({
    name: '',
    email: '',
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showConfirmPassword = ref(false);
const error = ref('');
const success = ref('');

onMounted(async () => {
    try {
        const response = await axios.get('/api/user');
        form.name = response.data.name;
        form.email = response.data.email;
    } catch (err) {
        console.error("Failed to fetch user data:", err);
    }
});

const updateAccount = async () => {
    try {
        const response = await axios.put('/api/user/update', {
            name: form.name,
            email: form.email,
            password: form.password,
            password_confirmation: form.password_confirmation,
        });

        if (response.data.success) {
            success.value = 'Account updated successfully!';
            error.value = '';
        }
    } catch (err) {
        error.value = err.response?.data?.errors || 'An error occurred during the update.';
    }
};

const deleteAccount = async () => {
    try {
        const response = await axios.delete('/api/user/delete', {
            data: { password: form.current_password },
        });

        if (response.data.success) {
            success.value = 'Account deleted successfully!';
            localStorage.removeItem('token');
            router.push('/');
        } else {
            error.value = response.data.message;
        }
    } catch (err) {
        error.value = err.response?.data?.message || 'An error occurred during account deletion.';
    }
};
</script>

<style scoped>
.success {
    color: green;
    font-size: 0.9em;
}

.error {
    color: red;
    font-size: 0.9em;
}
</style>
