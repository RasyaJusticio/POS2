<template>
    <!--begin::Form-->
    <div class="w-100" style="height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <!--begin::Heading-->
        <div class="text-center mb-5">
            <router-link to="/">
                <img
                :src="setting?.logo"
                :alt="setting?.app"
                class="w-300px mb-0" 
                style="max-width: 100%; height: auto;"
                />
>
            </router-link>
            <h1 style="font-size: 36px; line-height: 1; margin-top: 0; margin-bottom: 0;">
                Welcome!
            </h1>

            <div class="text-primary" style="font-size: 25px;">
                <strong>AON Cashier</strong>
            </div>
        </div>
        <!--end::Heading-->


        <div class="tab-content w-100">
            <div class="tab-pane fade show active" id="with-email" role="tabpanel">
                <WithEmail />
            </div>
        </div>

        <div class="border-bottom border-gray-300 w-100 mt-5 mb-10"></div>
    </div>
    <!--end::Form-->
</template>

<script>
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";

import WithEmail from "./tabs/WithEmail.vue";
import WithPhone from "./tabs/WithPhone.vue";
import { useSetting } from "@/services";

export default defineComponent({
    name: "sign-in",
    components: {
        WithEmail,
        WithPhone,
    },
    setup() {
        const store = useAuthStore();
        const router = useRouter();
        const { data: setting = {} } = useSetting();

        const submitButton = ref(null);

        const login = Yup.object().shape({
            identifier: Yup.string()
                .email("Email/No. Telepon tidak valid")
                .required("Harap masukkan Email/No. Telepon")
                .label("Email"),
            password: Yup.string()
                .min(8, "Password minimal terdiri dari 8 karakter")
                .required("Harap Masukkan Password")
                .label("Password"),
        });

        return {
            login,
            submitButton,
            getAssetPath,
            store,
            router,
            setting,
        };
    },
    data() {
        return {
            data: {
                identifier: null,
                password: null,
            },
            check: {
                type: "",
                error: "",
            },
        };
    },
    methods: {
        submit() {
            blockBtn(this.submitButton);

            axios
                .post("/auth/login", { ...this.data, type: this.check.type })
                .then((res) => {
                    this.store.setAuth(res.data.user, res.data.token);
                    this.router.push("/dashboard");
                })
                .catch((error) => {
                    toast.error(error.response.data.message);
                })
                .finally(() => {
                    unblockBtn(this.submitButton);
                });
        },
        checkInput(value) {
            this.check.type = "";
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                this.check.type = "email";
            } else {
                this.check.type = "phone";
                if (isNaN(this.data.identifier)) {
                    this.check.type =
                        "Masukkan Email / No. Telepon Yang Valid!";
                }
            }
        },
    },
});
</script>

<style scoped>
/* Tambahkan gaya CSS sesuai kebutuhan */
html, body {
    height: 100%;
    margin: 0;
}

body {
    overflow: hidden; /* Mencegah scrolling */
}
</style>
