// File: resources/js/types/types.ts
export interface Product {
    id: number;
    uuid: string;
    name: string;
    category: string;
    price: number;
    // quantity: number;
    description: string;
    image_url: string;
    is_sold_out?: boolean;
}
