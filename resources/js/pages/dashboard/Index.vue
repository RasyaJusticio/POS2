<template>
  <main class="dashboard">
    <div class="container">
      <h1 class="title">Graphic</h1>
      
      <div class="stats">
        <StatCard title="Total Sales" :value="totalSales" iconClass="fas fa-dollar-sign" /> 
        <StatCard title="Total Items" :value="totalItems" iconClass="fas fa-box" />
        <StatCard title="Total Customers" :value="totalCustomers" iconClass="fas fa-users" />
        <StatCard title="Profit" :value="profit" iconClass="fas fa-chart-line" />
      </div>

      <div class="charts">
        <ChartCard title="Sales Over Time">
          <canvas id="salesChart"></canvas>
        </ChartCard>
        <ChartCard title="Top Selling Items">
          <canvas id="topItemsChart"></canvas>
        </ChartCard>
      </div>
    </div>
  </main>
</template>


<script setup lang="ts">
import { ref, onMounted } from 'vue';
import StatCard from './StatCard.vue';
import ChartCard from './ChartCard.vue';
import Chart from 'chart.js/auto';

const totalSales = ref(0);
const totalItems = ref(0);
const totalCustomers = ref(0);
const profit = ref(0);

onMounted(() => {
  // Fetch data from your API or state management
  // Simulated data for demonstration
  totalSales.value = 178900;
  totalItems.value = 30;
  totalCustomers.value = 7500;
  profit.value = 1900;

  initializeCharts();
});

const initializeCharts = () => {
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{
        label: 'Sales',
        data: [1200, 1900, 3000, 5000, 4200],
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2,
        fill: false,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
  new Chart(topItemsCtx, {
    type: 'bar',
    data: {
      labels: ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5'],
      datasets: [{
        label: 'Top Selling Items',
        data: [12, 19, 3, 5, 2],
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
};
</script>

<style scoped>
.container {
  padding: 20px;
}
.title {
  margin-bottom: 20px;
}
.stats {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}
.charts {
  display: flex;
  gap: 20px;
}
.large-icon {
  font-size: 10rem; /* Ukuran lebih besar untuk ikon */
  color: #000000; /* Warna ikon yang lebih mencolok */
  margin-bottom: 10px; /* Jarak antara ikon dan teks */
}
</style>
