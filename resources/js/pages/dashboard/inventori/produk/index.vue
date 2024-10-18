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

// Fungsi Print
const printProducts = () => {
    // Get the table data from your existing component
    const productsTable = document.getElementById('table-produk');
    if (!productsTable) {
        console.error('Table element not found');
        return;
    }

    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    if (!printWindow) {
        console.error('Failed to open print window');
        return;
    }

    // Build the printable HTML structure with modern styles
    const printContent = `
        <html>
            <head>
                <title>Print Products</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        background-color: #f4f4f4;
                        color: #333;
                    }
                    h2 {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        background-color: #fff;
                    }
                    table, th, td {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        padding: 12px;
                        text-align: left;
                    }
                    th {
                        background-color: #007BFF; /* Bootstrap primary color */
                        color: white;
                        font-weight: bold;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                    tr:hover {
                        background-color: #e9ecef;
                    }
                    img {
                        max-width: 50px; /* Adjust as needed */
                        height: auto;
                    }
                </style>
            </head>
            <body>
                <h2>Product List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Array.from(productsTable.querySelectorAll('tbody tr')).map((row, index) => {
                            const cells = row.querySelectorAll('td');
                            return `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${cells[1]?.innerText || ''}</td>
                                    <td>${cells[2]?.innerText || ''}</td>
                                    <td>${cells[3]?.innerText || ''}</td>
                                    <td>${cells[4]?.innerText || ''}</td>
                                    <td>${cells[5]?.innerHTML || ''}</td> <!-- Assuming image is in the 6th cell -->
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </body>
        </html>
    `;

    // Write content to the new window
    printWindow.document.write(printContent);

    // Close the document to ensure it's fully loaded before printing
    printWindow.document.close();

    // Wait for the content to load before printing
    printWindow.onload = () => {
        printWindow.print();
        printWindow.close();
    };
};



// Fungsi Export Excel
const exportExcel = async () => {
    try {
        const response = await axios.get("/inventori/produk/export-excel", {
            responseType: "blob", // Mendapatkan file Excel sebagai binary blob
        });
        
        // Buat link download
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", "produk.xlsx"); // Nama file yang akan di-download
        document.body.appendChild(link);
        link.click();
    } catch (error) {
        console.error("Error exporting Excel file:", error);
    }
};

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
    }),

];

// Fungsi untuk refresh tabel
const refresh = () => paginateRef.value?.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = ""; // Reset selected saat form ditutup
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

             <!-- Tombol Print Produk -->
             <button
                type="button"
                class="btn btn-sm btn-secondary ms-2"
                v-if="!openForm"
                @click="printProducts"
            >
                Print
                <i class="la la-print"></i>
            </button>

            <!-- Tombol Export Excel -->
            <button
                type="button"
                class="btn btn-sm btn-success ms-2"
                v-if="!openForm"
                @click="exportExcel"
            >
                Export Excel
                <i class="la la-file-excel"></i>
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


