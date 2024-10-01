<template>
  <div class="payment-page">
    <h1 class="title">Transaksi Pembayaran</h1>
    <img src="@/assets/images/spice.png" alt="Logo" class="logo" /> <!-- Tambahkan Logo di sini -->
    <div v-if="!receiptVisible" class="payment-options">
      <div class="payment-option" @click="selectPayment('cash')">
        <i class="fas fa-money-bill-wave"></i>
        <h2>Cash</h2>
        <p>Pembayaran menggunakan Uang Tunai.</p>
      </div>
      <div class="payment-option" @click="selectPayment('debit')">
        <i class="fas fa-credit-card"></i>
        <h2>Debit</h2>
        <p>Pembayaran menggunakan kartu Debit.</p>
      </div>
    </div>

    <div v-if="selectedPayment && !receiptVisible" class="payment-details">
      <h3>Metode Pembayaran: {{ selectedPayment }}</h3>
      <button @click="showConfirmationModal = true" class="btn btn-success">Konfirmasi Pembayaran</button>
    </div>

    <div v-if="receiptVisible" class="receipt">
      <h2>Struk Pembayaran</h2>
      <p>Nomer Antrian: {{ queueNumber }}</p>

      
      <div class="summary">
        <h3>Detail Pembayaran</h3>
        <p>Metode Pembayaran: {{ selectedPayment }}</p>
        <h4>Item Pesanan:</h4>
    <ul>
      <li v-for="item in cart" :key="item.id">
        {{ item.name }} - {{ item.quantity }} x {{ currency(item.price) }} = {{ currency(item.price * item.quantity) }}
      </li>
    </ul>
        <p><strong>Total Pembayaran: {{ currency(total) }}</strong></p>
        <p class="payment-instruction">Silakan bayar di kasir</p> <!-- Tambahkan teks di sini -->
      </div>
     
      <div v-if="selectedPayment === 'debit'" class="qr-code">
        <h3>Scan QR Code untuk Pembayaran</h3>
        <img :src="generateQRCode()" alt="QR Code" />
      </div>
    </div>

    </div>
    <!-- Modal Konfirmasi -->
    <div v-if="showConfirmationModal" class="modal">
      <div class="modal-content">
        <h3>Konfirmasi Pembayaran</h3>
        <p>Apakah Anda yakin ingin melanjutkan pembayaran dengan {{ selectedPayment }}?</p>
        <button @click="confirmPayment" class="btn btn-success">Ya</button>
        <button @click="cancelPayment" class="btn btn-secondary">Tidak</button>
      </div>
    </div>
</template>

<script setup lang="ts">
import { currency } from '@/libs/utils';
import { useRoute } from 'vue-router';
import { ref, computed, onMounted } from 'vue';
import QRCode from 'qrcode-generator';
import backgroundImage from '@/assets/images/ppp.png'; // Sesuaikan dengan path gambar Anda

const route = useRoute();
const cart = ref([]);
const selectedPayment = ref(null);
const receiptVisible = ref(false);
const queueNumber = ref(null);
const showConfirmationModal = ref(false);
const orderItems = ref([]);

function selectPayment(method: string) {
  selectedPayment.value = method;
}

function confirmPayment() {
  queueNumber.value = Math.floor(Math.random() * 1000);
  receiptVisible.value = true;
  showConfirmationModal.value = false; // Tutup modal setelah konfirmasi
}

// Fungsi untuk membatalkan pembayaran
function cancelPayment() {
  showConfirmationModal.value = false; // Tutup modal tanpa melakukan apa-apa
}

function reset() {
  selectedPayment.value = null;
  receiptVisible.value = false;
  queueNumber.value = null;
}

onMounted(() => {
  const cartParam = route.query.cart;
  if (cartParam) {
    cart.value = JSON.parse(cartParam);
  }
});

const total = computed(() => {
  return cart.value.reduce((acc, item) => acc + (item.price * item.quantity), 0);
});

const totalItems = computed(() => {
  return orderItems.value.reduce((acc, item) => acc + item.quantity, 0);
});


function generateQRCode() {
  const qr = QRCode(0, 'H');
  const paymentInfo = `Pembayaran: ${total.value}`;
  qr.addData(paymentInfo);
  qr.make();
  return qr.createDataURL(4);
}
</script>

<style>
body {
  background: url('@/assets/images/ppp.png') no-repeat center center fixed;
  background-size: cover;
  font-family: 'Arial', sans-serif;
}

.payment-page {
  max-width: 600px;
  margin: auto;
  text-align: center;
  padding: 20px;
  background: #ffffff;
  border-radius: 50px;
  box-shadow: 0 6px 1000px rgba(0, 0, 0, 0.2);
}

.title {
  font-size: 28px;
  color: #ffffff;
  margin-bottom: 20px;
}

.payment-options {
  display: flex;
  justify-content: space-around;
  margin: 20px 0;
}

.payment-option {
  cursor: pointer;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 12px;
  transition: all 0.3s;
  background: #f0f0f0;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  text-align: center;
  flex: 1;
  margin: 0 10px;
}

.payment-option:hover {
  background: #e0f7fa;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  color: #fff;
  cursor: pointer;
  font-size: 16px;
}

.btn-success {
  background-color: #28a745;
}

.btn-black {
  background-color: #000000; /* Warna hitam */
  color: #ffffff; /* Warna teks putih */
  padding: 10px 20px; /* Padding untuk tombol */
  border: none; /* Tanpa border */
  border-radius: 5px; /* Radius sudut */
  cursor: pointer; /* Pointer saat hover */
  font-size: 16px; /* Ukuran font */
  transition: background 0.3s; /* Transisi saat hover */
}

.btn-black:hover {
  background-color: #444444; /* Warna lebih terang saat hover */
}


.btn-secondary {
  background-color: #000000;
}

.receipt {
  margin-top: 90px;
  padding: 20px;
  padding-left: 150px;
  padding-right: 150px;
  background: #e0f7fa;
  border-radius: 20px;
  box-shadow: 10px 15px 15px rgba(0, 0, 0, 0.1);
  max-width: 800px; /* Atur lebar maksimum lebih besar */
  width: 100%; /* Agar bisa responsif di layar kecil */
  height: auto; /* Tinggi otomatis sesuai konten */
  overflow: auto; /* Tambahkan scroll jika konten terlalu tinggi */
  margin: 20px auto; /* Centerkan di tengah */
}


.qr-code {
  margin-top: 20px;
  margin-bottom: auto;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;xa
}

.modal-content {
  background: #ffffff;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  text-align: center;
  max-width: 500px;
  width: 90%;
  transition: transform 0.3s ease;
  transform: translateY(20px);
}

.modal-content h3 {
  font-size: 24px;
  margin-bottom: 10px;
  color: #333;
}

.modal-content p {
  font-size: 16px;
  margin-bottom: 20px;
  color: #555;
}

.modal-content .btn {
  padding: 12px 24px;
  border: none;
  border-radius: 5px;
  color: #ffffff;
  cursor: pointer;
  font-size: 16px;
  margin: 0 10px;
  transition: background 0.3s ease;
}

.modal-content .btn-success {
  background-color: #000000; /* Hijau Mewah */
}

.modal-content .btn-secondary {
  background-color: #8e8e8e; /* Merah Mewah */
}

.modal-content .btn-success:hover {
  background-color: #ff0000; /* Hijau Gelap Saat Hover */
}

.modal-content .btn-secondary:hover {
  background-color: #ff0000; /* Merah Gelap Saat Hover */
}


.qr-code img {
  width: 200px;
  height: 200px;
}

.logo {
  max-width: 400px; /* Atur lebar maksimum logo */
  margin: 30px auto; /* Jarak atas dan bawah */
  display: block; /* Membuat logo menjadi blok agar bisa center */
}


.summary {
  margin-top: 20px;
}

.payment-instruction {
  font-size: 20px; /* Ukuran font untuk instruksi */
  color: #000000; /* Warna teks, sesuaikan dengan desain */
  margin-top: 10px; /* Jarak atas jika diperlukan */
}

</style>
