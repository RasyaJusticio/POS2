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
    <!-- Sales Over Time Chart -->
    <ChartCard title="Customer Over Time">
      <canvas id="salesChart"></canvas>
    </ChartCard>

    <!-- Top Selling Items Chart -->
    <ChartCard title="Top Selling Items">
      <canvas id="topItemsChart"></canvas>
    </ChartCard>
  </div>

      <!-- Menampilkan Total Reservations -->
      <div>
        <!-- Anda dapat menambahkan komponen lain di sini jika diperlukan -->
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

// Inisialisasi router
const router = useRouter();

// Reactive state untuk total penjualan, item, pelanggan, profit, dan reservasi
const totalSales = ref(0);
const totalItems = ref(0); // Jumlah reservasi dari API
const totalCustomers = ref(0); // Jumlah pelanggan
const profit = ref(0);
const totalReservations = ref(0); // Total reservasi

// Fungsi inisialisasi saat komponen dimuat
onMounted(() => {
  initializeSalesChart();
  initializeTopItemsChart(); // Inisialisasi chart produk terlaris
  fetchTotalItems();
  fetchTotalReservations();
  fetchTotalCustomers(); // Menambahkan ini untuk mengambil total pelanggan
});

// Fungsi untuk inisialisasi chart 'Sales Over Time'
const initializeSalesChart = () => {
  const salesCtx = document.getElementById('salesChart')?.getContext('2d');
  if (!salesCtx) return; // Tambahkan pengecekan untuk menghindari error jika elemen tidak ada

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
};

// Fungsi untuk inisialisasi chart 'Top Selling Items'
const initializeTopItemsChart = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/top-selling-items');
    console.log(response.data);  // Debugging line to check the response

    const topItems = response.data;
    const labels = topItems.map(item => item.name);  // Gunakan nama produk sebagai label
    const data = topItems.map(item => item.total_sold);  // Gunakan jumlah terjual untuk data

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


// Fungsi untuk fetch total items (reservations count) dari API
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

// Fungsi untuk fetch total reservasi dari API
const fetchTotalReservations = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/reservations/count');
    totalReservations.value = response.data.totalItems;
  } catch (error) {
    console.error('Error fetching total reservations:', error);
  }
};

// Fungsi untuk fetch total pelanggan dari API
const fetchTotalCustomers = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/total-customers');
    totalCustomers.value = response.data.total_customers;
  } catch (error) {
    console.error('Error fetching total customers:', error);
  }
};

// Format currency to Rupiah
const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(value);
};

// Fungsi untuk navigasi ke halaman reservasi
const navigateToReservation = () => {
  router.push({ name: 'dashboard.inventori.reservation' });
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
