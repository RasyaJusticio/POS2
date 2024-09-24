import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: "Dashboard",
                name: "dashboard",
                route: "/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },

    // WEBSITE
    {
        heading: "Website",
        route: "/dashboard/website",
        name: "website",
        pages: [
            // MASTER
            {
                sectionTitle: "Master",
                route: "/master",
                keenthemesIcon: "cube-3",
                name: "master",
                sub: [
                    {
                        sectionTitle: "User",
                        route: "/users",
                        name: "master-user",
                        sub: [
                            {
                                heading: "Role",
                                name: "master-role",
                                route: "/dashboard/master/users/roles",
                            },
                            {
                                heading: "User",
                                name: "master-user",
                                route: "/dashboard/master/users",
                            },
                        ],
                    },
                ],
            },

            //Menu POS
            {
                sectionTitle: "POS",
                route: "/pos",
                name: "pos",
                keenthemesIcon: "setting-2",
                sub: [
                    {
                        heading: "Shop",
                        name: "pos-layout",
                        route: "/dashboard/pos/pos-layout",
                    },
                    {
                        heading: "Items",
                        name: "pos-items",
                        route: "/dashboard/pos/pos-items",
                    },
                    {
                        heading: "Cart",
                        name: "pos-cart",
                        route: "/dashboard/pos/pos-cart",
                    },  
                ],
            },

            //Menu Inventori
            {
                sectionTitle: "Inventori",
                route: "/inventori",
                name: "inventori",
                keenthemesIcon: "cube-2",
                sub: [
                    {
                        heading: "Produk",
                        route: "/dashboard/inventori/produk",
                        name: "inventori-produk",
                    },
                    {
                        heading: "Kategori",
                        route: "/dashboard/inventori/kategori",
                        name: "inventori-kategori",
                    },
                    {
                        heading: "Stok",
                        route: "/dashboard/inventori/stok",
                        name: "inventori-stok",
                    },
                ],
            },
            
            {
                heading: "Setting",
                route: "/dashboard/setting",
                name: "setting",
                keenthemesIcon: "setting-2",
            },
        ],
    },
];

export default MainMenuConfig;
