

export interface Pembelian {
    id: number;
    pembelian_id: number;
    status: string;
    total_price: number;
    created_at: string;
    items: string; // Menambahkan items untuk daftar produk yang dibeli
}
