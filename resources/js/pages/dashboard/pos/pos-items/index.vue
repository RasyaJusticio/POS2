<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./form.vue"; // Correct path to your form component
import { createColumnHelper } from "@tanstack/vue-table";
import type { Item } from "@/types/pos"; // Adjust the type as per your model


const column = createColumnHelper<Item>();
const paginateRef = ref<any>(null);
const selected = ref<number | undefined>(undefined);  // Replace null with undefined
const openForm = ref<boolean>(false);

const { delete: deleteItem } = useDelete({
    onSuccess: () => paginateRef.value?.refetch(), // Refetch after deletion
});

const columns = [
    column.accessor("name", {
        header: "Item Name",
    }),
    column.accessor("price", {
        header: "Price",
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
            h("img", { src: cell.getValue(), alt: "Item Image", width: 100 }),
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
                            selected.value = cell.getValue(); // Set selected item id
                            openForm.value = true; // Open form for edit
                        },
                    },
                    h("i", { class: "la la-pencil fs-2" })
                ),
                h(
                    "button",
                    {
                        class: "btn btn-sm btn-icon btn-danger",
                        onClick: () =>
                            deleteItem(`/master/items/${cell.getValue()}`), // Delete item
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value?.refetch(); // Refetch the table when needed

watch(openForm, (val) => {
    if (!val) {
        selected.value = undefined; // Reset selection when the form is closed
    }
    window.scrollTo(0, 0); // Scroll to top when opening/closing form
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
            <h2 class="mb-0">Item List</h2>
            <button
                type="button"
                class="btn btn-sm btn-primary ms-auto"
                v-if="!openForm"
                @click="openForm = true"
            >
                Add Item
                <i class="la la-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <paginate
                ref="paginateRef"
                id="table-item"
                url="pos-items"
                :columns="columns"
            ></paginate>
        </div>
    </div>
</template>
