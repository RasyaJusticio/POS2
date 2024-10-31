import axios from "@/libs/axios";
import type { Ref } from "vue";

export class FilterHelper {
    public fetchMethod: "get" | "post" = "get";

    private fetchUrl: string;
    private result: Ref<any>;
    private isLoading: Ref<boolean> | undefined;
    private error: Ref<string> | undefined;

    constructor(
        fetchUrl: string,
        result: Ref<any>,
        isLoading?: Ref<boolean>,
        error?: Ref<string>
    ) {
        this.fetchUrl = fetchUrl;
        this.result = result;

        if (isLoading) {
            this.isLoading = isLoading;
        }
        if (error) {
            this.error = error;
        }
    }

    public filterByDate = async (date: Date | null) => {
        if (!date) {
            this.result.value = [];
            return;
        }

        if (this.isLoading) {
            this.isLoading.value = true;
        }
        if (this.error) {
            this.error.value = "";
        }

        try {
            const formattedDate = date.toISOString().split("T")[0];

            let response;
            if (this.fetchMethod === "get") {
                response = await axios.get(
                    `${this.fetchUrl}?date=${formattedDate}`
                );
            } else {
                response = await axios.post(this.fetchUrl, {
                    date: formattedDate,
                });
            }

            if (response.data.data && Array.isArray(response.data.data)) {
                this.result.value = response.data.data;
            } else {
                throw new Error("Format data tidak valid");
            }
        } catch (err) {
            if (this.error) {
                this.error.value =
                    err instanceof Error
                        ? err.message
                        : "Terjadi kesalahan saat mengambil data";
            }
            this.result.value = [];
        } finally {
            if (this.isLoading) {
                this.isLoading.value = false;
            }
        }
    };
}
