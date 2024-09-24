// File: resources/js/types/types.ts

export interface Item {
    id: number;
    name: string;
    price: number;
    quantity: number;
    description: string;
    image_url: string;
}

export interface Product {
    id: number;
    name: string;
    category: string;
    price: number;
    quantity: number;
    description: string;
    image_url: string;
}
