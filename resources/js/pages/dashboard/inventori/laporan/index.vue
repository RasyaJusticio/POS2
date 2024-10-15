<script setup lang="ts">
import { h, ref, onMounted } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import type { TransactionReport } from "@/types/laporan"; // Ganti dengan path yang sesuai
import axios from "@/libs/axios";
import { formatRupiah } from "@/libs/utilss";

const column = createColumnHelper<TransactionReport>();
const paginateRef = ref<any>(null);
const transactions = ref<TransactionReport[]>([]); // Menyimpan data transaksi
const selectedTransaction = ref<TransactionReport | null>(null);


const { delete: deleteTransactionReport } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

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
    column.accessor("id", {
        header: "No",
    }),
    column.accessor("pembelian_id", {
        header: "ID Pembelian",
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
                            deleteTransactionReport(`/inventori/laporan/${cell.getValue()}`),
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

