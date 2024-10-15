<template>
  <div class="card w-100 text-center mb-10 p-10 ">
    <div class="card-body">
      <div class="card-title d-flex justify-content-center align-items-center">
        <i :class="[iconClass, 'large-icon', 'text-secondary-emphasis', 'me-10']"></i>
        <span class="value fw-bold fs-2 mb-0">{{ value }}</span>
      </div>
        <h3 class="">{{ title }}</h3>
    </div>
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
  padding: 10px;
  border-radius: 8px;
  box-shadow: 2px 4px 4px hsla(0, 0%, 10%, 0.461);
  align-items: flex-start; /* Mengatur item ke atas */
}

.large-icon {
  font-size: 5rem; /* Ukuran ikon */
  color: #000000; /* Warna ikon */
  margin-right: 100px; /* Jarak antara ikon dan nilai */
  margin-left: 10px; /* Jarak ke kiri untuk memberi ruang di pojok */
}


.value {
  font-size: 2.5rem; /* Sesuaikan ukuran untuk nilai */
  font-weight: bold;
  margin-left: 10px; /* Jarak antara ikon dan nilai */
}


h3 {
  margin-left: 10px; /* Jarak antara ikon-value dan judul */
}

</style>
