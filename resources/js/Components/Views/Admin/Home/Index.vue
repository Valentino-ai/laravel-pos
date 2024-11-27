<script setup>
    import { useRouter } from "vue-router"

    const router = useRouter()

    const logout = () => {
        localStorage.removeItem('token');
        router.push('/');
    }
</script>
<template>
    <h1>Dashboard</h1>

    <button @click="logout">
        Logout
    </button>
</template>