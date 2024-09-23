<script setup lang="ts">
import { h, ref, watch } from "vue";
import { useDelete } from "@/libs/hooks";
import Form from "./form.vue"; // Rename your form component
import { createColumnHelper } from "@tanstack/vue-table";
import type { Item } from "@/types"; // Adjust type accordingly
import { link } f

const column = createColumnHelper<Item>();
const paginateRef = ref<any>(null);
const selected = ref<number | null>(null); // Allow null if no item is selected
const openForm = ref<boolean>(false);

const { delete: deleteItem } = useDelete({
    onSuccess: () => paginateRef.value.refetch(),
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
        cell: (cell) => h("img", { src: cell.getValue(), alt: "Item Image", width: 100 }),
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
                            deleteItem(`/master/items/${cell.getValue()}`),
                    },
                    h("i", { class: "la la-trash fs-2" })
                ),
            ]),
    }),
];

const refresh = () => paginateRef.value.refetch();

watch(openForm, (val) => {
    if (!val) {
        selected.value = null;
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
