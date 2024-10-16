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
      </div>

      <div class="charts">
        <ChartCard title="Sales Over Time">
          <canvas id="salesChart"></canvas>
        </ChartCard>  
      </div>

      <!-- Menampilkan Total Reservations -->
      <div></div>
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
  gap: 10px;
  margin-bottom: 20px;
  
  background: var(--card-bg);
  color: var(--text-color);
  padding: 10px;
  border-radius: 8px;
  box-shadow: 2px 4px 4px hsla(0, 0%, 10%, 0.461);
  flex: 1;
  transition: background 0.3s ease, color 0.3s ease;
}

.charts {
  background: transparent; /* Membuat background chart transparan */
  border: none; /* Menghilangkan border */
  flex: 1;
}

.large-icon {
  font-size: 10rem; /* Ukuran lebih besar untuk ikon */
  color: var(--text-color); /* Menggunakan variabel untuk warna */
  margin-bottom: 10px; /* Jarak antara ikon dan teks */
}

:root {
  --card-bg: #ffffff; /* Background untuk mode terang */
  --card-bg-dark: #1a202c; /* Background untuk mode gelap */
  --text-color: #000000; /* Warna teks untuk mode terang */
  --text-color-dark: #ffffff; /* Warna teks untuk mode gelap */
  --chart-bg: rgba(255, 255, 255, 0.5); /* Background chart transparan untuk mode terang */
  --chart-bg-dark: rgba(0, 0, 0, 0.5); /* Background chart transparan untuk mode gelap */
}

.stat-card {
  background: var(--card-bg);
  color: var(--text-color);
  padding: 10px;
  border-radius: 8px;
  box-shadow: 2px 4px 4px hsla(0, 0%, 10%, 0.461);
  flex: 1;
  transition: background 0.3s ease, color 0.3s ease;
}

.stat-card h3 {
  margin-left: 10px;
  color: var(--text-color);
}

/* Dark mode styles */
.dark .stat-card {
  background: var(--card-bg-dark);
  color: var(--text-color-dark);
}

.dark .stat-card h3 {
  color: var(--text-color-dark);
}

.chart-card {
  background: transparent; /* Membuat background chart transparan */
  border: none; /* Menghilangkan border */
  flex: 1;
  transition: background 0.3s ease;
}

.dark .chart-card {
  background: var(--chart-bg-dark); /* Transparan untuk dark mode */
}
</style>
