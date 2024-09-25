<template>
  <div class="pos-container">
    <header class="pos-header">
      <h1 class="header-title">Point of Sale</h1>
      <div class="pos-actions">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search Items..."
          class="search-input"
        />
        <button @click="checkout" class="btn btn-success button-spacing">Checkout</button>
        <button @click="clearCart" class="btn btn-danger">Clear Cart</button>
      </div>
    </header>

    <main class="pos-body">
      <section class="pos-items">
        <h2>Available Items</h2>
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
              <img :src="item.image" alt="item.name" class="item-image" />
              <div class="item-details">
                <h3 class="item-name">{{ item.name }}</h3>
                <p class="item-description">{{ item.description }}</p>
                <span class="item-price">{{ item.price | currency }}</span>
                <button @click="addToCart(item)" class="btn btn-primary">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <aside class="pos-cart">
        <h2>Shopping Cart</h2>
        <ul class="cart-list">
          <li v-for="cartItem in cart" :key="cartItem.id" class="cart-item">
            <span>{{ cartItem.name }}</span>
            <span>{{ cartItem.quantity }} x {{ cartItem.price | currency }}</span>
            <button @click="removeFromCart(cartItem)" class="btn btn-secondary">Remove</button>
          </li>
        </ul>
        <div class="discount">
          <input
            type="number"
            v-model.number="discount"
            placeholder="Discount %"
            class="discount-input"
          />
        </div>
        <div class="total">
          <strong>Total: {{ total | currency }}</strong>
        </div>
      </aside>
    </main>

    <footer class="pos-footer">
      <p>&copy; {{ new Date().getFullYear() }} Your Company Name</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

// Sample data for items
const items = ref([
  { id: 1, name: 'SomTam', description: 'Spicy green papaya salad.', price: 70000, category: 'Food', image:'/assets/images.jpeg' },
  { id: 2, name: 'T-Shirt', description: 'Stylish cotton t-shirt.', price: 200000, category: 'Clothing', image: '/images/tshirt.jpg' },
  { id: 3, name: 'Laptop', description: 'High-performance laptop.', price: 1500000, category: 'Electronics', image: '/images/laptop.jpg' },
  { id: 4, name: 'Groceries', description: 'Daily essentials.', price: 80000, category: 'Groceries', image: '/images/groceries.jpg' },
]);

const categories = ref(['All', 'Food', 'Clothing', 'Electronics', 'Groceries']);
const cart = ref([]);
const searchQuery = ref('');
const selectedCategory = ref('All');
const discount = ref(0);

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

// Add item to cart
const addToCart = (item) => {
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
  alert(`Total after discount: ${total.value} - Proceeding to payment...`);
  // Add payment processing logic here
};

// Filter items by category
const filterByCategory = (category) => {
  selectedCategory.value = category;
};
</script>

<style scoped>
.pos-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    font-family: 'Poppins', sans-serif; /* Change to Poppins */
    background-color: #f5f5f5;
    border-radius: 10px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
}


.pos-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.button-spacing {
  margin-right: 10px; /* Atur jarak sesuai kebutuhan */
}

.header-title {
  font-size: 2.5rem;
  color: #333;
  text-transform: uppercase;
}

.pos-actions {
  display: flex;
  align-items: center;
}

.search-input {
  padding: 12px;
  margin-right: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 250px;
  transition: border-color 0.3s;
}

.search-input:focus {
  border-color: #007bff;
  outline: none;
}

.pos-body {
  display: flex;
  gap: 20px;
}

.pos-items {
  flex: 2;
  background-color: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.pos-cart {
  flex: 1;
  border-left: 1px solid #ccc;
  padding-left: 20px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.categories {
  margin-bottom: 15px;
}

.btn-category {
  margin-right: 5px;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  background-color: #007bff;
  color: white;
  transition: background-color 0.3s;
}

.btn-category:hover {
  background-color: #0056b3;
}

.btn-category.active {
  background-color: #0056b3;
}

.item-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.item-card {
  width: calc(50% - 10px);
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  position: relative;
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
  font-size: 1.2rem;
  color: #333;
  margin-bottom: 5px;
}

.item-description {
  font-size: 0.9rem;
  color: #666;
  margin: 10px 0;
}

.item-price {
  font-weight: bold;
  color: #28a745;
  margin-bottom: 10px;
}

.btn-primary {
  background-color: #007bff;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.cart-list {
  list-style: none;
  padding: 0;
}

.cart-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0px;
}

.discount {
  margin-top: 10px;
}

.discount-input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 100%;
  transition: border-color 0.3s;
}

.discount-input:focus {
  border-color: #28a745;
  outline: none;
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
</style>
