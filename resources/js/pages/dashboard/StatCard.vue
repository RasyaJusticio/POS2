<template>
    <div class="stat-card">
      <i :class="iconClass" class="large-icon"></i>
      <h3>{{ title }}</h3>
      <p>{{ value }}</p>
    </div>
  </template>
  
  <script setup lang="ts">
defineProps({
  title: String,
  value: [Number, String],
  iconClass: String // Properti baru untuk kelas ikon
});
</script>
  
  <style scoped>
  .stat-card {
    flex: 1;
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 2px 4px 4px hsla(0, 0%, 10%, 0.461);
    
  }
  .large-icon {
    font-size: 5rem;
    color: #000000; /* Warna ikon */
    margin-bottom: middle;
    margin-top: middle;
  }
  .content {
    text-align: center;
  }
  </style>
  