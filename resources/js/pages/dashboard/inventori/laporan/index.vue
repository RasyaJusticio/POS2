<template>
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
            <h2 class="mb-0">Laporan Transaksi</h2>
            <div class="ms-auto d-flex gap-2">
                <button
                    type="button"
                    class="btn btn-sm btn-secondary d-flex align-items-center gap-2"
                    @click="printTransaction"
                    :disabled="!transactions.length"
                >
                    <i class="la la-print"></i>
                    Print
                </button>
                <button
                    type="button"
                    class="btn btn-sm btn-secondary d-flex align-items-center gap-2"
                    @click="exportTransaction"
                    :disabled="!transactions.length"
                >
                    <i class="la la-file-excel"></i>
                    Export Excel
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="col-md-4 mb-4">
                <label
                    class="form-label fw-bold fs-6 required"
                    for="date-picker"
                >
                    <i class="la la-calendar"></i> Pilih Tanggal
                </label>
                <VuePicDatePicker
                    id="date-picker"
                    v-model="selectedDate"
                    :format="dateFormat"
                    @update:model-value="filterHelper.filterByDate"
                    :min-date="minDate"
                    :max-date="maxDate"
                    class="form-control form-control-lg form-control-solid"
                />
            </div>

            <div v-if="isLoading" class="d-flex justify-content-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div v-else-if="error" class="alert alert-danger" role="alert">
                {{ error }}
            </div>

            <div
                v-else-if="!transactions.length && selectedDate"
                class="alert alert-info"
                role="alert"
            >
                Tidak ada transaksi pada tanggal yang dipilih
            </div>

            <paginate
                v-else
                ref="paginateRef"
                id="table-transactions"
                url="/inventori/laporan"
                :columns="columns"
                :data="transactions"
            />
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <TransitionRoot appear :show="!!selectedTransaction" as="template">
        <Dialog as="div" class="modal-overlay" @close="closeModal">
            <div class="modal-content">
                <DialogTitle as="div" class="modal-header">
                    <h5>Detail Transaksi</h5>
                    <button class="modal-close" @click="closeModal">
                        &times;
                    </button>
                </DialogTitle>

                <div class="modal-body" v-if="selectedTransaction">
                    <dl class="transaction-details">
                        <div class="detail-item">
                            <dt>ID Pembelian:</dt>
                            <dd>{{ formatId(selectedTransaction.id) }}</dd>
                        </div>
                        <div class="detail-item">
                            <dt>Nama:</dt>
                            <dd>{{ selectedTransaction.customer_name }}</dd>
                        </div>
                        <div class="detail-item">
                            <dt>Pesanan:</dt>
                            <dd>
                                <ul class="items-list">
                                    <li
                                        v-for="item in parseItems(
                                            selectedTransaction.items
                                        )"
                                        :key="item"
                                    >
                                        {{ item }}
                                    </li>
                                </ul>
                            </dd>
                        </div>
                        <div class="detail-item">
                            <dt>Total Harga:</dt>
                            <dd>
                                {{
                                    formatRupiah(
                                        selectedTransaction.total_price
                                    )
                                }}
                            </dd>
                        </div>
                        <div class="detail-item">
                            <dt>Status Pembayaran:</dt>
                            <dd>
                                <span
                                    :class="
                                        getStatusClass(
                                            selectedTransaction.status
                                        )
                                    "
                                >
                                    {{ selectedTransaction.status }}
                                </span>
                            </dd>
                        </div>
                        <div class="detail-item">
                            <dt>Tanggal Transaksi:</dt>
                            <dd>
                                {{ formatDate(selectedTransaction.created_at) }}
                            </dd>
                        </div>
                    </dl>

                    <button
                        @click="markAsProcessed(selectedTransaction)"
                        class="btn btn-primary mt-4 w-100"
                        :disabled="isProcessing"
                    >
                        {{
                            isProcessing
                                ? "Memproses..."
                                : "Tandai Sudah Diproses"
                        }}
                    </button>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { h, ref, onMounted } from "vue";
import { Dialog, DialogTitle, TransitionRoot } from "@headlessui/vue";
import { createColumnHelper } from "@tanstack/vue-table";
import VuePicDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import axios from "@/libs/axios";
import { formatRupiah } from "@/libs/utilss";
import { FilterHelper } from "../helpers/filterHelper";

interface Pembelian {
    id: number;
    customer_name: string;
    items: string;
    total_price: number;
    status: "Pending" | "Paid" | "Cancelled";
    created_at: string;
    created: boolean;
}

// State management
const transactions = ref<Pembelian[]>([]);
const selectedTransaction = ref<Pembelian | null>(null);
const selectedDate = ref<Date | null>(null);
const isLoading = ref(false);
const error = ref<string>("");
const isProcessing = ref(false);

// Constants
const dateFormat = "yyyy-MM-dd";
const minDate = new Date("2020-01-01");
const maxDate = new Date();

// Column helper
const column = createColumnHelper<Pembelian>();

// Filter Helper
const filterHelper = new FilterHelper("/inventori/laporan", transactions, isLoading, error);
filterHelper.fetchMethod = 'post';

// Utility functions
const formatId = (id: number) => id.toString().padStart(3, "0");
const formatDate = (date: string) => new Date(date).toLocaleDateString("id-ID");
const parseItems = (items: string) => items.split("\n").filter(Boolean);
const closeModal = () => (selectedTransaction.value = null);

const getStatusClass = (status: Pembelian["status"]) => ({
    "badge bg-warning": status === "Pending",
    "badge bg-success": status === "Paid",
    "badge bg-danger": status === "Cancelled",
});

// API calls
const markAsProcessed = async (transaction: Pembelian) => {
    isProcessing.value = true;
    try {
        await axios.put(`/inventori/laporan/${transaction.id}/process`);
        transaction.created = true;
        closeModal();
    } catch (err) {
        error.value = "Gagal memproses transaksi";
    } finally {
        isProcessing.value = false;
    }
};

const deletePembelian = async (url: string) => {
    if (!confirm("Apakah Anda yakin ingin menghapus transaksi ini?")) return;

    try {
        await axios.delete(url);
        await filterByDate(selectedDate.value);
    } catch (err) {
        error.value = "Gagal menghapus transaksi";
    }
};

// Table columns configuration
const columns = [
    column.display({
        id: "number",
        header: "No",
        cell: (info) => info.row.index + 1,
    }),
    column.accessor("id", {
        header: "ID Pembelian",
        cell: (info) => formatId(info.getValue()),
    }),
    column.accessor("customer_name", {
        header: "Nama",
    }),
    column.accessor("items", {
        header: "Produk yang Dibeli",
        cell: (info) => {
            const items = parseItems(info.getValue());
            return h(
                "div",
                { class: "items-container" },
                items.map((item) => h("div", { class: "item" }, item))
            );
        },
    }),
    column.accessor("total_price", {
        header: "Total",
        cell: (info) => formatRupiah(info.getValue()),
    }),
    column.accessor("status", {
        header: "Status Pembayaran",
        cell: (info) =>
            h(
                "span",
                {
                    class: getStatusClass(info.getValue()),
                },
                info.getValue()
            ),
    }),
    column.accessor("created_at", {
        header: "Tanggal Pesanan",
        cell: (info) => formatDate(info.getValue()),
    }),
    column.accessor("created", {
        header: "Status Proses",
        cell: (info) => (info.getValue() ? "Sudah Diproses" : "Belum Diproses"),
    }),
    column.accessor("id", {
        header: "Aksi",
        cell: (info) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () =>
                            (selectedTransaction.value = info.row.original),
                    },
                    h("i", { class: "la la-eye fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deletePembelian(
                                `/inventori/laporan/${info.getValue()}`
                            ),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

// Initialize component
onMounted(async () => {
    if (selectedDate.value) {
        await filterByDate(selectedDate.value);
    }
});
</script>

<style scoped>
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

.form-control {
    max-width: 300px;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: grid;
    place-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 24px;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.modal-close {
    cursor: pointer;
    font-size: 1.5rem;
    background: none;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.modal-close:hover {
    background-color: #f0f0f0;
}

.transaction-details {
    display: grid;
    gap: 16px;
    margin: 0;
}

.detail-item {
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 8px;
}

.detail-item dt {
    font-weight: 600;
    color: #666;
}

.items-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.items-list li {
    padding: 4px 0;
    border-bottom: 1px solid #eee;
}

.items-list li:last-child {
    border-bottom: none;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
}

.items-container {
    text-align: left;
}

.item {
    padding: 2px 0;
}

@media (max-width: 768px) {
    .card-body {
        padding: 12px;
    }

    .form-control {
        max-width: 100%;
    }

    .detail-item {
        grid-template-columns: 1fr;
    }
}
</style>
