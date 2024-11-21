<template>
    <div class="container">
      <h2>Size List</h2>
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Size Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(size, index) in sizes" :key="size.id">
            <td>{{ index + 1 }}</td> <!-- Increment ID starts from 1 -->
            <td>{{ size.name }}</td>
            <td>
              <button @click="deleteSize(size.id)" class="btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  const sizes = ref([]);
  
  const fetchSizes = async () => {
    try {
      const response = await axios.get('/api/sizes', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      sizes.value = response.data.sizes;
    } catch (error) {
      console.error(error);
      alert('Failed to fetch sizes!');
    }
  };
  
  onMounted(fetchSizes);
  
  const deleteSize = async (id) => {
    if (confirm('Are you sure you want to delete this size?')) {
      try {
        await axios.delete(`/api/sizes/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        fetchSizes(); // Refresh the size list
        alert('Size deleted successfully!');
      } catch (error) {
        console.error(error);
        alert('Failed to delete size!');
      }
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 800px;
    margin: 20px auto;
  }
  
  .table th, .table td {
    text-align: center;
  }
  
  .btn {
    margin-right: 10px;
  }
  </style>
  