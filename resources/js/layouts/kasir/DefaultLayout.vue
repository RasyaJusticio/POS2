<template>
  <div class="default-layout">
    <header>
      <nav class="navbar navbar-expand-lg p-4" style="background-color: black; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <div class="container-fluid d-flex justify-content-between align-items-center">
          <router-link class="navbar-brand" to="/">
            <img src="@/assets/images/siam.png" alt="Logo" style="width: auto; height: 100px;" /> <!-- Add your logo here -->
          </router-link>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto">
              <router-link class="nav-link active p-3 mb-6 bg-success border border-black text-white fs-1" aria-current="page" to="/landing/PAGE">Home</router-link>
              <router-link class="nav-link p-3 mb-6 bg-success border border-black text-white fs-1" to="/landing/PAGE">Features</router-link>
              <router-link class="nav-link p-3 mb-6 bg-success border border-black text-white fs-1" to="/landing/PAGE">Pricing</router-link>
              <a class="nav-link disabled text-white fs-1" aria-disabled="true">Siam Restaurant</a>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <RouterView /> <!-- This will render the landing page content -->
    </main>

    <footer>
      <p class="text-center">&copy; {{ new Date().getFullYear() }} Siam Spice</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { RouterView } from 'vue-router';

const title = ref('Siam Spice'); // Change this to your desired title
</script>


<!-- <style scoped>
.default-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: 'Poppins', sans-serif; /* Font style for the entire layout */
}

header {
  background: #704b15;
  color: #ff0000; /* Light color for better contrast */
  padding: 20px;
  text-align: center;
}

nav {
  display: flex;
  flex-direction: column; /* Stack items vertically for clarity */
  font-size: 2rem; /* Increase font size for the title */
}

nav h1 {
  font-size: 2rem; /* Increase font size for the title */
  margin-bottom: 10px; /* Add space below title */
}

nav ul {
  list-style-type: none;
  padding: 0;
  display: flex; /* Use flex for horizontal layout */
  justify-content: center; /* Center align the links */
}

nav ul li {
  margin: 0 20px; /* Add more space between links */
  border: 2px solid transparent; /* Add transparent border for spacing */
  border-radius: 5px; /* Rounded corners */
  transition: border-color 0.3s; /* Smooth border color transition */
}

nav ul li:hover {
  border-color: #000000; /* Change border color on hover */
}

.nav-link {
  color: #000000; /* Change link color */
  padding: 40px 20px; /* Add padding for clickable area */
}

.nav-link:hover {
  color: #2225ba; /* Change hover color */
}

nav ul li a {
  text-decoration: none; /* Remove underline from links */
  color: #ff0000; /* Link color */
  font-weight: bold; /* Make links bold */
  padding: 10px 15px; /* Add padding for clickable area */
}

main {
  flex: 1;
  padding: 20px;
}

footer {
  background:  #704b15;
  color: #000000;
  text-align: center;
  padding: 30px;
}

.logo {
  width: 100px; /* Adjust size as needed */
  height: auto;
  margin-right: 10px; /* Space between logo and title */
}


</style> -->
