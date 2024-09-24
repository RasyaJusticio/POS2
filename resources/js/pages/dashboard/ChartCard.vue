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
    background: #ffffff;
    padding: 5px;
    border-radius: 8px;
    box-shadow: 2px 4px 4px rgba(0, 0, 0, 0.418);
    margin-top: center;
    margin-bottom: center;
  }
  .chart-container {
    position: relative;
    height: 300px; /* adjust as needed */
  }
  </style>
  