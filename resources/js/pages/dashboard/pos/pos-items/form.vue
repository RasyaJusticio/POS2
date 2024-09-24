<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Item } from "@/types/pos";  // Sesuaikan tipe Item

const props = defineProps({
    selected: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const formData = ref<Item>({} as Item);
const imageFile = ref<File | null>(null);
const formRef = ref();

const formSchema = Yup.object().shape({
    name: Yup.string().required("Item Name is required"),
    price: Yup.number().required("Price is required").positive("Price must be positive"),
    quantity: Yup.number().required("Quantity is required").integer("Quantity must be an integer").min(0, "Quantity must be at least 0"),
    description: Yup.string(),
});

function getEdit() {
    block(document.getElementById("form-item"));
    axios.get(`/api/master/items/${props.selected}`)  // Ganti URL dengan API yang benar
        .then(({ data }) => {
            formData.value = data.item;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-item"));
        });
}

function onImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        imageFile.value = target.files[0];
    }
}

function submit() {
    block(document.getElementById("form-item"));
    const formDataToSubmit = new FormData();
    formDataToSubmit.append("name", formData.value.name);
    formDataToSubmit.append("price", formData.value.price.toString());
    formDataToSubmit.append("quantity", formData.value.quantity.toString());
    formDataToSubmit.append("description", formData.value.description);
    if (imageFile.value) {
        formDataToSubmit.append("image", imageFile.value);
    }

    axios({
        method: props.selected ? "put" : "post",
        url: props.selected
            ? `/api/pos/pos-items/${props.selected}`  // Ganti URL untuk update item
            : "/api/pos/pos-items",  // Ganti URL untuk tambah item
        data: formDataToSubmit,
    })
        .then(() => {
            emit('refresh');
            emit('close');
            toast.success("Item saved successfully");
        })
        .catch((err: any) => {
            const errors = err.response?.data?.errors;
            if (errors) {
                formRef.value.setErrors(errors);  // Panggil setErrors hanya jika ada error
            }
            toast.error(err.response?.data?.message || "An error occurred");
        })
        .finally(() => {
            unblock(document.getElementById("form-item"));
        });
}

onMounted(() => {
    if (props.selected) {
        getEdit();
    }
});

watch(
    () => props.selected,
    () => {
        if (props.selected) {
            getEdit();
        }
    }
);
</script>

<template>
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-item" ref="formRef">
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Add" }} Item</h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Cancel
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="item-name">
                            Item Name
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="name"
                            autocomplete="off" v-model="formData.name" id="item-name" placeholder="Enter Item Name" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="item-price">
                            Price
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="number" name="price"
                            autocomplete="off" v-model="formData.price" id="item-price" placeholder="Enter Price" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="price" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="item-quantity">
                            Quantity
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="number" name="quantity"
                            autocomplete="off" v-model="formData.quantity" id="item-quantity" placeholder="Enter Quantity" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="quantity" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6" for="item-description">Description</label>
                        <Field class="form-control form-control-lg form-control-solid" as="textarea" name="description"
                            autocomplete="off" v-model="formData.description" id="item-description" placeholder="Enter Description" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6" for="item-image">Image</label>
                        <input type="file" id="item-image" class="form-control form-control-lg form-control-solid" @change="onImageChange" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </VForm>
</template>
