import axios from 'axios';

const api = {
  getResource: async (resource) => {
    try {
      const response = await axios.get(`/api/${resource}`);
      return response.data;
    } catch (error) {
      console.error(`Failed to fetch ${resource}:`, error);
      throw error;
    }
  },
  
  getResourceById: async (resource, id) => {
    try {
      const response = await axios.get(`/api/${resource}/${id}`);
      return response.data;
    } catch (error) {
      console.error(`Failed to fetch ${resource} with ID ${id}:`, error);
      throw error;
    }
  },
  
  createResource: async (resource, data) => {
    try {
      const response = await axios.post(`/api/${resource}`, data);
      return response.data;
    } catch (error) {
      console.error(`Failed to create ${resource}:`, error);
      throw error;
    }
  },
  
  updateResource: async (resource, id, data) => {
    try {
      const response = await axios.put(`/api/${resource}/${id}`, data);
      return response.data;
    } catch (error) {
      console.error(`Failed to update ${resource} with ID ${id}:`, error);
      throw error;
    }
  },
  
  deleteResource: async (resource, id) => {
    try {
      const response = await axios.delete(`/api/${resource}/${id}`);
      return response.data;
    } catch (error) {
      console.error(`Failed to delete ${resource} with ID ${id}:`, error);
      throw error;
    }
  },
};

export default api;