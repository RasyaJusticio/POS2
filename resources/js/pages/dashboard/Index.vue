<template>
  <main class="dashboard">
    <div class="container">
      <h1 class="title">Graphic</h1>

      <div class="stats">
        <StatCard 
          title="Total Sales" 
          :value="formatCurrency(totalSales)" 
          iconClass="fas fa-dollar-sign" 
        /> 
        <StatCard  
          title="Total Reservation" 
          :value="totalReservations" 
          iconClass="fas fa-box" 
          @click="navigateToReservation"
        />
        <StatCard 
          title="Total Customers" 
          :value="totalCustomers" 
          iconClass="fas fa-users" 
        />
        <StatCard 
          title="Profit" 
          :value="formatCurrency(profit)" 
          iconClass="fas fa-chart-line" 
        />
      </div>

      <div class="charts">
        <ChartCard title="Sales Over Time">
          <canvas id="salesChart"></canvas>
        </ChartCard>
        <ChartCard title="Top Selling Items">
          <canvas id="topItemsChart"></canvas>
        </ChartCard>
      </div>

      <!-- Menampilkan Total Reservations -->
      <div>

      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';  // Import useRouter
import StatCard from './StatCard.vue';
import ChartCard from './ChartCard.vue';
import Chart from 'chart.js/auto';
import axios from 'axios'; // Import axios untuk API call

const router = useRouter();  // Inisialisasi router
const totalSales = ref(0);
const totalItems = ref(0); // Number of reservations from the API
const totalCustomers = ref(0); // Ini adalah state untuk total customers
const profit = ref(0);

const navigateToReservation = () => {
  router.push({ name: 'dashboard.inventori.reservation' });  // Arahkan ke halaman reservasi
};
// Reactive state untuk total reservations
const totalReservations = ref(0);

// Fetch totalItems (reservations count) dari API
const fetchTotalItems = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/reservations/count');
    if (!response.ok) {
      throw new Error('Failed to fetch reservation count');
    }
    const data = await response.json();
    totalItems.value = data.totalItems;
  } catch (error) {
    console.error('Error fetching reservation count:', error);
  }
};

// Fetch totalReservations dari API (menampilkan total reservasi)
const fetchTotalReservations = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/reservations/count');
    totalReservations.value = response.data.totalItems;
  } catch (error) {
    console.error('Error fetching total reservations:', error);
  }
};

// Fetch totalCustomers dari API (menampilkan total customers)
const fetchTotalCustomers = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/total-customers');
    totalCustomers.value = response.data.total_customers;
  } catch (error) {
    console.error('Error fetching total customers:', error);
  }
};

// Memanggil fungsi saat komponen dimuat
onMounted(() => {
  fetchTotalItems();
  fetchTotalReservations();
  fetchTotalCustomers(); // Tambahkan ini untuk mengambil total customer
  initializeCharts();
});

// Format currency to Rupiah
const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value);
};

// Fungsi untuk menginisialisasi charts
const initializeCharts = () => {
  const salesCtx = document.getElementById('salesChart')?.getContext('2d');
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

  const topItemsCtx = document.getElementById('topItemsChart')?.getContext('2d');
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
