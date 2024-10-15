<template>
  <div class="card mb-4">
    <div class="card-body">
      <h4 class="card-title">{{ title }}</h4>
      <div class="chart-container">
        <canvas id="reservationChart"></canvas>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, onMounted, ref } from 'vue';
import { Chart } from 'chart.js';
import axios from 'axios';

const props = defineProps({
  title: String,
});

const chartRef = ref(null);
let reservationChart = null;

onMounted(async () => {
  try {
    // Fetch data from the backend
    const response = await axios.get('/api/reservations/customers-per-month');
    const { months, total_customers } = response.data;

    // Create the chart
    const ctx = document.getElementById('reservationChart').getContext('2d');
    reservationChart = new Chart(ctx, {
      type: 'line', // Change to 'line' chart
      data: {
        labels: months, // Array of month names
        datasets: [
          {
            label: 'Number of Guests',
            data: total_customers, // Data array for guests per month
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: true, // Optional: fill under the line
            tension: 0.4, // Optional: curve the line
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  } catch (error) {
    console.error('Error fetching reservation data:', error);
  }
});
</script>

<style scoped>
.chart-container {
  position: relative;
  height: 300px;
}
</style>
