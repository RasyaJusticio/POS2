<template>
  <div class="w-lg-500px w-100">
    <main class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework">
      <div class="mb-0 text-center" style="margin-top: 90px;">
        <router-link to="/">
          <img
            src="@/assets/images/siam2.png"
            alt="Logo AON Cashier"
            class="w-600px mb-0"
            style="max-width: 100%; height: auto;"
          />
        </router-link>
        <h1 style="font-size: 30px; line-height: 1; margin-top: 0; margin-bottom: 0;"></h1>
        <div class="text-primary" style="font-size: 20px;">
          <strong>Reset Password</strong>
        </div>
      </div>

      <form class="mx-auto mw-600px w-100 pt-15 pb-10" novalidate @submit="handleSubmit">
        <div class="mb-4">
          <label for="email" class="form-label">Email</label>
          <input v-model="formData.email" type="email" class="form-control" id="email" required />
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">New Password</label>
          <input v-model="formData.password" type="password" class="form-control" id="password" required />
        </div>

        <div class="mb-4">
          <label for="confirm-password" class="form-label">Confirm New Password</label>
          <input v-model="formData.password_confirmation" type="password" class="form-control" id="confirm-password" required />
        </div>

        <div class="d-flex flex-stack pt-15">
          <div class="mr-2">
            <button type="button" class="btn btn-lg btn-light-primary me-3" @click="goToLogin">
              Kembali
            </button>
          </div>
          <div>
            <button type="submit" id="submit-form" class="btn btn-lg btn-primary">
              Reset Password
            </button>
          </div>
        </div>
      </form>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import axios from "axios"; // Pastikan axios diimpor
import { toast } from "vue3-toastify";
import router from "@/router";

export default defineComponent({
  name: "ResetPassword",
  setup() {
    const formData = ref({
      email: "",
      password: "",
      password_confirmation: "",
    });

    const handleSubmit = async (event: Event) => {
      event.preventDefault();

      // Cek jika password cocok
      if (formData.value.password !== formData.value.password_confirmation) {
        toast.error("Password tidak cocok");
        return;
      }

      // Siapkan data untuk API
      const payload = {
        email: formData.value.email,
        password: formData.value.password,
      };

      try {
        const response = await axios.post("http://localhost:8000/auth/reset-password", payload); // Ganti dengan URL backend Anda
        toast.success("Password berhasil direset. Silakan masuk.");
        router.push({ name: "sign-in" });
      } catch (error) {
        // Tangani kesalahan dengan baik
        if (axios.isAxiosError(error)) {
          toast.error(error.response?.data.message || "Terjadi kesalahan");
        } else {
          toast.error("Terjadi kesalahan. Silakan coba lagi.");
        }
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

<style>
  /* Mengubah warna teks untuk label */
  .form-label {
    color: white !important;
  }

  /* Mengubah warna teks di input */
  .form-control {
    color: white !important; /* Mengubah teks input menjadi putih */
    background-color: #333; /* Latar belakang gelap untuk kontras */
  }

  /* Tombol Reset Password dengan teks putih */
  .btn-primary {
    color: white !important;
    background-color: #007bff; /* Warna latar belakang tombol */
  }

  /* Tombol Kembali dengan teks putih */
  .btn-light-primary {
    color: white !important;
    background-color: #6c757d; /* Warna latar belakang tombol */
  }

  /* Sesuaikan font dan warna untuk form */
  .form {
    font-family: Arial, sans-serif; /* Menggunakan font yang lebih konsisten */
  }
</style>
