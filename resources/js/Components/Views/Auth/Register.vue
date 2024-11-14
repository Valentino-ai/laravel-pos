<template>
    <div>
        <h2>Register</h2>
        <form @submit.prevent="register">
            <div>
                <label for="name">Name:</label>
                <input type="text" v-model="form.name" required />
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" v-model="form.email" required />
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" v-model="form.password" required />
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" v-model="form.confirm_password" required />
            </div>
            <input type="submit" value="Register" />
        </form>
        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

let form = reactive({
    name: '',
    email: '',
    password: '',
    confirm_password: ''
});

let error = ref('');

const register = async () => {
    try {
        const response = await axios.post('/api/register', form);

        if (response.data.success) {
            console.log(response);
            router.push('/login'); // Redirect to login page after successful registration
        } else {
            error.value = response.data.message; // Display error from the API response
        }
    } catch (err) {
        // Handle validation or network errors
        error.value = err.response?.data?.message || 'An error occurred during registration.';
        console.error("Registration error:", err);
    }
};
</script>

<style scoped>
.error {
    color: red;
    font-size: 0.9em;
}
</style>
