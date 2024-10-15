export interface TransactionReport {
    id: number; // Menggunakan number karena id di Laravel biasanya integer
    pembelian_id: number; // Sesuaikan dengan foreignId yang menggunakan integer
    status: string; // Menggunakan union type untuk status
    total_price: number; // Tambahkan kolom total_price
    created_at: string;
}
