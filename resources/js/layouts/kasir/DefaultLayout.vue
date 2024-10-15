<template>
  <div class="default-layout">
    <header>
      <nav class="navbar navbar-expand-lg navbar-dark transparent-navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
          <router-link class="navbar-brand" to="/dashboard">
            <img src="@/assets/images/spice.png" class="rounded-4" alt="Logo" style="width: auto; height: 100px;" />
          </router-link>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
              <router-link class="nav-link" to="#home" :class="{ active: $route.hash === '#home' }" style="color: #00132e; font-size: 18px;">
                <i class="fas fa-home" style="color: #00132e; font-size: 20px;"></i> Home
              </router-link>

              <router-link class="nav-link" to="#menu" :class="{ active: $route.hash === '#menu' }" style="color: #00132e; font-size: 18px;">
                <i class="fas fa-utensils" style="color: #00132e; font-size: 20px;"></i> Menu
              </router-link>

              <router-link class="nav-link" to="#about" :class="{ active: $route.hash === '#about' }" style="color: #00132e; font-size: 18px;">
                <i class="fas fa-info-circle" style="color: #00132e; font-size: 20px;"></i> About Us
              </router-link>

              <router-link class="nav-link" to="#contact" :class="{ active: $route.hash === '#contact' }" style="color: #00132e; font-size: 18px;">
                <i class="fas fa-envelope" style="color: #00132e; font-size: 20px;"></i> Contact
              </router-link>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <RouterView /> <!-- This will render the landing page content -->
    </main>

    <footer>
      <p>&copy; {{ new Date().getFullYear() }} Siam Spice Co. All Rights Reserved.</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { RouterView } from 'vue-router';

const title = ref('Siam Spice'); // Change this to your desired title
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.default-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: 'Poppins', sans-serif; /* Clean font style */
  background-color: #f8f9fa6b; /* Light background */
}

header {
  position: fixed; /* Fixed position */
  top: 0; /* Stick to the top */
  width: 100%; /* Full width */
  z-index: 1000; /* Above other content */
}

.transparent-navbar {
  background: rgba(255, 255, 255, 0); /* Transparent background */
  backdrop-filter: blur(4px); /* Blur effect */
  box-shadow: none; /* Remove shadow */
}

main {
  margin-top: 80px; /* Adjust based on the height of the navbar */
}

.navbar {
  font-size: 1.2rem;
}

.logo {
  height: 80px; /* Logo size */
  transition: transform 0.3s; /* Scale on hover */
}

.logo:hover {
  transform: scale(1.1); /* Scale logo on hover */
}

.nav-link {
  color: #ffffff; /* Link color */
  margin: 0 15px; /* Space between links */
  padding: 10px 15px; /* Padding for clickable area */
  transition: background-color 0.3s, color 0.3s; /* Smooth transitions */
  border-radius: 5px; /* Rounded corners */
  font-weight: bold; /* Make text bold */
  display: flex;
  align-items: center;
}

.nav-link i {
  margin-right: 8px; /* Adjusted space between icon and text */
  font-size: 1.1rem; /* Icon size */
}

.nav-link.active {
  border-bottom: 3px solid #080e42; /* Garis bawah untuk tautan aktif */
  color: #d9534f; /* Warna teks berubah saat aktif */
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1); /* Highlight on hover */
  color: #d9534f; /* Change text color on hover */
}

footer {
  background-color: #343a40; /* Dark footer */
  color: #ffffff; /* Footer text color */
  text-align: center;
  padding: 20px;
  margin-top: auto; /* Push footer to the bottom */
  font-weight: bold; /* Make footer text bold */
}

footer p {
  margin: 0; /* Remove default margin */
}

.social-icons {
  margin-top: 10px;
}

.social-icons a {
  color: white;
  margin: 0 10px;
  font-size: 1.5rem; /* Adjust icon size */
  transition: color 0.3s;
}

.social-icons a:hover {
  color: #d9534f; /* Change icon color on hover */
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .nav-link {
    padding: 8px 10px; /* Smaller padding on mobile */
  }

  .logo {
    height: 60px; /* Adjust logo size for mobile */
  }
}
</style>
