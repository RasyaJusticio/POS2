<template>
    <div id="app">
      <h1>Sales Data</h1>
  
      <!-- Filter Tanggal -->
      <Datepicker 
        v-model="selectedDate" 
        placeholder="Select a date" 
        :format="dateFormat"
      />
  
      <!-- Tabel Data -->
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Item</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in filteredSales" :key="sale.id">
            <td>{{ sale.id }}</td>
            <td>{{ sale.date }}</td>
            <td>{{ sale.item }}</td>
            <td>{{ sale.amount }}</td>
          </tr>
          <tr v-if="filteredSales.length === 0">
            <td colspan="4" style="text-align: center;">No data found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import Datepicker from '@vuepic/vue-datepicker';
  import '@vuepic/vue-datepicker/dist/main.css';
  
  export default {
    components: { Datepicker },
    data() {
      return {
        selectedDate: null,
        dateFormat: 'yyyy-MM-dd', // Format untuk menampilkan tanggal
        salesData: [
          { id: 1, date: '2024-10-01', item: 'Product A', amount: 100 },
          { id: 2, date: '2024-10-02', item: 'Product B', amount: 200 },
          { id: 3, date: '2024-10-05', item: 'Product C', amount: 150 },
          { id: 4, date: '2024-10-05', item: 'Product D', amount: 250 },
          { id: 5, date: '2024-10-10', item: 'Product E', amount: 300 },
        ],
      };
    },
    computed: {
      filteredSales() {
        if (!this.selectedDate) return this.salesData;
        const selectedDateStr = this.selectedDate.toISOString().split('T')[0];
        return this.salesData.filter((sale) => sale.date === selectedDateStr);
      },
    },
  };
  </script>
  
  <style scoped>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  </style>
  