<template>
  <header class="pos-header">
    <video autoplay muted loop class="background-video">
      <source src="@/assets/images/asiks.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
      <router-link to="/landing/PAGE">
        <button class="btn btn-lg btn-secondary">
    <i class="fas fa-arrow-left "></i>
</button>            </router-link>
    <div class="header-image">
      <img src="@/assets/images/spice.png" alt="Spice Image" style="max-width: 60%; height: auto;" />
    </div>
    <div class="pos-actions">
      <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search Items..."
          class="search-input"
        />
      </div>
    </div>
  </header>
  
  
      <main class="pos-body">
        <section class="pos-items">
          <div class="categories">
            <button
              v-for="category in categories"
              :key="category"
              @click="filterByCategory(category)"
              :class="['btn-category', { active: selectedCategory === category }]"
            >
              {{ category }}
            </button>
          </div>
          <div class="item-list">
            <div
              v-for="item in filteredItems"
              :key="item.id"
              class="item-card"
            >
              <div class="card-inner">
                <img :src="item.image_url" alt="item.name" class="item-image" />
                <div class="item-details">
                  <h3 class="item-name">{{ item.name }}</h3>
                  <p class="item-description">{{ item.description }}</p>
                  <span class="item-price">{{ formatCurrency(item.price) }}</span>
                  <div class="btn-container">
                    <button @click="addToCart(item)" class="btn btn-primary" :disabled="item.is_sold_out">
                 <i class="fas fa-shopping-cart"></i>
                 </button>
                </div>
                  <span v-if="item.is_sold_out" class="sold-out-label">Sold Out</span> <!-- Tambahkan label ini -->
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
  
      <!-- Fixed cart button -->
      <button @click="toggleCart" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count">{{ cartTotalQuantity }}</span>
        </button>
        
  
      <!-- Shopping Cart -->
      <aside v-if="isCartVisible" class="pos-cart">
        <h2 style="padding-top: 20px;">Shopping Cart</h2>
        <ul class="cart-list">
          <li v-for="cartItem in cart" :key="cartItem.id" class="cart-item">
              <!-- <span> {{ cart }} </span> -->
              <span>{{ cartItem.name }}</span>
            <span>{{ cartItem.quantity }} x {{ formatCurrency(cartItem.price) }}</span>
            <button @click="removeFromCart(cartItem)" class="btn btn-secondary">Remove</button>
          </li>
        </ul>
  
        <div class="total">
          <strong>Total: {{ formatCurrency(total) }}</strong>
        </div>
        <router-link :to="{ path: '/landing/payment', query: { cart: JSON.stringify(cart) } }">
  <button class="btn btn-success" style="margin-right: 30px;">Checkout</button>
</router-link>

        <button @click="clearCart" class="btn btn-danger">Clear Cart</button>
  
      </aside>
  
      <footer class="pos-footer">
        <p>&copy; {{ new Date().getFullYear() }} Siam Spice Co.</p>
      </footer>
    
  </template>
  
  <script setup lang="ts">
  import { ref, computed, onMounted } from 'vue';
  import type { Product } from "@/types/pos"; 
  import ApiService from "@/core/services/ApiService";
  import { toast } from "vue3-toastify";
  
  // // Import images 
  // import somTamImage from '@/assets/images/somtam.jpeg';
  // import padImage from '@/assets/images/pad.jpeg';
  // import khaoImage from '@/assets/images/khao.jpeg';
  // import soiImage from '@/assets/images/soi.jpeg';
  // import thaiImage from '@/assets/images/thai.jpeg';
  // import tamImage from '@/assets/images/Tamarind Juice.jpg';
  // import cocoImage from '@/assets/images/Coconut Water.jpg';
  // import yamImage from '@/assets/images/Yam Nua.jpg';
  // import gaengImage from '@/assets/images/Gaeng Som.jpg';
  // import khanomImage from '@/assets/images/Khanom Jeen Nam Ya.jpg';
  // import mangoImage from '@/assets/images/Mango Sticky Rice.jpg';
  // import takoImage from '@/assets/images/Tako.jpg';
  // import lodImage from '@/assets/images/Lod Chong.jpg';
  // import tomImage from '@/assets/images/Tom Kha Kai.jpg';
  // import larbImage from '@/assets/images/Larb.jpg';
  // import paorImage from '@/assets/images/P Aor.jpg';
  // import pranakornImage from '@/assets/images/Pranakorn .jpg';
  // import savoeyImage from '@/assets/images/Savoey.jpg';
  
  // Sample data for items
  // const items = ref([
  //   { id: 1, name: 'Som Tam', description: 'A spicy green papaya salad with shredded papaya, tomatoes, and a tangy dressing.', price: 70000, category: 'Food', image: somTamImage },
  //   { id: 2, name: 'Pad Thai', description: 'Stir-fried rice noodles with shrimp or chicken, eggs, and peanuts in a savory sauce.', price: 70000, category: 'Food', image: padImage },
  //   { id: 3, name: 'Khao Pad', description: 'Thai fried rice with jasmine rice, vegetables, and protein, seasoned with soy sauce.', price: 70000, category: 'Food', image: khaoImage },
  //   { id: 4, name: 'Khao Soi', description: 'Coconut curry noodle soup topped with crispy noodles and garnished with lime.', price: 80000, category: 'Food', image: soiImage },
  //   { id: 5, name: 'Thai Iced Tea', description: 'A sweet, creamy drink made from black tea and sweetened condensed milk.', price: 30000, category: 'Drink', image: thaiImage },
  //   { id: 6, name: 'Tamarind Juice', description: 'Tangy drink made from tamarind pulp, often sweetened and chilled.', price: 20000, category: 'Drink', image: tamImage },
  //   { id: 7, name: 'Coconut Water', description: 'Refreshing, naturally sweet beverage from young green coconuts.', price: 15000, category: 'Drink', image: cocoImage },
  //   { id: 8, name: 'Yam Nua', description: ' Spicy Thai beef salad with grilled beef, fresh herbs, and a zesty lime dressing.', price:  75000, category: 'Food', image: yamImage },
  //   { id: 9, name: 'Gaeng Som', description: 'Tangy sour curry with fish or shrimp and vegetables, flavored with tamarind.', price: 80000, category: 'Food', image: gaengImage },
  //   { id: 10, name: 'Khanom Jeen Nam Ya', description: 'Rice noodles served with a rich fish curry sauce and fresh veggies.', price: 50000, category: 'Food', image: khanomImage },
  //   { id: 11, name: 'Mango Sticky Rice', description: 'Sweet rice topped with ripe mango and drizzled with coconut milk.', price: 25000, category: 'Dessert', image: mangoImage },
  //   { id: 12, name: 'Tako', description: 'Steamed coconut milk dessert in small cups, topped with a creamy layer.', price: 20000, category: 'Dessert', image: takoImage },
  //   { id: 13, name: 'Lod Chong', description: 'Pandan-flavored rice flour noodles served in sweet coconut milk.', price: 20000, category: 'Dessert', image: lodImage },
  //   { id: 14, name: 'Tom Kha Kai', description: 'Chicken in a rich coconut milk broth with lemongrass and galangal.', price: 70000, category: 'Food', image: tomImage },
  //   { id: 15, name: 'Larb', description: 'Spicy salad of minced meat with fresh herbs and lime juice.', price: 70000, category: 'Food', image: larbImage },
  //   { id: 16, name: 'P Aor', description: 'Flavorful Thai noodle soup with various toppings in a savory broth.', price:  80000 , category: 'Food', image: paorImage },
  //   { id: 17, name: 'Pranakorn', description: 'Grilled marinated meat served with a spicy dipping sauce.', price: 75000, category: 'Food', image: pranakornImage },
  //   { id: 18, name: 'Savoey', description: 'Grilled marinated meat served with a spicy dipping sauce.', price: 80000, category: 'Food', image: savoeyImage },
  // ]);

  const items = ref<Product[]>([]);
  
  const categories = ref(['All', 'makanan', 'minuman', 'dessert']);
  const cart = ref([]);
  const searchQuery = ref('');
  const selectedCategory = ref('All');
  const discount = ref(0);
  const isCartVisible = ref(false);
  

  const fetchProducts = async () => {
    try {
        const response = await ApiService.get('/inventori/produk');
        console.log(response.data);
        items.value = response.data; 
    } catch (error) {
        console.error("Error fetching products:", error);
    }
};

onMounted(() => {
    fetchProducts();
});




  // Computed properties
  const filteredItems = computed(() => {
    return items.value.filter(item => {
      const matchesSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase());
      const matchesCategory = selectedCategory.value === 'All' || item.category === selectedCategory.value;
      return matchesSearch && matchesCategory;
    });
  });
  
  const total = computed(() => {
    const subtotal = cart.value.reduce((acc, item) => acc + (item.price * item.quantity), 0);
    return subtotal - (subtotal * (discount.value / 100));
  });
  
  // Calculate total quantity in cart
  const cartTotalQuantity = computed(() => {
    return cart.value.reduce((acc, item) => acc + item.quantity, 0);
  });//
  
  // Format currency to Rupiah
  const formatCurrency = (value) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
  };
  
  // Add item to cart
  const addToCart = (item) => {
    if (item.is_sold_out) {
      toast.error("Item ini sudah terjual habis dan tidak bisa dipesan")
    return; // Keluar dari metode jika sold out
  }
  
    const existingItem = cart.value.find(cartItem => cartItem.id === item.id);
    if (existingItem) {
      existingItem.quantity++;
    } else {
      cart.value.push({ ...item, quantity: 1 });
    }
  };
  
  // Remove item from cart
  const removeFromCart = (cartItem) => {
    cart.value = cart.value.filter(item => item.id !== cartItem.id);
  };
  
  // Clear the cart
  const clearCart = () => {
    cart.value = [];
  };
  
  // Checkout function
  const checkout = () => {
    alert('Total after discount: ${formatCurrency(total.value)} - Proceeding to payment...');

    // Add payment processing logic here
  };
  
  // Filter items by category
  const filterByCategory = (category) => {
    selectedCategory.value = category;
  };
  
  // Toggle cart visibility
  const toggleCart = () => {
    isCartVisible.value = !isCartVisible.value;
  };
  </script>
  
  <style scoped>
  .pos-container {
      max-width: 1600px;
      margin: auto;
      padding: 20px;
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
      border-radius: 10px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
  }

  .background-video {
    position: fixed;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -1; /* Pastikan video berada di belakang konten lainnya */
    transform: translate(-50%, -50%);
    object-fit: cover; /* Memastikan video memenuhi area yang tersedia */
}
  
  .pos-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .header-image {
    margin-left: 33%;
  }
  
  .pos-actions {
    display: flex;
    align-items: center;
  }
  
  .search-container {
    position: relative; /* Position relative for absolute positioning of icon */
  }
  
  .search-icon {
    position: absolute; /* Position the icon inside the input */
    left: 10px; /* Adjust positioning */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Center the icon */
    color: #aaa; /* Icon color */
    pointer-events: none; /* Prevents clicking the icon */
    margin-left: 70%
  }
  
  .search-input {
    padding: 12px 15px 12px 35px; /* Adjust padding for the icon */
    margin-right: 30px;
    border: 1px solid #ccc;
    border-radius: 25px;
    width: 250px;
    transition: border-color 0.3s, box-shadow 0.3s;
    font-size: 1rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  
  .search-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }
  
  
  .pos-body {
    display: flex;
    gap: 20px;
  }
  
  .pos-items {
    flex: 2;
    background: rgba(255, 255, 255, 0); /* Transparent background */
    box-shadow: none; /* Remove shadow */
    border-radius: 10px;
    padding: 20px;
}

  
  .fixed-cart-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px 15px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s;
  }
  
  .fixed-cart-button:hover {
    background-color: #0056b3;
  }
  
  .pos-cart {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 400px; /* Increase the width as desired */
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
  }
  
  .categories {
    display: flex;
    justify-content: center; /* Center the buttons horizontally */
    margin-bottom: 15px;
    flex-wrap: wrap; /* Allow wrapping if necessary */
  }
  
  .btn-category {
    margin-right: 5px;
    padding: 15px 25px; /* Increased padding */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #123352;
    color: white;
    transition: background-color 0.3s;
    font-size: 1.2rem; /* Increased font size */
  }
  
  
  .btn-category:hover {
    background-color: #ffffff;
    color: rgb(173, 126, 25);

  }
  
  .btn-category.active {
    background-color: #ffffff;
    color: rgb(173, 126, 25);

  }
  
  .item-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .item-card {
    width: calc(32% - 10px);
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    margin-left: 10px; /* Adjusted margin to shift right */
  }
  
  .card-inner {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  
  .item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }
  
  .item-image {
    width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 10px;
  }
  
  .item-details {
    text-align: center;
  }
  
  .item-name {
    font-size: 1.7rem;
    color: #333;
    margin-bottom: 5px;
  }
  
  .item-description {
    font-size: 1.2rem;
    color: #666;
    margin: 10px 0;
  }
  
  .item-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #28a745;
    margin-bottom: 10px;
  }

  .btn-primary {
      position: fixed; /* Fixes the button at a specific position */
      bottom: 20px; /* Adjust this value to move it up or down */
      right: 20px; /* Position from the right */
      background-color: #007bff; /* Default background color */
      color: white; /* Text color */
      padding: 10px 15px; /* Padding for the button */
      border: none; /* Remove border */
      border-radius: 5px; /* Rounded corners */
      cursor: pointer; /* Pointer on hover */
      transition: background-color 0.3s; /* Smooth transition for hover effect */
      z-index: 1000; /* Ensure it appears above other content */
  }
  
  .cart-list {
    list-style: none;
    padding: 0;
    font-size: medium;
    margin-top: 9%;
  }
  
  .btn-secondary {
    background-color: #8d8e8e;
    color: rgb(0, 0, 0);
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.4s;
    margin-left: 10px;
  }
  
  .cart-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0px;
  }
  
  .total {
    margin-top: 20px;
    font-weight: bold;
    font-size: 1.2rem;
  }
  
  .pos-footer {
    text-align: center;
    margin-top: 20px;
    font-size: 12px;
    color: #888;
  }
  
  .floating-cart-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #007bff;
      color: rgb(255, 255, 255);
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      box-shadow: 0 4px 10px rgb(14, 15, 72);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background-color 0.1s, transform 0.1s;
      z-index: 1000;
  }
  
  .floating-cart-button {
      width: 80px; /* Increased size */
      height: 85px; /* Increased size */
  }
  
  
  .cart-count {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 5px;
      font-size: 12px;
 }

 .sold-out {
    filter: grayscale(100%); /* Menjadikan item hitam putih */
    opacity: 0.5; /* Mengurangi opasitas */
  }

  .sold-out-label {
    color: red; /* Warna label sold out */
    font-weight: bold; /* Menggunakan huruf tebal */
  }

</style>