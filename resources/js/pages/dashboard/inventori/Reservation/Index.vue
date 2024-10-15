<template>
  <VForm class="form card mb-10" @submit="submit" id="form-reservation" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Reservations List</h2>
    </div>

    <!-- Filter, Sort and Total section -->
    <div class="card-body">
      <div class="row align-items-center mb-4">
        <!-- Filter by Date -->
        <div class="col-md-4">
          <div class="fv-row">
            <label class="form-label fw-bold fs-6 required" for="reservation-date">Filter by Date</label>
            <input
              type="date"
              id="reservation-date"
              v-model="selectedDate"
              @change="filterByDate"
              class="form-control form-control-lg form-control-solid"
            />
          </div>
        </div>

        <!-- Sort by Date -->
        <div class="col-md-4">
          <div class="fv-row">
            <label class="form-label fw-bold fs-6 required" for="sort-date">Sort by Date</label>
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

        <!-- Total Reservations and Guests -->
        <div class="col-md-4 text-center">
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

// State variables
const reservations = ref<any[]>([]);
const filteredReservations = ref<any[]>([]);
const selectedDate = ref('');
const sortOrder = ref('asc'); // Sorting order
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
  if (selectedDate.value) {
    filteredReservations.value = reservations.value.filter(
      (reservation: any) => reservation.date === selectedDate.value
    );
  } else {
    filteredReservations.value = [...reservations.value]; // If no date is selected, show all
  }
  
  sortReservations(); // Apply sorting after filtering
  calculateTotals();  // Calculate total reservations and guests
};

// Function to sort reservations by date
const sortReservations = () => {
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

// Fetch reservations when component mounts (only once)
onMounted(() => {
  fetchReservations();
});
</script>

<style scoped>
h5 {
  font-size: 1.25rem; /* Adjust font size as needed */
}
.text-white {
  color: white; /* Set text color to white for numbers */
}
</style>
