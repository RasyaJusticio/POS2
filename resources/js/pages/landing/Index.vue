<template>
  <div class="landing-container">
    <main class="landing-body">
      <section class="hero-banner">
        <div class="position-relative">
          <img
            src="@/assets/images/ppp.png"
            alt="Delicious Thai Food"
            class="img-fluid rounded hero-image1"
          />
          <div class="hero-text position-absolute top-20 start-20 text-white p-4">
            <h1>Welcome! ยินดีต้อนรับ</h1>
            <h2>Experience the Best of Thailand</h2>
            <p>Join us for a delightful dining experience with authentic flavors.</p>
            <p>Discover the rich culinary heritage of Thailand, crafted with love and tradition.</p>
            <router-link to="/landing/thai">
              <button class="btn btn-lg btn-primary">Order Now</button>
            </router-link>
          </div>
        </div>
      </section>

<section class="menu-categories text-center">
  <h2>Most Popular</h2>
  <div class="row justify-content-center">
    <div class="col-md-3 category-card">
      <div class="card">
        <img src="@/assets/images/somtam.jpeg" alt="Appetizers" class="img-fluid rounded-top" />
        <div class="card-body">
          <h3>Som Tam</h3>
          <h3>ส้มตำ</h3>
          <button class="btn btn-outline-primary" @click="showDescription('somtam')">View More</button>
        </div>
      </div>
    </div>

    <div class="col-md-3 category-card">
      <div class="card">
        <img src="@/assets/images/thai.jpeg" alt="Appetizers" class="img-fluid rounded-top" />
        <div class="card-body">
          <h3>Thai Tea</h3>
          <h3>ชาเย็น</h3>
          <button class="btn btn-outline-primary" @click="showDescription('thaitea')">View More</button>
        </div>
      </div>
    </div>

    <div class="col-md-3 category-card">
      <div class="card">
        <img src="@/assets/images/P Aor.jpg" alt="Appetizers" class="img-fluid rounded-top" />
        <div class="card-body">
          <h3>P Aor</h3>
          <h3>ก๋วยเตี๋ยวพะโล้</h3>
          <button class="btn btn-outline-primary" @click="showDescription('paor')">View More</button>
        </div>
      </div>
    </div>
  </div>
  
  <div v-if="visibleDescription" class="floating-card">
  <button class="close-btn" @click="visibleDescription = null">X</button>
  
  <h3 v-if="visibleDescription === 'somtam'">SOMTAM</h3>
  <p v-if="visibleDescription === 'somtam'">
    A spicy green papaya salad that highlights fresh, crunchy vegetables. Tossed in a zesty lime dressing, it offers a perfect balance of heat and tang. This dish is a staple of Thai cuisine, loved for its vibrant flavors and textures.
  </p>
  
  <h3 v-if="visibleDescription === 'thaitea'">THAI TEA</h3>
  <p v-if="visibleDescription === 'thaitea'">
    A delightful blend of strong brewed tea and creamy milk, sweetened to perfection. Its rich, aromatic flavors are enhanced with a hint of spices, making it a favorite refreshment. Served over ice, this drink is both satisfying and refreshing on a warm day.
  </p>
  
  <h3 v-if="visibleDescription === 'paor'">P AOR</h3>
  <p v-if="visibleDescription === 'paor'">
    A savory noodle soup that features tender meat and a rich, flavorful broth. Infused with aromatic herbs, each bowl is a comforting experience. It’s a beloved dish that captures the essence of Thai comfort food, perfect for any time of day.
  </p>
</div>

</section>



      <section id="pricing" class="pricing-section text-center">
        <h2>Pricing</h2>
        <p>Affordable delights for every taste. Check out our menu for detailed pricing!</p>
        <button class="btn btn-lg btn-outline-primary" @click="navigateToMenu('pricing')">View Pricing</button>
      </section>

      <section class="about-us text-center">
        <h2>About Us</h2>
        <img
          src="@/assets/images/spice.png"
          alt="Delicious Thai Food"
          class="img-fluid rounded hero-image"
        />
        <p>At our restaurant, we bring the heart of Thailand to your plate. Join us for a culinary journey that celebrates traditional flavors and contemporary dining.</p>
        <button class="btn btn-lg btn-primary" @click="navigateToMenu('about')">Learn More</button>
      </section>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// State to manage visibility of the description card
const visibleDescription = ref<string | null>(null);

// Function to navigate to a specific menu
const navigateToMenu = (category: string) => {
  router.push({ name: category });
};

// Function to show the food description based on the item clicked
const showDescription = (item: string) => {
  visibleDescription.value = item;
};
</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

.landing-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.hero-banner {
  margin-bottom: 30px;
}

.hero-image1 {
  max-width: 100%; /* Set a maximum width (adjust as needed) */
  display: block; /* Centering the image */
  margin: 0 auto; /* Center the image */
  height: auto; /* Maintain aspect ratio */
}

.hero-image {
  max-width: 80%; /* Set a maximum width (adjust as needed) */
  height: auto; /* Maintain aspect ratio */
  filter: brightness(70%); /* Darken the image for better text contrast */
  border-radius: 10px; /* Rounded corners */
  margin: 0 auto; /* Center the image */
  display: block; /* Centering the image */
}

.hero-text {
  border-radius: 10px;
  position: absolute;
  top: 50%;
  left: 15%; /* Adjust this value to shift further left */
  transform: translateY(-50%); /* Center vertically */
  text-align: left; /* Align text to the left */
}

.hero-text h1 {
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
  font-size: 4rem;
  margin-bottom: 5%;
}

.hero-text h2 {
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
  font-size: 2.5rem;
  margin-bottom: 10px;
  color: #000000;
}

.hero-text p {
  font-family: "Fredoka", sans-serif;
  font-size: 1.5rem;
  margin-bottom: 5px;
  color: #000000;
}

.btn-primary {
  background-color: #d9534f; /* Customize button color */
  border: none;
  padding: 12px 24px; /* Increase padding for a larger button */
  font-size: 1.25rem; /* Increase font size */
  border-radius: 0.5rem; /* Optional: Adjust border radius for a rounded effect */
  margin: 10px; /* Add spacing around the button */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow effect */
  transition: box-shadow 0.3s; /* Smooth transition for the shadow */
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.btn-primary:hover {
  background-color: #c9302c; /* Darker shade on hover */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Increase shadow on hover */
}

.menu-categories {
  padding: 50px 20px;
  background-color: #f8f9fa; /* Light background */
  border-radius: 10px;
}

.menu-categories h2 {
  margin-bottom: 30px;
  font-size: 2.5rem;
  color: #c8102e; /* Highlight color */
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.category-card {
  margin: 15px; /* Space between cards */
}

.card {
  border: none;
  transition: transform 0.3s, box-shadow 0.3s;
  border-radius: 10px;
  overflow: hidden; /* Rounded corners */
  background: #ffffff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Increase shadow on hover */
}

.card-body {
  padding: 20px;
  text-align: center;
}

.card-body h3 {
  font-size: 1.5rem;
  margin: 10px 0;
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.card-body p {
  color: #555; /* Darker text for better readability */
}

.btn-outline-primary {
  border: 2px solid #c8102e; /* Outline color */
  color: #c8102e;
  transition: background-color 0.3s, color 0.3s;
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.btn-outline-primary:hover {
  background-color: #c8102e; /* Button background on hover */
  color: white;
}

.pricing-section, .about-us {
  padding: 30px 0;
  background-color: #fff; /* White background for sections */
  margin: 20px 0;
  border-radius: 10px;
}

.pricing-section h2, .about-us h2 {
  margin-bottom: 20px;
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.pricing-section p, .about-us p {
  color: #555; /* Darker text for better readability */
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.btn-outline-primary {
  border-color: #d9534f;
  color: #d9534f;
}

.btn-outline-primary:hover {
  background-color: #d9534f;
  color: white;
}

.floating-card {
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(255, 255, 255, 0.9); /* Adjust alpha for transparency */
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  z-index: 1000; /* Ensure it appears above other content */
  display: flex; /* Enable Flexbox */
  flex-direction: column; /* Stack children vertically */
  align-items: center; /* Center children horizontally */
  justify-content: center; /* Center children vertically */
  text-align: justify; /* Justify text inside */
  font-size: 18px;
  width: 80%; /* Set the width to 80% of the viewport */
  max-width: 400px; /* Optional: Set a maximum width */
}

.floating-card h3 {
  font-size: 24px; /* Larger font for titles */
  margin-bottom: 10px; /* Space below title */
  color: #000000; /* Title color */
  text-transform: uppercase; /* Uppercase titles for emphasis */
}
.close-btn {
  background: transparent;
  border: none;
  font-size: 20px;
  cursor: pointer;
  position: absolute;
  top: 10px;
  right: 10px;
}

.floating-card p {
  margin: 10px 0;
  margin-top: 0%;
}

</style>
