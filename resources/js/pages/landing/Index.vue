<template>
  <div class="landing-container">
    <main class="landing-body">
      <section class="hero-banner">
        <div class="position-relative">
          <img src="@/assets/images/siam.png" alt="Delicious Thai Food" class="img-fluid rounded" />
          <div class="hero-text position-absolute top-50 start-50 translate-middle text-center text-white">
            <h2>Experience the Best of Thailand</h2>
            <p>Join us for a delightful dining experience with traditional flavors.</p>
            <button class="btn btn-lg btn-primary">Order Now</button>
          </div>
        </div>
      </section>

      <section class="menu-categories text-center">
        <h2>Explore Our Menu</h2>
        <div class="row justify-content-center">
          <div class="col-md-3 category-card" @click="navigateToMenu('appetizers')">
            <img src="@/assets/images/Tom Kha Kai.jpg" alt="Appetizers" class="img-fluid rounded" />
            <h3>Appetizers</h3>
            <p>Start your meal with our delightful appetizers!</p>
          </div>
          <div class="col-md-3 category-card" @click="navigateToMenu('main-courses')">
            <img src="@/assets/images/P Aor.jpg" alt="Main Courses" class="img-fluid rounded" />  
            <h3>Main Courses</h3>
            <p>Indulge in our signature dishes!</p>
          </div>
          <div class="col-md-3 category-card" @click="navigateToMenu('desserts')">
            <img src="@/assets/images/Mango Sticky Rice.jpg" alt="Desserts" class="img-fluid rounded" />
            <h3>Desserts</h3>
            <p>End your meal with our sweet treats!</p>
          </div>
          <div class="col-md-3 category-card" @click="navigateToMenu('beverages')">
            <img src="@/assets/images/thai.jpeg" alt="Beverages" class="img-fluid rounded" />
            <h3>Beverages</h3>
            <p>Quench your thirst with our refreshing drinks!</p>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const navigateToMenu = (category) => {
  router.push({ name: category });
};
</script>

<style scoped>
.landing-container {
  display: flex;
  flex-direction: column;
  min-height: 200vh;
}

.hero-banner {
  margin-bottom: 30px;
}

.hero-text {
  background: rgba(0, 0, 0, 0.5); /* Dark background for better text contrast */
  padding: 20px;
  border-radius: 10px;
}

.hero-text h2 {
  font-size: 2.5rem;
  margin-bottom: 10px;
  color: #f8f9fa;
}

.hero-text p {
  font-size: 1.5rem;
  margin-bottom: 20px;
}

.btn-primary {
  background-color: #000000; /* Customize button color */
  border: none;
}

.btn-primary:hover {
  background-color: #000000; /* Darker shade on hover */
}

.menu-categories {
  background-color: #f8f9fa; /* Light background for the menu section */
  padding: 30px 0;
  border-radius: 10px;
}

.menu-categories h2 {
  margin-bottom: 20px;
}

.category-card {
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
  background: rgb(255, 255, 255); /* Card background */
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgb(255, 255, 255); /* Increase shadow on hover */
}

.category-card img {
  border-radius: 10px;
}

.category-card h3 {
  margin: 10px 0;
  font-size: 1.5rem;
}

.category-card p {
  color: #723713; /* Slightly darker text color for description */
}
</style>
