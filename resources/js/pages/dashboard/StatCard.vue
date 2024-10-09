<template>
  <div class="stat-card">
    <!-- Bagian pertama: Icon dan value dengan ukuran besar -->
    <div class="icon-value">
      <i :class="iconClass" class="large-icon"></i>
      <p class="value">{{ value }}</p>
    </div>
    <!-- Bagian kedua: Judul -->
    <h3>{{ title }}</h3>
    
  </div>
</template>

<script setup lang="ts">
defineProps({
  title: String,
  value: [String, Number],
  iconClass: String,
});
</script>

<style scoped>
.stat-card {
  flex: 1;
  background: #ffffff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 2px 4px 4px hsla(0, 0%, 10%, 0.461);
  align-items: flex-start; /* Mengatur item ke atas */
}

.icon-value {
  display: flex;
  align-items: center; /* Align icon and value vertically */
  justify-content: center; /* Center content horizontally */
  margin-bottom: 10px; /* Space below the icon-value */
}

.large-icon {
  font-size: 5rem; /* Ukuran ikon */
  color: #000000; /* Warna ikon */
  margin-right: 100px; /* Jarak antara ikon dan nilai */
  margin-left: 10px; /* Jarak ke kiri untuk memberi ruang di pojok */
}

.value {
  font-size: 2rem; /* Sesuaikan ukuran untuk nilai */
  font-weight: bold;
  margin-left: 5px; /* Jarak untuk nilai */
}

h3 {
  margin-left: 10px; /* Jarak antara ikon-value dan judul */
}

</style>
