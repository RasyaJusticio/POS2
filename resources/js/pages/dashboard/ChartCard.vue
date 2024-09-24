<template>
    <div class="chart-card">
      <h4>{{ title }}</h4>
      <div class="chart-container">
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps } from 'vue';
  
  const props = defineProps({
    title: String,
  });
  </script>
  
  <style scoped>
  .chart-card {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(15, 15, 15, 0.854);
  }
  .chart-container {
    position: relative;
    height: 300px; /* adjust as needed */
  }
  </style>
  