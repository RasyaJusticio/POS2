<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./form.vue"; // Sesuaikan path ke komponen form
import { createColumnHelper } from "@tanstack/vue-table";
import type { Product } from "@/types/pos"; // Sesuaikan tipe Produk dengan model
import { formatRupiah } from "@/libs/utilss"; // Import formatRupiah helper

const column = createColumnHelper<Product>();
const paginateRef = ref<any>(null);
const selected = ref<number | undefined>(undefined);
const openForm = ref<boolean>(false);

const { delete: deleteProduct } = useDelete({
    onSuccess: () => paginateRef.value?.refetch(), // Refetch setelah delete
});

const columns = [
    column.accessor("name", {
        header: "Product Name",
    }),
    column.accessor("category", {
        header: "Category", // Kolom kategori setelah nama
    }),
    column.accessor("price", {
        header: "Price",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    column.accessor("quantity", {
        header: "Quantity",
    }),
    column.accessor("description", {
        header: "Description",
    }),
    column.accessor("image_url", {
    header: "Image",
    cell: (cell) =>
        h("img", {
            src: `/storage/${cell.getValue()}`,
            alt: "Product Image",
            width: 100,
        }),
}),
    column.accessor("id", {
        header: "Actions",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-info",
                        onClick: () => {
                            selected.value = cell.getValue(); // Set produk terpilih untuk edit
                            openForm.value = true; // Buka form untuk edit
                        },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deleteProduct(`/inventori/produk/${cell.getValue()}`), // Ganti URL menjadi API yang benar
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value?.refetch(); // Refetch table saat dibutuhkan

watch(openForm, (val) => {
    if (!val) {
        selected.value = undefined; // Reset selected saat form ditutup
    }
    window.scrollTo(0, 0); // Scroll ke atas saat buka/tutup form
});
</script>

<template>
    <Form
        :selected="selected"
        @close="openForm = false"
        v-if="openForm"
        @refresh="refresh"
    />

    <div class="card">
        <div class="card-header align-items-center">
            <h2 class="mb-0">Product List</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Add Product
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-produk"
                url="/inventori/produk"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
