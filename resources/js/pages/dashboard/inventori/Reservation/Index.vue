<template>
  <VForm class="form card mb-10" id="form-reservation" ref="formRef">
    <div class="card-header align-items-center">
      <h2 class="mb-0">Reservations List</h2>

      <!-- Button Container for Print and Export -->
      <div class="d-flex ms-auto">
        <button
          type="button"
          class="btn btn-sm btn-success me-2"
          @click="printReservations"
        >
          <i class="la la-print me-1 fs-4"></i> Print
        </button>

        <button
          type="button"
          class="btn btn-sm btn-success"
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
  const totalReservationsText = `<h3>Total Reservations: ${totalReservations.value}</h3>`;
  const totalGuestsText = `<h3>Total Guests: ${totalGuests.value}</h3>`;
  
  const printContent = `
    <h1>Reservations List</h1>
    <table border="1" style="border-collapse: collapse; width: 100%;">
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
    ${totalReservationsText}
    ${totalGuestsText}
  `;

  const newWindow = window.open('', '_blank');
  if (newWindow) {
    newWindow.document.write(printContent);
    newWindow.document.close();
    newWindow.print();
    newWindow.close();
  } else {
    console.error("Failed to open a new window.");
  }
};

// Function to export reservations to Excel (dummy function)
const exportReservations = () => {
  alert('Export to Excel feature not yet implemented.');
};

// Call fetchReservations when the component is mounted
onMounted(fetchReservations);
</script>

<style scoped>
.form-label {
  margin-bottom: 0.5rem; /* Spacing between label and input */
}

.form-control {
  margin-bottom: 1rem; /* Spacing between form controls */
}

.card-body {
  padding: 1.5rem; /* Add padding to improve spacing */
}

.card-header {
  padding: 1rem 1.5rem; /* Align with card-body padding */
  display: flex; /* Align header items horizontally */
  justify-content: space-between; /* Space between title and buttons */
}

.card-header h2 {
  font-size: 1.75rem; /* Slightly larger heading */
  font-weight: bold;
}

button {
  font-size: 1.1rem; /* Smaller font for buttons */
  padding: 0.5rem 1rem; /* Consistent button padding */
}

.btn i {
  font-size: 2rem; /* Ukuran ikon lebih besar */
}

.btn-success {
  background-color: #28a745;
}

.table-hover tbody tr:hover {
  background-color: #f2f2f2; /* Light gray hover effect */
}

.table th, .table td {
  text-align: center; /* Center align the table data */
  vertical-align: middle; /* Vertical center align */
}

.table th {
  background-color: #343a40;
  color: white; /* Dark header with white text */
}

.table-bordered {
  border: 1px solid #dee2e6; /* Border around the table */
}

.badge {
  font-size: 1rem;
}

@media (max-width: 768px) {
  .card-header h2 {
    font-size: 1.5rem; /* Smaller heading on small screens */
  }
  button {
    font-size: 0.75rem; /* Adjust button size for smaller screens */
  }
  .table th, .table td {
    font-size: 0.875rem; /* Reduce table font size */
  }
}
</style>
