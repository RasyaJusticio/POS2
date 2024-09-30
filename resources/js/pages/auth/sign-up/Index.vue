<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px w-100">
        <!--begin::Form-->
        <main class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework">
            <!--begin::Heading-->
            <div class="mb-0 text-center" style="margin-top: 90px;"> <!-- Adjust the value as needed -->
    <router-link to="/">
        <img 
            src="/LOGO.png" 
            alt="Logo AON Cashier" 
            class="w-150px mb-0"
            style="max-width: 100%; height: auto;"
        />
    </router-link>
    <h1 style="font-size: 30px; line-height: 1; margin-top: 0; margin-bottom: 0;">
        Register 
    </h1>
    <div class="text-primary" style="font-size: 20px;"><strong>AON Cashier</strong></div>
</div>

            <!--end::Heading-->
  
        <form class="mx-auto mw-600px w-100 pt-15 pb-10" novalidate @submit="handleSubmit">
          <!--begin::Form Fields-->
          <div class="mb-4">
            <label for="name" class="form-label">Name</label>
            <input v-model="formData.name" type="text" class="form-control" id="name" required />
          </div>
          
          <div class="mb-4">
            <label for="name" class="form-label">Address</label>
            <input v-model="formData.address" type="text" class="form-control" id="address" required />
          </div>
  
          <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input v-model="formData.email" type="email" class="form-control" id="email" required />
          </div>
  
          <div class="mb-4">
            <label for="phone" class="form-label">No. Telepon</label>
            <input v-model="formData.phone" type="tel" class="form-control" id="phone" required />
          </div>
  
          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input v-model="formData.password" type="password" class="form-control" id="password" required />
          </div>
  
          <div class="mb-4">
            <label for="confirm-password" class="form-label">Konfirmasi Password</label>
            <input v-model="formData.password_confirmation" type="password" class="form-control" id="confirm-password" required />
          </div>
          <!--end::Form Fields-->
  
          <!--begin::Actions-->
          <div class="d-flex flex-stack pt-15">
            <div class="mr-2">
              <button type="button" class="btn btn-lg btn-light-primary me-3" @click="goToLogin">
                Kembali
              </button>
            </div>
  
            <div>
              <button type="submit" id="submit-form" class="btn btn-lg btn-primary">
                Daftar
              </button>
            </div>
          </div>
          <!--end::Actions-->
        </form>
  
        <div class="border-bottom border-gray-300 w-100 mt-5 mb-10"></div>
  
        <div class="text-gray-400 fw-semobold fs-4 text-center">
          Sudah memiliki akun?
          <router-link to="/sign-in" class="link-primary fw-bold">Masuk</router-link>
        </div>
      </main>
      <!--end::Form-->
    </div>
    <!--end::Wrapper-->
  </template>
  
  <script lang="ts">
  import { defineComponent, ref } from "vue";
  import axios from "@/libs/axios";
  import { toast } from "vue3-toastify";
  import router from "@/router";
  
  export default defineComponent({
    name: "SignUp",
    setup() {
      const formData = ref({
        name: "",
        address: "",
        email: "",
        phone: "",
        password: "",
        password_confirmation: "",
      });
  
      const handleSubmit = async (event: Event) => {
        event.preventDefault();
        try {
          const response = await axios.post("/auth/register", formData.value);
          toast.success("Akun berhasil dibuat");
          router.push({ name: "sign-in" });
        } catch (error) {
          toast.error(error.response.data.message);
        }
      };
  
      const goToLogin = () => {
        router.push({ name: "sign-in" });
      };
  
      return {
        formData,
        handleSubmit,
        goToLogin,
      };
    },
  });
  </script>
  
  <style scoped>
  /* Tambahkan gaya jika perlu */
  </style>