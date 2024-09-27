<template>
  <div class="default-layout">
    <header>
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center ">
          <router-link class="navbar-brand" to="/">
            <img src="@/assets/images/siam2.png" class="logo" alt="Siam Spice Logo" />
          </router-link>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
              <router-link class="nav-link" to="/">Home</router-link>
              <router-link class="nav-link" to="/menu">Menu</router-link>
              <router-link class="nav-link" to="#pricing">Pricing</router-link>
              <router-link class="nav-link" to="/about">About Us</router-link>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <RouterView /> <!-- This will render the landing page content -->
    </main>

    <footer>
      <p>&copy; {{ new Date().getFullYear() }} Siam Spice. All Rights Reserved.</p>
      <div class="social-icons">
        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { RouterView } from 'vue-router';

const title = ref('Siam Spice'); // Change this to your desired title
</script>

<style scoped>
.default-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: 'Poppins', sans-serif; /* Clean font style */
  background-color: #f8f9fa; /* Light background */
}

header {
  background-color: #343a40; /* Dark header */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.navbar {
  font-size: 1.2rem;
}

.logo {
  height: 80px; /* Logo size */
  transition: transform 0.3s; /* Logo scaling effect */
}

.logo:hover {
  transform: scale(1.1); /* Scale logo on hover */
}

.nav-link {
  color: #ffffff; /* Link color */
  margin: 0 15px; /* Space between links */
  padding: 10px 15px; /* Add padding for clickable area */
  transition: background-color 0.3s, color 0.3s; /* Smooth transitions */
}

.nav-link:hover {
  background-color: #d9534f; /* Highlight color on hover */
  color: #ffffff; /* Keep text color on hover */
  border-radius: 5px; /* Rounded corners on hover */
}

footer {
  background-color: #343a40; /* Dark footer */
  color: #ffffff; /* Footer text color */
  text-align: center;
  padding: 20px;
  margin-top: auto; /* Push footer to the bottom */
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
</style>
