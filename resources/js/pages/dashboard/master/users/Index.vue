<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks"; // Ensure this path is correct
import Form from "./Form.vue";
import { createColumnHelper } from "@tanstack/vue-table";
import type { User } from "@/types"; // Ensure this path is correct
import axios from "axios";

const column = createColumnHelper<User>();
const paginateRef = ref<any>(null);
const selected = ref<string>("");
const openForm = ref<boolean>(false);

const printUser = async () => {
    try {
        const response = await axios.get('/master/users/print', {
            params: {
                search: '' // Pass any search term if needed
            }
        });

        const userData = response.data.data.map((user, index) => ({
            No: index + 1,
            Nama: user.name,
            Alamat: user.address,
            Email: user.email
        }));

        // Format the data for printing
        const printContent = `
            <h1>Daftar Pengguna</h1>
            <table border="1" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    ${userData.map(user => `
                        <tr>
                            <td>${user.No}</td>
                            <td>${user.Nama}</td>
                            <td>${user.Alamat}</td>
                            <td>${user.Email}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        const newWindow = window.open('', '_blank');
        if (newWindow) {
            newWindow.document.write(printContent);
            newWindow.document.close();
            newWindow.print();
            newWindow.close();
        } else {
            console.error("Failed to open a new window.");
        }

    } catch (error) {
        console.error("Error fetching users for printing:", error);
    }
};

const { delete: deleteUser } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
});

// Kolom data untuk tabel
const columns = [
    column.accessor("no", {
        header: "No",
    }),
    column.accessor("name", {
        header: "Nama",
    }),
    column.accessor("address", {
        header: "Alamat",
    }),
    column.accessor("email", {
        header: "Email",
    }),
    column.accessor("uuid", {
        header: "Aksi",
        cell: (cell) =>
            h("div", { class: "d-flex gap-2" }, [
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
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deleteUser(`/master/users/${cell.getValue()}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

// Fungsi untuk merefresh data setelah ada perubahan
const refresh = () => paginateRef.value.refetch();

// Fungsi untuk export data ke file Excel
const exportUsers = async () => {
    try {
        const response = await axios({
            url: '/master/users/export',
            method: 'GET',
            responseType: 'blob', // Penting untuk unduhan file
        });

        // Buat URL untuk respons blob
        const url = window.URL.createObjectURL(new Blob([response.data]));

        // Buat elemen link dan klik untuk memicu unduhan
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'users.xlsx'); // Nama file
        document.body.appendChild(link);
        link.click();

        // Bersihkan
        if (link.parentNode) {
            link.parentNode.removeChild(link);
        }
    } catch (error) {
        console.error("Error downloading the Excel file:", error);
    }
};

watch(openForm, (val) => {
    if (!val) {
        selected.value = "";
    }
    window.scrollTo(0, 0);
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
            <h2 class="mb-0">List Users</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Tambah
                <i class="la la-plus"></i>
            </button>

            <!-- Tombol untuk mencetak daftar pengguna -->
            <button
                type="button"
                class="btn btn-sm btn-success ms-2"
                v-if="!openForm"
                @click="printUser"
            >
                Cetak
                <i class="la la-print"></i>
            </button>

            <!-- Tombol untuk export data pengguna ke Excel -->
            <button
            type="button"
            class="btn btn-sm btn-success ms-2"
            v-if="!openForm"
            @click="exportUsers"
        >
            Export Excel
            <i class="la la-file-excel"></i>
        </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-users"
                url="/master/users"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
