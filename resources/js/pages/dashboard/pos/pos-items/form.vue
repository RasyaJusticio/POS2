<script setup lang="ts">
import { block, unblock } from "@/libs/utils";
import { onMounted, ref, watch } from "vue";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import type { Produk } from "@/types/pos";  // Sesuaikan tipe Produk

const props = defineProps({
    selected: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(["close", "refresh"]);

const formData = ref<Product>({} as Product);
const imageFile = ref<File | null>(null);
const formRef = ref();

const formSchema = Yup.object().shape({
    name: Yup.string().required("Product Name is required"),
    price: Yup.number().required("Price is required").positive("Price must be positive"),
    quantity: Yup.number().required("Quantity is required").integer("Quantity must be an integer").min(0, "Quantity must be at least 0"),
    description: Yup.string(),
    category: Yup.string().required("Category is required"), // Validasi kategori
});

function getEdit() {
    block(document.getElementById("form-produk"));
    axios.get(`/api/pos/pos-produk/${props.selected}`)  // Ganti URL dengan API yang benar
        .then(({ data }) => {
            formData.value = data.produk;
        })
        .catch((err: any) => {
            toast.error(err.response.data.message);
        })
        .finally(() => {
            unblock(document.getElementById("form-produk"));
        });
}

function onImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        imageFile.value = target.files[0];
    }
}

function submit() {
    block(document.getElementById("form-produk"));
    const formDataToSubmit = new FormData();
    formDataToSubmit.append("name", formData.value.name);
    formDataToSubmit.append("price", formData.value.price.toString());
    formDataToSubmit.append("quantity", formData.value.quantity.toString());
    formDataToSubmit.append("description", formData.value.description);
    formDataToSubmit.append("category", formData.value.category); // Menambahkan kategori
    if (imageFile.value) {
        formDataToSubmit.append("image", imageFile.value);
    }

    axios({
        method: props.selected ? "put" : "post",
        url: props.selected
            ? `/api/pos/pos-produk/${props.selected}`  // Ganti URL untuk update produk
            : "/api/pos/pos-produk",  // Ganti URL untuk tambah produk
        data: formDataToSubmit,
    })
        .then(() => {
            emit('refresh');
            emit('close');
            toast.success("Product saved successfully");
        })
        .catch((err: any) => {
            const errors = err.response?.data?.errors;
            if (errors) {
                formRef.value.setErrors(errors);  // Panggil setErrors hanya jika ada error
            }
            toast.error(err.response?.data?.message || "An error occurred");
        })
        .finally(() => {
            unblock(document.getElementById("form-produk"));
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
    <VForm class="form card mb-10" @submit="submit" :validation-schema="formSchema" id="form-produk" ref="formRef">
        <div class="card-header align-items-center">
            <h2 class="mb-0">{{ selected ? "Edit" : "Add" }} Product</h2>
            <button type="button" class="btn btn-sm btn-light-danger ms-auto" @click="emit('close')">
                Cancel
                <i class="la la-times-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="produk-name">
                            Product Name
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="name"
                            autocomplete="off" v-model="formData.name" id="produk-name" placeholder="Enter Product Name" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="name" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="produk-category">
                            Category
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="text" name="category"
                            autocomplete="off" v-model="formData.category" id="produk-category" placeholder="Enter Category" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="category" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="produk-price">
                            Price
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="number" name="price"
                            autocomplete="off" v-model="formData.price" id="produk-price" placeholder="Enter Price" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="price" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Kolom lainnya tetap -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6 required" for="produk-quantity">
                            Quantity
                        </label>
                        <Field class="form-control form-control-lg form-control-solid" type="number" name="quantity"
                            autocomplete="off" v-model="formData.quantity" id="produk-quantity" placeholder="Enter Quantity" />
                        <div class="fv-plugins-message-container">
                            <div class="fv-help-block">
                                <ErrorMessage name="quantity" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Input Image -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bold fs-6" for="produk-image">
                            Image
                        </label>
                        <input class="form-control form-control-lg form-control-solid" type="file" @change="onImageChange"
                            id="produk-image" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary me-3">
                Save Changes
            </button>
        </div>
    </VForm>
</template>
