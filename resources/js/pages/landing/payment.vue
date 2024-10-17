<template>
  <div class="payment-page">
    <video autoplay muted loop class="background-video">
      <source src="@/assets/images/asiks.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <h1 class="title" :style="{ color: '#091057' }"></h1>
    <img src="@/assets/images/spice.png" alt="Logo" class="logo" /> <!-- Tambahkan Logo di sini -->

    <router-link to="/landing/PAGE">
              <button class="btn btn-lg btn-primary" style="font-weight: bold;">BACK</button>
            </router-link>
            


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
        <p class="payment-instruction">Silakan bayar di kasir Melalui Cash / Debit </p> <!-- Tambahkan teks di sini -->
      </div>
     
      <div v-if="selectedPayment === 'debit'" class="qr-code">
        <h3>Scan QR Code & QRIS Payment </h3>
        <img :src="generateQRCode()" alt="QR Code" />
      </div>
      <button @click="downloadReceipt" class="btn btn-success">Download Receipt</button>
    </div>

    </div>
    <!-- Modal Konfirmasi -->
    <div v-if="showConfirmationModal" class="modal">
      <div class="modal-content">
        <h3>Konfirmasi Pembayaran</h3> click
        <p>Apakah Anda yakin ingin melanjutkan pembayaran?</p> 
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
import jsPDF from 'jspdf';
import axios from 'axios';

const route = useRoute();
const cart = ref([]);
const selectedPayment = ref(null);
const receiptVisible = ref(false);
const queueNumber = ref(null);
const showConfirmationModal = ref(false);
const orderId = ref(""); // This should be dynamically generated
const totalAmount = ref(0);
const snapToken = '{{ $snapToken }}';
const blueColor = '#0000FF';  // Atau warna biru hex atau nama warna



function selectPayment(method: string) {
  selectedPayment.value = method;
}

function downloadReceipt() {
  const doc = new jsPDF();

  // Header
  doc.setFontSize(16);
  doc.text("==================================", 20, 40);
  doc.text("Toko : Siam Spice Co.", 20, 10);
  doc.setFontSize(12);
  doc.text("==================================", 20, 40);
  doc.text("Jl. Jendral Sudirman. 10, Surabaya", 20, 20);
  doc.text(`Tanggal: ${new Date().toLocaleString()}`, 20, 30);
  doc.text("==================================", 20, 40);
  
  // Receipt Title
  doc.setFontSize(14);
  doc.setFont("Helvetica", "bold"); // Set font to bold
  doc.text("Struk Pembayaran", 20, 50);
  doc.setFont("Helvetica", "normal"); // Reset to normal
  doc.text("==================================", 20, 60);
  
  // Payment Method
  doc.setFontSize(12);
  doc.setFont("Helvetica", "bold"); // Set font to bold for these lines
  doc.text(`Nomer Antrian: ${queueNumber.value}`, 20, 70);
  doc.text(`Metode Pembayaran: ${selectedPayment.value}`, 20, 80);
  doc.setFont("Helvetica", "normal"); // Reset to normal
  
  // Item List
  doc.text("Item Pesanan:", 20, 90);
  doc.text("==================================", 20, 100);
  
  let yOffset = 110; // Y offset for items
  cart.value.forEach(item => {
    const line = `${item.name} - ${item.quantity} x ${currency(item.price)} = ${currency(item.price * item.quantity)}`;
    doc.text(line, 20, yOffset);
    yOffset += 10; // Increase line height
  });

  doc.text("==================================", 20, yOffset);
  yOffset += 10;
  doc.setFont("Helvetica", "bold"); // Set font to bold for the total line
  doc.text(`Total Pembayaran: ${currency(total.value)}`, 20, yOffset);
  doc.setFont("Helvetica", "normal"); // Reset to normal
  yOffset += 10;
  doc.text("Silakan bayar di kasir", 20, yOffset);

  // Footer
  yOffset += 20;
  doc.setFont("Helvetica", "bold"); // Set font to bold for the footer
  doc.text("========THANKS FOR COMING IN HERE!!!!!!!!!!!========", 20, yOffset);
  doc.setFont("Helvetica", "normal"); // Reset to normal

  // Save PDF
  doc.save("struk_pembayaran.pdf");
}

// cara penggunaan: handlePayment(1234-1234-1234-1234) <--
function handlePayment() {
  const pembelian_id = route.params.pembelian_id
  console.log(pembelian_id)
  // const cartQuery: any = route.query.cart;
  // const cart = JSON.parse(cartQuery);

  // // Dapatkan UUID dari url
  // const uuid: any = cart[0]?.uuid;

  // if (!uuid) {
  //   console.warn('UUID tidak terdeteksi')
  //   return;
  // }

  // axios.post(`/orders/checkout/${uuid}`)
  //   .then(response => {
      // if (window.snap) {
      //   window.snap.pay(response.data.payment_url, {
      //     onSuccess: (result) => {
      //       console.log("Pembayaran berhasil:", result);
      //     }
      //   });
      // }
  //   })
  //   .catch(error => {
  //       console.error('Error during order checkout:', error);
  //   });
}




function showReceipt() {
  queueNumber.value = Math.floor(Math.random() * 1000);
  receiptVisible.value = true;
  showConfirmationModal.value = false;
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

  console.log(cartParam)
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
  margin: 0; /* Menghilangkan margin default */
  font-family: 'Arial', sans-serif;
}

.background-video {
  position: fixed; /* Memperbaiki posisi video */
  top: 0;
  left: 0;
  width: 100%; /* Mengatur lebar video */
  height: 100%; /* Mengatur tinggi video */
  object-fit: cover; /* Menyesuaikan ukuran video agar menutupi area */
  z-index: -1; /* Menempatkan video di belakang konten */
}
.payment-page {
  max-width: 600px;
  margin: auto;
  text-align: center;
  padding: 20px;
  background: #ffffff;
  border-radius: 10px;
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
  margin-top: 20px;
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
  
  max-width: 800px; /* Atur lebar maksimum lebih besar */
  width: 100%; /* Agar bisa responsif di layar kecil */
  height: auto; /* Tinggi otomatis sesuai konten */
  overflow: auto; /* Tambahkan scroll jika konten terlalu tinggi */
  margin: 20px auto; /* Centerkan di tengah */
}


.qr-code {
  border-radius: 30px;
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
  z-index: 1000;
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