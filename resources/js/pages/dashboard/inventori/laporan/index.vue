<script setup lang="ts">
import { h, ref, onMounted } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import type { Pembelian } from "@/types/laporan"; // Ganti dengan path yang sesuai
import axios from "@/libs/axios";
import { formatRupiah } from "@/libs/utilss";

const column = createColumnHelper<Pembelian>();
const paginateRef = ref<any>(null);
const transactions = ref<Pembelian[]>([]); // Menyimpan data transaksi
const selectedTransaction = ref<Pembelian | null>(null);

const { delete: deletePembelian } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
})

// Mendapatkan data transaksi saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.post('/inventori/laporan');
        transactions.value = response.data; 
    } catch (error) {
        console.error('Error fetching transactions:', error);
    }
});

// Fungsi untuk mencetak laporan transaksi
const printTransaction = async () => {
    try {
        const response = await axios.post('/inventori/laporan');
        const transactions = response.data.data;

        // Memformat data transaksi
        const printContent = `
            <html>
            <head>
                <title>Laporan Transaksi</title>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    th, td {
                        border: 1px solid black;
                        padding: 8px;
                        text-align: center;
                    }
                    th {
                        background-color: #0070C0;
                        color: white;
                    }
                </style>
            </head>
            <body>
                <h1 style="text-align: center;">Laporan Transaksi</h1>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pembelian</th>
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
                                <td>${transaction.status}</td>
                                <td>${formatRupiah(transaction.total_price)}</td>
                                <td>${new Date(transaction.created_at).toLocaleDateString("id-ID")}</td>
                            </tr>
                        `).join('')}
                    </tbody>
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

// Fungsi untuk mengekspor laporan transaksi ke Excel
const exportTransaction = async () => {
    try {
        const response = await axios.get('/inventori/laporan/export', {
            responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'laporan_transaksi.xlsx');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Error downloading the Excel file:", error);
    }
};


// Mendapatkan data transaksi saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.post('/inventori/laporan');
        transactions.value = response.data;
        paginateRef.value.refetch(); 
    } catch (error) {
        console.error('Error fetching transactions:', error);
    }
});

const markAsProcessed = async (transaction: Pembelian) => {
    // Simpan status pesanan ke backend
    transaction.created = true; // Mark as processed
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
            return cell.row.index + 1; // Menggunakan indeks baris sebagai nomor urut
        },
    }),
    column.accessor("id", {
        header: "ID Pembelian",
        cell: (cell) => cell.getValue().toString().padStart(3, '0'), // Memastikan minimal 3 digit dengan padding '0'
    }),
    column.accessor("items", {
        header: "Produk yang Dibeli",
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
                // h(
                //     "button",
                //     {
                //         class: "btn btn-sm btn-icon btn-success",
                //         disabled: cell.row.original.created, // Disable if already processed
                //         onClick: () => markAsProcessed(cell.row.original),
                //     },
                //     h("i", { class: "fa fa-check fs-2" }) // Font Awesome check icon
                    
                // ),
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


const refresh = () => paginateRef.value.refetch();
</script>


<template>
    <div class="card">
      <div class="card-header align-items-center">
        <h2 class="mb-0">Laporan Transaksi</h2>
  
        <!-- Button for printing the reservations list -->
        <button
          type="button"
          class="btn btn-sm btn-success ms-auto"
          @click="printTransaction"
        >
          Print
          <i class="la la-print"></i>
        </button>
  
        <!-- Button for exporting the reservations list to Excel -->
        <button
          type="button"
          class="btn btn-sm btn-success ms-2"
          @click="exportTransaction"
        >
          Export Excel
          <i class="la la-file-excel"></i>
        </button>
      </div>
  
      <div class="card-body">
        <paginate
          ref="paginateRef"
          id="table-transactions"
          url="/inventori/laporan"
          :columns="columns"
          :data="transactions"
        ></paginate>
      </div>
    </div>
  
    <!-- Detail Transaksi Modal -->
    <div v-if="selectedTransaction" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Detail Transaksi</h5>
          <button class="modal-close" @click="selectedTransaction = null">
            &times;
          </button>
        </div>
  
        <div class="modal-body">
          <p><strong>ID Pembelian:</strong> {{ selectedTransaction?.pembelian_id }}</p>
          <p><strong>Status Pembayaran:</strong> {{ selectedTransaction?.status }}</p>
          <p><strong>Total Harga:</strong> {{ formatRupiah(selectedTransaction?.total_price) }}</p>
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

.card-body {
    padding: 0; /* Mengurangi padding di dalam card-body */
}

table {
    border-collapse: collapse;
    width: 100%;
    margin: 0; /* Menghapus margin */
}

th, td {
    border: 1px solid black;
    padding: 8px; /* Atur padding sesuai kebutuhan */
    margin: 0; /* Pastikan tidak ada margin */
}
</style>

  
