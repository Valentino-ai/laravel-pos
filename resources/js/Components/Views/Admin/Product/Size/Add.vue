<template>
    <div class="container">
      <h2>Add New Size</h2>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="name">Size Name</label>
          <input type="text" v-model="size.name" id="name" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Add Size</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const size = ref({
    name: '',
  });
  
  const router = useRouter();
  
  const submitForm = async () => {
    try {
      await axios.post('/api/sizes', size.value, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      router.push({ name: 'SizeList' });
      alert('Size added successfully!');
    } catch (error) {
      console.error(error);
      alert('Failed to add size!');
    }
  };
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
  </style>
  