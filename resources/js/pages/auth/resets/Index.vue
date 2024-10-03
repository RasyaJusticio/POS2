<template>
  <div class="w-lg-500px w-100">
    <main class="form w-100">
      <img src="@/assets/images/siam2.png" alt="" class="w-500px mb-0" style="max-width: 100%; height: auto;" />

      <div class="mb-0 text-center" style="margin-top: 10px;">
        <h1 class="text-primary"><strong>Reset Password</strong></h1>
      </div>

      <form @submit.prevent="handleResetSubmit">
        <div class="mb-4">
          <label for="email" class="form-label" style="color: white;">Email</label>
          <input v-model="resetForm.email" type="email" class="form-control" id="email" required />
        </div>

        <div class="mb-4">
          <label for="password" class="form-label" style="color: white;">New Password</label>
          <input v-model="resetForm.password" type="password" class="form-control" id="password" required />
        </div>

        <div class="mb-4">
          <label for="confirm-password" class="form-label" style="color: white;">Confirm New Password</label>
          <input v-model="resetForm.password_confirmation" type="password" class="form-control" id="confirm-password" required />
        </div>

        <div>
          <button type="submit" class="btn btn-lg btn-primary" style="color: white;">Reset Password</button>
        </div>
      </form>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import axios from "axios"; 
import { toast } from "vue3-toastify";
import router from "@/router"; 

export default defineComponent({
  name: "ResetPassword",
  setup() {
    const resetForm = ref({
      email: "",
      password: "",
      password_confirmation: "",
    });

    const handleResetSubmit = async () => {
      if (resetForm.value.password !== resetForm.value.password_confirmation) {
        toast.error("Password tidak cocok");
        return;
      }

      try {
        const response = await axios.post("/auth/reset-password", {
          email: resetForm.value.email,
          password: resetForm.value.password,
          password_confirmation: resetForm.value.password_confirmation
        });
        toast.success("Password berhasil direset. Silakan masuk.");
        router.push({ name: "sign-in" });
      } catch (error) {
        if (error.response && error.response.data) {
          const errors = error.response.data.errors; 
          for (const key in errors) {
            toast.error(`${key}: ${errors[key].join(', ')}`);
          }
        } else {
          toast.error("Terjadi kesalahan. Silakan coba lagi.");
        }
      }
    };

    return {
      resetForm,
      handleResetSubmit,
    };
  },
});
</script>

<style scoped>

</style>
