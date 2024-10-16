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
        const transactions = response.data.data; // Pastikan mengambil data dari respons yang benar

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

        // Debug log untuk melihat isi printContent
        console.log(printContent);

        // Membuka jendela baru untuk pencetakan
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
            responseType: 'blob', // Untuk mendownload blob
        });

        // Membuat URL untuk blob
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'laporan_transaksi.xlsx'); // Nama file
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // Menghapus link setelah klik
    } catch (error) {
        console.error("Error downloading the Excel file:", error);
    }
};


// Mendapatkan data transaksi saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.post('/inventori/laporan');
        console.log("Response data:", response.data);
        transactions.value = response.data; 
        paginateRef.value.refetch(); 
    } catch (error) {
        console.error('Error fetching transactions:', error);
    }
});


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
    column.accessor("status", {
        header: "Status Pembayaran",
    }),
    column.accessor("total_price", {
        header: "Total",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    column.accessor("created_at", {
        header: "Tanggal Pesanan",
        cell: (cell) => {
            return new Date(cell.getValue()).toLocaleDateString("id-ID"); // Format tanggal sesuai locale
        },
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

    <!-- Detail Transaksi -->
    <div v-if="selectedTransaction" class="card mt-4">
        <div class="card-header">
            <h5>Detail Transaksi</h5>
        </div>
        <div class="card-body">
            <p><strong>ID Pembelian:</strong> {{ selectedTransaction?.pembelian_id }}</p>
            <p><strong>Status Pembayaran:</strong> {{ selectedTransaction?.status }}</p>
            <p><strong>Total Harga:</strong> {{ formatRupiah(selectedTransaction?.total_price) }}</p>
            <p><strong>Tanggal Transaksi:</strong> {{ new Date(selectedTransaction?.created_at).toLocaleDateString("id-ID") }}</p>
            <button class="btn btn-secondary" @click="selectedTransaction = null">Tutup Detail</button>
        </div>
    </div>
</template>

