<script setup lang="ts">
import { h, ref, onMounted } from "vue";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import type { TransactionReport } from "@/types/laporan"; // Ganti dengan path yang sesuai
import axios from "@/libs/axios";

const column = createColumnHelper<TransactionReport>();
const paginateRef = ref<any>(null);
const transactions = ref<TransactionReport[]>([]); // Menyimpan data transaksi

const { delete: deleteTransaction } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

// Mendapatkan data transaksi saat komponen dimuat
onMounted(async () => {
    try {
        const response = await axios.post('/inventori/laporan');
        transactions.value = response.data; // Simpan data transaksi ke dalam ref
        paginateRef.value.refetch(); // Memanggil refetch jika perlu
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
    column.accessor("items", {
        header: "Detail Item",
        cell: (cell) => {
            const items = JSON.parse(cell.getValue());
            return h("div", {}, items.map((item: any) => `Produk ID: ${item}`).join(", "));
        },
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
                        onClick: () => {
                            // Tampilkan detail pesanan
                            alert(`Detail Pesanan:\nID Pembelian: ${cell.row.getValue("pembelian_id")}\nStatus: ${cell.row.getValue("status")}\nItems: ${cell.row.getValue("items")}`);
                        },
                    },
                    h("i", { class: "la la-eye fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deleteTransaction(`/inventori/laporan/${cell.getValue()}`),
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
</template>
