<template>
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Login</h4>
        <p class="card-description"> Please log in to continue </p>
        <form @submit.prevent="login" class="forms-sample">
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input
              type="email"
              v-model="form.email"
              class="form-control"
              id="exampleInputEmail1"
              placeholder="Email"
              required
            />
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input
              type="password"
              v-model="form.password"
              class="form-control"
              id="exampleInputPassword1"
              placeholder="Password"
              required
            />
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2">
            Login
          </button>
        </form>
        <p v-if="error" class="text-danger mt-2">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { reactive, ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  import { defineEmits } from 'vue';

  const emit = defineEmits(['updateAuth']);

  const router = useRouter();
  
  const form = reactive({ 
    email: '', 
    password: '' 
  });
  
  const error = ref('');
  
  const login = async () => {
    await axios.post('/api/login', form)
      .then(response => {
        if (response.data.success) {
          console.log(response);
          localStorage.setItem('token', response.data.token);
          emit('updateAuth', true);
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
