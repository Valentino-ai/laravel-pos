<template>
    <div>
        <h2>Login</h2>
        <form @submit.prevent="login">
            <div>
                <label for="email">Email:</label>
                <input type="email" v-model="form.email" required />
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" v-model="form.password" required />
            </div>
            <input type="submit" value="Login" />
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
        email: '', 
        password: '' 
    });

    let error = ref('');

    const login = async () => {
        await axios.post('/api/login', form)
            .then(response => {
                if (response.data.success) {
                    console.log(response);
                    localStorage.setItem('token', response.data.token);
                    router.push('/dashboard/');
                } else {
                    error.value = response.data.message;
                }
            })
            .catch(error => {
                error.value = "An error occurred. Please try again.";
                console.error("Login error:", error);
            });
    };
</script>
