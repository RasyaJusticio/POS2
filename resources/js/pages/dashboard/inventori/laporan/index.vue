<script setup lang="ts">
import { h, ref, onMounted, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Pembelian } from "@/types/laporan";
import axios from "@/libs/axios";
import { formatRupiah } from "@/libs/utilss";


const column = createColumnHelper<Pembelian>();
const paginateRef = ref<any>(null);
const transactions = ref<Pembelian[]>([]);
const selectedTransaction = ref<Pembelian | null>(null);

// Date filter refs
const startDate = ref<string>('');
const endDate = ref<string>('');
const isLoading = ref<boolean>(false);
const noDataMessage = ref<string>('');

// Watch for changes in date range
watch([startDate, endDate], async ([newStartDate, newEndDate]) => {
  if (newStartDate && newEndDate) {
    await filterByDateRange();
  }
});

const filterByDateRange = async () => {
  if (!startDate.value || !endDate.value) {
    noDataMessage.value = 'Silakan pilih rentang tanggal';
    return;
  }

  // Validate date range
  if (new Date(startDate.value) > new Date(endDate.value)) {
    noDataMessage.value = 'Tanggal awal tidak boleh lebih besar dari tanggal akhir';
    return;
  }

  isLoading.value = true;
  noDataMessage.value = '';

  try {
    const response = await axios.post('/inventori/laporan', {
      start_date: startDate.value,
      end_date: endDate.value
    });

    if (response.data && response.data.length > 0) {
      transactions.value = response.data;
      noDataMessage.value = '';
    } else {
      transactions.value = [];
      noDataMessage.value = 'Tidak ada transaksi pada rentang tanggal yang dipilih';
    }
  } catch (error) {
    console.error('Error fetching transactions:', error);
    noDataMessage.value = 'Terjadi kesalahan saat mengambil data';
    transactions.value = [];
  } finally {
    isLoading.value = false;
  }
};

// Reset filter function
const resetFilter = () => {
  startDate.value = '';
  endDate.value = '';
  transactions.value = [];
  noDataMessage.value = '';
  fetchInitialData(); // Fetch initial data after reset
};

const checkNameBeforeSubmit = () => {
    if (!selectedTransaction.value?.customer_name) {
        alert("Nama pembeli tidak boleh kosong. Silakan isi nama sebelum melanjutkan.");
        return false;
    }
    return true;
};

const { delete: deletePembelian } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

// Initial data fetch
const fetchInitialData = async () => {
    try {
        const response = await axios.post('/inventori/laporan');
        transactions.value = response.data;
    } catch (error) {
        console.error('Error fetching initial transactions:', error);
        noDataMessage.value = 'Terjadi kesalahan saat mengambil data awal';
    }
};

// Print transaction function
const printTransaction = async () => {
    try {
        let response;
        if (startDate.value && endDate.value) {
            response = await axios.post('/inventori/laporan', {
                start_date: startDate.value,
                end_date: endDate.value
            });
        } else {
            response = await axios.post('/inventori/laporan');
        }
        
        const transactions = response.data;

        const printContent = `
            <html>
            <head>
                <title>Laporan Transaksi</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    h1 {
                        text-align: center;
                        color: #0070C0;
                    }
                    .date-range {
                        text-align: center;
                        margin-bottom: 20px;
                        font-size: 14px;
                    }
                    table {
                        border-collapse: collapse;
                        width: 100%;
                        margin-top: 20px;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 10px;
                        text-align: center;
                        font-size: 14px;
                    }
                    th {
                        background-color: #0070C0;
                        color: white;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    tr:hover {
                        background-color: #ddd;
                    }
                    tfoot {
                        font-weight: bold;
                        background-color: #0070C0;
                        color: white;
                    }
                </style>
            </head>
            <body>
                <h1>Laporan Transaksi</h1>
                ${startDate.value && endDate.value ? `
                    <div class="date-range">
                        Periode: ${new Date(startDate.value).toLocaleDateString('id-ID')} - 
                        ${new Date(endDate.value).toLocaleDateString('id-ID')}
                    </div>
                ` : ''}
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pembelian</th>
                            <th>Nama Pembeli</th>
                            <th>Status Pembayaran</th>
                            <th>Total</th>
                            <th>Tanggal Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${transactions.map((transaction, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${transaction.pembelian_id}</td>
                                <td>${transaction.customer_name}</td>
                                <td>${transaction.status}</td>
                                <td>${formatRupiah(transaction.total_price)}</td>
                                <td>${new Date(transaction.created_at).toLocaleDateString('id-ID')}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total Transaksi:</td>
                            <td colspan="2">${transactions.length}</td>
                        </tr>
                    </tfoot>
                </table>
            </body>
            </html>
        `;

        const newWindow = window.open('', '_blank');
        if (newWindow) {
            newWindow.document.write(printContent);
            newWindow.document.close();
            newWindow.print();
            newWindow.close();
        } else {
            console.error("Gagal membuka jendela baru.");
        }

    } catch (error) {
        console.error("Error fetching transactions for printing:", error);
    }
};

// Export transaction function
const exportTransaction = async () => {
    try {
        const queryParams = startDate.value && endDate.value ? 
            `?start_date=${startDate.value}&end_date=${endDate.value}` : '';
            
        const response = await axios.get(`/inventori/laporan/export${queryParams}`, {
            responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'DATA TRANSAKSI SIAM.xlsx');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Error downloading the Excel file:", error);
    }
};

const markAsProcessed = async (transaction: Pembelian) => {
    transaction.created = true;
    try {
        await axios.put(`/inventori/laporan/${transaction.id}`, {
            created: transaction.created,
        });
        paginateRef.value.refetch();
    } catch (error) {
        console.error('Error updating transaction status:', error);
    }
};

const columns = [
    column.display({
        header: "No",
        cell: (cell) => {
            return cell.row.index + 1;
        },
    }),
    column.accessor("id", {
        header: "ID Pembelian",
        cell: (cell) => cell.getValue().toString().padStart(3, '0'),
    }),
    column.accessor("customer_name", {
        header: "Nama",
    }),
    column.accessor("items", {
        header: "Produk yang Dibeli",
        cell: (cell) => {
            const itemsList = cell.getValue().split("\n").map(item => `<div>${item}</div>`).join('');
            return h('div', { innerHTML: itemsList });
        }
    }),
    column.accessor("total_price", {
        header: "Total",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    column.accessor("status", {
        header: "Status Pembayaran",
    }),
    column.accessor("created_at", {
        header: "Tanggal Pesanan",
        cell: (cell) => {
            return new Date(cell.getValue()).toLocaleDateString("id-ID");
        },
    }),
    column.accessor("created", {
        header: "Pesanan Dibuat",
        cell: (cell) => cell.getValue() ? "On Process" : "Procces",
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => selectedTransaction.value = cell.row.original,
                    },
                    h("i", { class: "la la-eye fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deletePembelian(`/inventori/laporan/${cell.getValue()}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

// Fetch initial data when component is mounted
onMounted(fetchInitialData);
</script>

<template>
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
            <h2 class="mb-0">Laporan Transaksi</h2>

            <button
                type="button"
                class="btn btn-sm btn-secondary ms-auto"
                @click="printTransaction"
                :disabled="isLoading"
            >
                <i class="la la-print"></i>
                Print
            </button>

            <button
                type="button"
                class="btn btn-sm btn-secondary ms-2"
                @click="exportTransaction"
                :disabled="isLoading"
            >
                <i class="la la-file-excel"></i>
                Export Excel
            </button>
        </div>

        <div class="card-body">
            <!-- Date Range Filter -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold fs-6 required">
                        <i class="la la-calendar"></i> Tanggal Awal
                    </label>
                    <input
                        type="date"
                        v-model="startDate"
                        class="form-control form-control-lg form-control-solid"
                        :max="endDate || undefined"
                        :disabled="isLoading"
                    />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold fs-6 required">
                        <i class="la la-calendar"></i> Tanggal Akhir
                    </label>
                    <input
                        type="date"
                        v-model="endDate"
                        class="form-control form-control-lg form-control-solid"
                        :min="startDate || undefined"
                        :disabled="isLoading"
                    />
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button
                        class="btn btn-primary me-2"
                        @click="filterByDateRange"
                        :disabled="!startDate || !endDate || isLoading"
                    >
                        <i class="la la-search"></i>
                        {{ isLoading ? 'Mencari...' : 'Cari' }}
                    </button>
                    <button
                        class="btn btn-secondary"
                        @click="resetFilter"
                        :disabled="isLoading"
                    >
                        <i class="la la-refresh"></i>
                        Reset
                    </button>
                </div>
            </div>

            <!-- Loading and No Data Messages -->
            <div v-if="isLoading" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div v-else-if="noDataMessage" class="alert alert-info text-center">
                {{ noDataMessage }}
            </div>

            <!-- Data Table -->
            <div v-else>
                <paginate
                    ref="paginateRef"
                    id="table-transactions"
                    url="/inventori/laporan"
                    :columns="columns"
                    :data="transactions"
                ></paginate>
            </div>
        </div>
    </div>

    <!-- Detail Transaction Modal -->
    <div v-if="selectedTransaction" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Detail Transaksi</h5>
                <button class="modal-close" @click="selectedTransaction = null">
                    &times;
                </button>
            </div>

            <div class="modal-body">
                <p><strong>ID Pembelian:</strong> {{ selectedTransaction?.id }}</p>
                <p><strong>Nama:</strong> {{ selectedTransaction?.customer_name }}</p>
                <p><strong>Pesanan:</strong> {{ selectedTransaction?.items }}</p>
                <p><strong>Total Harga:</strong> {{ formatRupiah(selectedTransaction?.total_price) }}</p>
                <p><strong>Status Pembayaran:</strong> {{ selectedTransaction?.status }}</p>
                <p><strong>Tanggal Transaksi:</strong> {{ new Date(selectedTransaction?.created_at).toLocaleDateString("id-ID") }}</p>
                <p><strong>Status Pesanan Dibuat:</strong> {{ selectedTransaction?.created ? 'On Process' : 'Procces' }}</p>
            </div>

            <button class="btn btn-secondary" @click="selectedTransaction = null">
                Tutup Detail
            </button>
        </div>
    </div>
</template>

<style scoped>
  /* CARD STYLING */
  .card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 16px;
  }
  
  .card-body {
    padding: 16px;
  }
  
  /* FORM INPUT */
  .form-control {
    max-width: 300px;
  }
  
  /* MODAL STYLING */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .modal-close {
    cursor: pointer;
    font-size: 1.5rem;
    background: none;
    border: none;
  }
  
  .modal-body {
    margin-top: 20px;
  }
  
  /* TABLE STYLING */
  table {
    border-collapse: collapse;
    width: 100%;
    margin: 0;
    table-layout: auto; /* Ensure table adapts based on content */
  }
  
  th,
  td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
  }
  
  th {
    background-color: #0070C0;
    color: white;
  }
  
  tr:nth-child(even) {
    background-color: #f2f2f2;
  }
  
  tr:hover {
    background-color: #ddd;
  }
  
  tfoot {
    font-weight: bold;
    background-color: #0070C0;
    color: white;
  }
  
  /* BUTTON STYLING */
  .btn {
    transition: background-color 0.3s ease;
  }
  
  .btn:hover {
    background-color: #0062a0;
  }
  
  @media (max-width: 768px) {
    .card-body {
      padding: 8px;
    }
  
    .form-control {
      max-width: 100%;
    }
  }
  </style>

  
