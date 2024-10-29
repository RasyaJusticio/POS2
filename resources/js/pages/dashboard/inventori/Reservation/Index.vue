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
      <div class="row align-items-center mb-4">
        <!-- Filter by Date -->
        <div class="col-md-4">
          <div class="fv-row">
            <label class="form-label fw-bold fs-6 required" for="reservation-date">
              <i class="la la-calendar"></i> Filter by Date
            </label>
            <Datepicker
              v-model="selectedDate"
              @change="filterByDate"
              :input-class="'form-control form-control-lg form-control-solid'"
              :format="'yyyy-MM-dd'"
              placeholder="Select a date"
            />
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
            <th>Orders</th>
            <th>Total</th>
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
            <td>
                <div>
                    <ul class="list-unstyled">
                        <li v-for="(item, index) in reservation.menus.split('\n')" :key="index" class="mb-2">
                            {{ item }}
                        </li>
                    </ul>
                </div>
            </td>
            <td>{{ formatRupiah(reservation.total_price) }}</td>
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
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

// State variables
const reservations = ref<any[]>([]);
const filteredReservations = ref<any[]>([]);
const selectedDate = ref('');
const sortOrder = ref('asc'); // Sorting order
const sortStatus = ref(''); // Sort by status
const totalReservations = ref(0); // Total Reservations
const totalGuests = ref(0); // Total Guests

// Format currency to Rupiah
const formatRupiah = (amount: number) => {
  if (isNaN(amount)) return "Rp 0";  // Prevent NaN
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
  }).format(amount);
};

// Fetch reservations only once when the component mounts
const fetchReservations = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/reservations');
    reservations.value = response.data.reservations; // Save all fetched reservations
    filteredReservations.value = [...reservations.value]; // Initialize filteredReservations
    sortReservations(); // Apply default sorting
    calculateTotals();  // Calculate totals after data is fetched
  } catch (error) {
    console.error('Error fetching reservations:', error);
  }
};

// Function to filter reservations by selected date
const filterByDate = () => {
  if (selectedDate.value) {
    filteredReservations.value = reservations.value.filter(
      (reservation: any) => reservation.date === selectedDate.value
    );
  } else {
    filteredReservations.value = [...reservations.value]; // If no date is selected, show all
  }
  sortReservations(); // Apply sorting after filtering
  calculateTotals(); // Update totals after filtering
};

// Function to sort reservations and calculate totals
const sortReservations = () => {
  if (sortStatus.value) {
    filteredReservations.value = reservations.value.filter(reservation => 
      (sortStatus.value === 'active' && !isReservationEnded(reservation)) || 
      (sortStatus.value === 'ended' && isReservationEnded(reservation))
    );
  } else {
    filteredReservations.value = [...reservations.value];
  }

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
  // Printing logic here
};

// Function to export reservations to Excel
const exportReservations = async () => {
  // Exporting logic here
};

// When the component mounts, fetch reservations
onMounted(() => {
  fetchReservations();
});
</script>

<style scoped>
.form-label {
  margin-bottom: 0.5rem;
}
.form-control {
  margin-bottom: 1rem;
}
.card-body {
  padding: 1.5rem;
}
.card-header {
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
}
.card-header h2 {
  font-size: 1.75rem;
  font-weight: bold;
}
</style>
