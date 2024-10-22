<template>
  <VForm class="form card mb-10" id="form-reservation" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Reservations List</h2>

      <!-- Button Container for Print and Export -->
      <div class="d-flex ms-auto">
        <button
          type="button"
          class="btn btn-sm btn-secondary me-2"
          @click="printReservations"
        >
          <i class="la la-print me-1 fs-4"></i> Print
        </button>

        <button
          type="button"
          class="btn btn-sm btn-secondary"
          @click="exportReservations"
        >
          <i class="la la-file-excel me-1 fs-4"></i> Export Excel
        </button>

      </div>
    </div>

    <!-- Filter, Sort and Total section -->
    <div class="card-body">
      <!-- Hanya bagian ini yang diubah untuk menggunakan datepicker -->
      <div class="row">
        <div class="col-md-4 mb-4">
          <label class="form-label fw-bold fs-6 required">
            <i class="la la-calendar"></i> Filter By Date (Start)
          </label>
          <Datepicker v-model="startDate" class="form-control" />
        </div>
        <div class="col-md-4 mb-4">
          <label class="form-label fw-bold fs-6 required">
            <i class="la la-calendar"></i> Filter By Date (End)
          </label>
          <Datepicker v-model="endDate" class="form-control" />
        </div>
        <div class="col-md-4 mb-4 d-flex align-items-end">
          <button class="btn btn-primary" @click="filterByDate">
            Filter
          </button>
        </div>
      </div>

      <!-- Sort by Date -->
      <div class="col-md-4">
        <div class="fv-row">
          <label class="form-label fw-bold fs-6 required" for="sort-date">
            <i class="la la-sort"></i> Sort by Date
          </label>
          <select
            id="sort-date"
            v-model="sortOrder"
            @change="sortReservations"
            class="form-control form-control-lg form-control-solid"
          >
            <option value="asc">Oldest First</option>
            <option value="desc">Newest First</option>
          </select>
        </div>
      </div>

      <!-- Sort by Status -->
      <div class="col-md-4">
        <div class="fv-row">
          <label class="form-label fw-bold fs-6 required" for="sort-status">
            <i class="la la-toggle-on"></i> Sort by Status
          </label>
          <select
            id="sort-status"
            v-model="sortStatus"
            @change="sortReservations"
            class="form-control form-control-lg form-control-solid"
          >
            <option value="">All</option>
            <option value="active">Active</option>
            <option value="ended">Reservation Ended</option>
          </select>
        </div>
      </div>

      <!-- Total Reservations and Guests -->
      <div class="col-md-4 text-center my-4">
        <div class="d-flex justify-content-center">
          <h5 class="mb-0 me-3">
            Total Reservations: <span class="badge bg-primary fs-5 text-white">{{ totalReservations }}</span>
          </h5>
          <h5 class="mb-0">
            Total Guests: <span class="badge bg-success fs-5 text-white">{{ totalGuests }}</span>
          </h5>
        </div>
      </div>
    </div>

    <!-- Reservations Table -->
    <div class="card-body">
      <div v-if="filteredReservations.length === 0" class="alert alert-warning">
        No reservations available for this date.
      </div>

      <table v-else class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Guests</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reservation in filteredReservations" :key="reservation.id" :class="getReservationClass(reservation)">
            <td>{{ reservation.id }}</td>
            <td>{{ reservation.name }}</td>
            <td>{{ reservation.phone }}</td>
            <td>{{ reservation.date }}</td>
            <td>{{ reservation.start_time }}</td>
            <td>{{ reservation.end_time }}</td>
            <td>{{ reservation.guests }}</td>
            <td>{{ getReservationStatus(reservation) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </VForm>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Datepicker from "vue3-datepicker"; // Import vue3-datepicker

// State variables
const reservations = ref<any[]>([]);
const filteredReservations = ref<any[]>([]);
const startDate = ref('');
const endDate = ref(''); // Tambahkan ref untuk endDate
const sortOrder = ref('asc'); // Sorting order
const sortStatus = ref(''); 
const totalReservations = ref(0);
const totalGuests = ref(0);

// Fetch reservations only once when the component mounts
const fetchReservations = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/reservations');
    reservations.value = response.data.reservations; // Save all fetched reservations
    filteredReservations.value = [...reservations.value]; // Initialize filteredReservations
    sortReservations(); // Apply default sorting
    calculateTotals();  // Calculate totals on initial load
  } catch (error) {
    console.error('Error fetching reservations:', error);
  }
};

// Function to filter reservations by selected date
const filterByDate = () => {
  if (startDate.value && endDate.value) {
    console.log("Start Date:", new Date(startDate.value));
    console.log("End Date:", new Date(endDate.value));

    const start = new Date(startDate.value);
    const end = new Date(endDate.value);

    filteredReservations.value = reservations.value.filter((reservation) => {
      const reservationDate = new Date(reservation.date);
      console.log("Reservation Date:", reservationDate);
      return reservationDate >= start && reservationDate <= end;
    });

    console.log("Filtered Reservations:", filteredReservations.value);
  } else {
    filteredReservations.value = [...reservations.value];
  }

  sortReservations();
  calculateTotals();
};


const sortReservations = () => {
  // Filter by status first if a status is selected
  if (sortStatus.value) {
    filteredReservations.value = reservations.value.filter(reservation => 
      (sortStatus.value === 'active' && !isReservationEnded(reservation)) || 
      (sortStatus.value === 'ended' && isReservationEnded(reservation))
    );
  } else {
    filteredReservations.value = [...reservations.value];
  }

  // Then sort by date
  filteredReservations.value.sort((a: any, b: any) => {
    const dateA = new Date(a.date);
    const dateB = new Date(b.date);
    return sortOrder.value === 'asc' ? dateA.getTime() - dateB.getTime() : dateB.getTime() - dateA.getTime();
  });

  calculateTotals();  // Recalculate total reservations and guests after sorting
};


// Function to calculate total reservations and guests
const calculateTotals = () => {
  totalReservations.value = filteredReservations.value.length;
  totalGuests.value = filteredReservations.value.reduce((sum, reservation) => sum + reservation.guests, 0);
};

// Function to check if a reservation has ended based on current time
const isReservationEnded = (reservation: any) => {
  const now = new Date();
  const endTime = new Date(`${reservation.date} ${reservation.end_time}`);
  return now > endTime;
};

// Function to return CSS class based on reservation status
const getReservationClass = (reservation: any) => {
  return isReservationEnded(reservation) ? 'table-danger' : '';
};

// Function to return reservation status as 'Active' or 'Ended'
const getReservationStatus = (reservation: any) => {
  return isReservationEnded(reservation) ? 'Reservation Ended' : 'Active';
};

// Function to print reservations
const printReservations = () => {
  // Cek apakah filteredReservations, totalReservations, dan totalGuests terdefinisi
  if (!filteredReservations || !totalReservations || !totalGuests) {
    console.error("Reservations data not found");
    return;
  }

  // Path ke gambar logo, pastikan logo bisa diakses
  const logoPath = "{{ asset('media/avatars/spice.png') }}";

  const printContent = `
    <style>
      body {
        font-family: Arial, sans-serif;
        padding: 20px;
        color: #333;
        background-color: #f9f9f9;
      }
      h1 {
        color: #4A90E2;
        font-weight: 600;
        margin-bottom: 10px;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
      }
      table, th, td {
        border: 1px solid #333;
        padding: 10px;
      }
      th {
        background-color: #f2f2f2;
        text-align: left;
      }
    </style>

    <div style="text-align: center;">
      <img src="${logoPath}" alt="Logo" style="width: 100px; height: auto;">
      <h1>Reservations List</h1>
      <p>Total Reservations: ${totalReservations.value}</p>
      <p>Total Guests: ${totalGuests.value}</p>
    </div>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Date</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Guests</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        ${filteredReservations.value.map(reservation => `
          <tr>
            <td>${reservation.id}</td>
            <td>${reservation.name}</td>
            <td>${reservation.phone}</td>
            <td>${reservation.date}</td>
            <td>${reservation.start_time}</td>
            <td>${reservation.end_time}</td>
            <td>${reservation.guests}</td>
            <td>${getReservationStatus(reservation)}</td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;

  const printWindow = window.open('', '_blank');
  printWindow?.document.write(printContent);
  printWindow?.document.close();
  printWindow?.focus();
  printWindow?.print();
  printWindow?.close();
};

const exportReservations = () => {
  console.log("Export to Excel");
};

// When the component mounts, fetch reservations
onMounted(() => {
  fetchReservations();
});

</script>
