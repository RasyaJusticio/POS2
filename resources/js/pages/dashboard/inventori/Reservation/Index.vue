<template>
  <VForm class="form card mb-10" @submit="submit" id="form-reservation" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Reservations List</h2>
    </div>

    <!-- Filter and Sort section -->
    <div class="card-body">
      <div class="row align-items-center mb-4">
        <!-- Items per page -->
        <div class="col-md-4">
          <label class="form-label fw-bold fs-6">Items per page:</label>
          <select
            v-model="itemsPerPage"
            @change="paginateReservations"
            class="form-select form-select-solid"
            style="width: 120px;"
          >
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
          </select>
        </div>

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
          <tr v-for="reservation in paginatedReservations" :key="reservation.id" :class="getReservationClass(reservation)">
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

      <!-- Pagination controls -->
      <div class="d-flex justify-content-center mt-4">
        <!-- Previous Page Button -->
        <button
          class="btn btn-secondary me-2"
          @click="previousPage"
          :disabled="currentPage === 1"
        >
          Previous
        </button>
        <!-- Next Page Button -->
        <button
          class="btn btn-secondary"
          @click="nextPage"
          :disabled="currentPage === totalPages"
        >
          Next
        </button>
      </div>
    </div>
  </VForm>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const reservations = ref<any[]>([]);
const filteredReservations = ref<any[]>([]);
const paginatedReservations = ref<any[]>([]);
const selectedDate = ref('');
const sortOrder = ref('asc'); // Sorting order
const itemsPerPage = ref(5); // Items per page
const currentPage = ref(1); // Track current page

// Fetch reservations only once when the component mounts
const fetchReservations = async () => {
  try {
    const response = await axios.get('http://localhost:8000/api/reservations');
    reservations.value = response.data.reservations; // Save all fetched reservations
    filteredReservations.value = [...reservations.value]; // Initialize filteredReservations
    sortReservations(); // Apply default sorting
    paginateReservations(); // Initial pagination
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
  paginateReservations(); // Apply pagination after filtering
};

// Function to sort reservations by date
const sortReservations = () => {
  filteredReservations.value.sort((a: any, b: any) => {
    const dateA = new Date(a.date);
    const dateB = new Date(b.date);

    return sortOrder.value === 'asc' ? dateA.getTime() - dateB.getTime() : dateB.getTime() - dateA.getTime();
  });
  paginateReservations(); // Re-paginate after sorting
};

// Function to handle pagination
const paginateReservations = () => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  paginatedReservations.value = filteredReservations.value.slice(start, end); // Paginate filteredReservations
};

// Function to handle Next Page
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    paginateReservations(); // Update view to next page
  }
};

// Function to handle Previous Page
const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    paginateReservations(); // Update view to previous page
  }
};

// Computed property to calculate total pages based on filtered data and items per page
const totalPages = computed(() => {
  return Math.ceil(filteredReservations.value.length / itemsPerPage.value);
});

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
