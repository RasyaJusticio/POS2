<template>
  <main class="dashboard">
    <div class="container">
      <h1 class="title">Graphic</h1>

      <div class="stats row mb-4">
        <div class="col-md-4">
          <StatCard 
            title="Total Sales" 
            :value="formatCurrency(totalSales)" 
            iconClass="fas fa-dollar-sign"  
            class="stat-card-custom"
          /> 
        </div>
        <div class="col-md-4">
          <StatCard  
            title="Total Reservations" 
            :value="totalReservations" 
            iconClass="fas fa-box" 
            @click="navigateToReservation"
            class="stat-card-custom"
          />
        </div>
        <div class="col-md-4">
          <StatCard 
            title="Total Customers" 
            :value="totalCustomers" 
            iconClass="fas fa-users"
            class="stat-card-custom" 
          />
        </div>
      </div>

      <div class="chart-card">
        <ChartCard title="Customer Over Time">
          <canvas id="salesChart"></canvas>
        </ChartCard>
       
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import StatCard from './StatCard.vue';
import ChartCard from './ChartCard.vue';
import Chart from 'chart.js/auto';
import axios from 'axios';

const router = useRouter();
const totalSales = ref(0);
const totalReservations = ref(0);
const totalCustomers = ref(0);

const navigateToReservation = () => {
  router.push({ name: 'dashboard.inventori.reservation' });
};

const fetchTotalReservations = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/reservations/count');
    totalReservations.value = response.data.totalItems;
  } catch (error) {
    console.error('Error fetching total reservations:', error);
  }
};

const fetchTotalCustomers = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/total-customers');
    totalCustomers.value = response.data.total_customers;
  } catch (error) {
    console.error('Error fetching total customers:', error);
  }
};

const fetchTotalSales = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/totalsales');
    totalSales.value = response.data.totalSales;
  } catch (error) {
    console.error('Error fetching total sales:', error);
  }
};

onMounted(() => {
  fetchTotalReservations();
  fetchTotalCustomers();
  fetchTotalSales();
  initializeCharts();
});

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value);
};

const initializeCharts = () => {
  const salesCtx = document.getElementById('salesChart')?.getContext('2d');
  if (!salesCtx) return;

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

  initializeTopItemsChart(); // Call this function to initialize the top items chart
};

// Function for initializing the 'Top Selling Items' chart
const initializeTopItemsChart = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/top-selling-items');
    const topItems = response.data;
    const labels = topItems.map(item => item.name);
    const data = topItems.map(item => item.total_sold);

    const topItemsCtx = document.getElementById('topItemsChart')?.getContext('2d');
    if (!topItemsCtx) return;

    new Chart(topItemsCtx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Top Selling Items',
          data: data,
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
  } catch (error) {
    console.error('Error fetching top selling items:', error);
  }
};
</script>

<style scoped>
.container {
  padding: 20px;
}

.title {
  margin-bottom: 20px;
}

/* Add box shadow to the stat card */
.stat-card-custom {
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.137); /* Add box shadow */
  border-radius: 10px; /* Optional: Match the card corners */
}

/* Adjusting the styles to fit Bootstrap grid */
/* .stats {
  margin-bottom: 20px;
} */

/* .charts { 
  margin-top: 20px; */
/* } 

/* Ensuring chart cards take full width */
.chart-card {
  width: 100%; /* Full width for chart cards */
}

.chart-card canvas {
  max-width: 100%; /* Ensure canvas scales correctly */
}

/* Responsive styles */
@media (max-width: 768px) {
  .stats {
    flex-direction: column; /* Stack columns on smaller screens */
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.473); /* Add box shadow here */
  }
}
</style>