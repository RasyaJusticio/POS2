<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { ref, onMounted, computed } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { useDelete } from "@/libs/hooks";
import { createColumnHelper } from "@tanstack/vue-table";
import type { TransactionReport } from "@/types/laporan";
import Flatpickr from "vue-flatpickr-component";

const transaction = ref<TransactionReport>({} as TransactionReport);
const transactions = ref<TransactionReport[]>([]);
const paginateRef = ref<any>(null);
const column = createColumnHelper<TransactionReport>();

const formRef = ref();

const formSchema = Yup.object().shape({
    pembelian_id: Yup.string().required("ID Pembelian harus diisi"),
    status: Yup.string().required("Status Pembayaran harus diisi"),
    created_at: Yup.string().required("Tanggal harus diisi"),
    items: Yup.array().min(1, "Item harus diisi"),
});

// Mendapatkan data laporan
onMounted(async () => {
    try {
        block(document.getElementById("form-report"));
        const response = await axios.post("/inventori/laporan");
        transactions.value = response.data;
    } catch (error) {
        toast.error("Gagal mendapatkan data");
    } finally {
        unblock(document.getElementById("form-report"));
    }
});

const submit = () => {
    alert("Form submitted!");
    // Logic submit form here
    block(document.getElementById("form-report"));
    axios({
        method: "post",
        url: "/inventori/laporan",
        data: transaction.value,
        headers: { "Content-Type": "application/json" },
    })
        .then(() => {
            paginateRef.value.refetch();
            toast.success("Laporan berhasil disimpan");
            formRef.value.resetForm();
        })
        .catch((err) => {
            toast.error("Terjadi kesalahan");
        })
        .finally(() => {
            unblock(document.getElementById("form-report"));
        });
};

const columns = [
    column.accessor("id", { header: "No" }),
    column.accessor("pembelian_id", { header: "ID Pembelian" }),
    column.accessor("status", { header: "Status Pembayaran" }),
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
            return new Date(cell.getValue()).toLocaleDateString("id-ID");
        },
    }),
];

const { delete: deleteTransaction } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});
</script>

<template>
    <VForm
        class="form card mb-10"
        @submit="submit"
        :validation-schema="formSchema"
        id="form-report"
        ref="formRef"
    >
        <div class="card-header">
            <h2 class="mb-0">Form Laporan Transaksi</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label">ID Pembelian</label>
                        <Field
                            class="form-control"
                            type="text"
                            name="pembelian_id"
                            v-model="transaction.pembelian_id"
                            placeholder="Masukkan ID Pembelian"
                        />
                        <div>
                            <ErrorMessage name="pembelian_id" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label">Status Pembayaran</label>
                        <Field
                            class="form-control"
                            type="text"
                            name="status"
                            v-model="transaction.status"
                            placeholder="Masukkan Status Pembayaran"
                        />
                        <div>
                            <ErrorMessage name="status" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label">Tanggal Pesanan</label>
                        <Field
                            class="form-control"
                            type="date"
                            name="created_at"
                            v-model="transaction.created_at"
                        />
                        <div>
                            <ErrorMessage name="created_at" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label">Detail Item</label>
                        <Field
                            class="form-control"
                            type="text"
                            name="items"
                            v-model="transaction.items"
                            placeholder="Masukkan Item"
                        />
                        <div>
                            <ErrorMessage name="items" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </VForm>
</template>
