<template>
    <div class="stat-card">
      <div class="icon">
        <i :class="icon"></i>
      </div>
      <div class="content">
        <h3>{{ title }}</h3>
        <p>{{ value }}</p>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps } from 'vue';
  
  const props = defineProps({
    title: String,
    value: Number,
    icon: String,
  });
  </script>
  
  <style scoped>
  .stat-card {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgb(25, 25, 25);
  }
  .icon {
    font-size: 2rem;
    margin-bottom: 10px;
  }
  .content {
    text-align: center;
  }
  </style>
  