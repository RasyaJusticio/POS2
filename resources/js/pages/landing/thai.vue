<template>
    <default-layout>
      <div class="landing-container">
        <header class="landing-header">
          <h1>Welcome to Thai Delight</h1>
          <p>Experience the authentic flavors of Thailand right here!</p>
          <button @click="viewMenu" class="btn btn-primary">View Menu</button>
        </header>
  
        <main class="landing-main">
          <section class="features">
            <h2>Our Features</h2>
            <ul>
              <li>🍜 Authentic Thai Cuisine</li>
              <li>🍃 Fresh Ingredients Daily</li>
              <li>🍹 Wide Selection of Beverages</li>
              <li>🏡 Cozy and Inviting Atmosphere</li>
              <li>🍽️ Dine-in and Takeout Options</li>
            </ul>
          </section>
  
          <section class="about">
            <h2>About Us</h2>
            <p>
              At Thai Delight, we are dedicated to providing an unforgettable dining experience. Our chefs use traditional recipes and the freshest ingredients to create mouthwatering dishes that celebrate the rich culinary heritage of Thailand. Join us for a meal and taste the difference!
            </p>
          </section>
        </main>
  
        <footer class="landing-footer">
          <p>&copy; {{ new Date().getFullYear() }} Thai Delight. All rights reserved.</p>
        </footer>
      </div>
    </default-layout>
  </template>
  
  <script setup lang="ts">
  import { useRouter } from 'vue-router';
  import { onMounted } from 'vue';
  
  const router = useRouter();
  
  onMounted(() => {
    console.log('Thai restaurant landing page mounted');
  });
  
  // Navigate to the menu page
  const viewMenu = () => {
    router.push('/menu'); // Adjust with your menu route
  };
  </script>
  
  <style scoped>
  .landing-container {
    max-width: 800px;
    margin: auto;
    padding: 20px;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    background-color: #f5f5f5;
    border-radius: 10px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
  }
  
  .landing-header {
    margin-bottom: 20px;
  }
  
  .landing-header h1 {
    font-size: 2.5rem;
    color: #333;
  }
  
  .landing-header p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 20px;
  }
  
  .landing-main {
    margin: 20px 0;
  }
  
  .features,
  .about {
    margin: 20px 0;
  }
  
  .features h2,
  .about h2 {
    font-size: 2rem;
    color: #333;
  }
  
  .features ul {
    list-style: none;
    padding: 0;
    text-align: left;
  }
  
  .features li {
    margin: 10px 0;
    font-size: 1.2rem;
  }
  
  .landing-footer {
    margin-top: 20px;
    font-size: 12px;
    color: #888;
  }
  </style>
  