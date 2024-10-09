<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./form.vue"; // Sesuaikan path ke komponen form
import { createColumnHelper } from "@tanstack/vue-table";
import type { Product } from "@/types/pos"; // Sesuaikan tipe Produk dengan model
import { formatRupiah } from "@/libs/utilss"; // Import formatRupiah helper
import axios from "@/libs/axios";

const column = createColumnHelper<Product>();
const paginateRef = ref<any>(null);
const selected = ref<number | undefined>(undefined);
const openForm = ref<boolean>(false);
const selectedCategory = ref<string>("");

const { delete: deleteProduct } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

const toggleSoldOut = async (productId: number) => {
    try {
        console.log('Data produk:', paginateRef.value.data.data); // Tambahkan ini untuk memeriksa struktur data
        const response = await axios.post(`/inventori/produk/${productId}/toggle-sold-out`);
        
        // Periksa apakah respons berhasil
        if (response.status === 200) {
            // Pastikan paginateRef.value.data adalah array
            if (Array.isArray(paginateRef.value.data.data)) {
                const product = paginateRef.value.data.data.find((p: Product) => p.id === productId);
                if (product) {
                    product.is_sold_out = response.data.product.is_sold_out; // Perbarui status dengan nilai dari respons
                }
            } else {
                console.error("paginateRef.value.data bukan array:", paginateRef.value.data);
            }
        } else {
            console.error("Gagal memperbarui status sold out:", response);
        }
    } catch (error) {
        console.error("Error toggling sold out status:", error);
    }
};




const categories = ref([
    { id: "1", name: "makanan" },
    { id: "2", name: "dessert" },
    { id: "3", name: "minuman" },
]);

const columns = [
    column.display({
        header: "No.",
        cell: (cell) => cell.row.index + 1, // Menampilkan nomor urut berdasarkan index row
    }),
    column.accessor("name", {
        header: "Product Name",
    }),
    column.accessor("category", {
        header: "Category",
    }),
    column.accessor("price", {
        header: "Price",
        cell: (cell) => formatRupiah(cell.getValue()),
    }),
    // column.accessor("quantity", {
    //     header: "Quantity",
    // }),
    column.accessor("description", {
        header: "Description",
    }),
    column.accessor("image_url", {
        header: "Image",
        cell: (cell) =>
            h("img", {
                src: `${cell.getValue()}`,
                alt: "Product Image",
                width: 100,
            }),
    }),

    column.accessor("id", {
    header: "Actions",
    cell: (cell) =>
        h("div", { class: "d-flex gap-2" }, [
            // Tombol untuk mengedit
            h(
                "button",
                {
                    class: "btn btn-sm btn-icon btn-info",
                    onClick: () => {
                        selected.value = cell.getValue();
                        openForm.value = true;
                    },
<<<<<<< HEAD
                    h("i", { class: "la la-pencil fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () => 
                            deleteProduct(`/inventori/produk/${cell.getValue()}`)
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
=======
                },
                h("i", { class: "la la-pencil fs-2" })
            ),
            // Tombol untuk menghapus
            h(
                "button",
                {
                    class: "btn btn-sm btn-icon btn-danger",
                    onClick: () =>
                        deleteProduct(`/inventori/produk/${cell.getValue()}`),
                },
                h("i", { class: "la la-trash fs-2" })
            ),
            // Tombol untuk toggle sold out
            h(
                "button",
                {
                    class: [ 
                        "btn btn-sm btn-icon",
                        cell.row.original.is_sold_out ? "btn-danger" : "btn-success",
                    ],
                    onClick: () => toggleSoldOut(cell.getValue()),
                },
                h("span", cell.row.original.is_sold_out ? "Sold Out" : "Avail able")
            ),
        ]),
>>>>>>> 9f291ebc4a67f74d29402bfca5b99f211a38090e
    }),

];

// Fungsi untuk refresh tabel
const refresh = () => paginateRef.value?.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = undefined; // Reset selected saat form ditutup
    }
    window.scrollTo(0, 0); // Scroll ke atas saat buka/tutup form
});

watch(selectedCategory, (newCategory) => {
    if (paginateRef.value) {
        const params = { per: 10 }; // Set jumlah item per halaman
        if (newCategory) {
            params.category = newCategory; // Mengirim kategori sebagai parameter
        } else {
            delete params.category; // Menghapus parameter kategori jika tidak ada
        }
        paginateRef.value.setParams(params); // Set parameter untuk request
        paginateRef.value.refetch(); // Meminta ulang data
    }
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

            <!-- Dropdown Kategori -->

            <!-- Tombol Tambah Produk -->
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
            <!-- Komponen Pagination -->
            <paginate
                ref="paginateRef"
                id="table-produk"
                url="/inventori/produk"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>

<style scoped>

.soldOut {
    filter: grayscale(100%);
    opacity: 0.6;
}

</style>
