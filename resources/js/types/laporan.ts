export interface TransactionReport {
    id: number; // Menggunakan number karena id di Laravel biasanya integer
    pembelian_id: number; // Sesuaikan dengan foreignId yang menggunakan integer
    status: 'pending' | 'success'; // Menggunakan union type untuk status
    items: Array<{
        id: number;
        name: string;
        quantity: number;
    }>; // Produk disimpan sebagai array objek dalam format JSON
    total_price: number; // Tambahkan kolom total_price
    created_at: string;
    updated_at: string;
}
