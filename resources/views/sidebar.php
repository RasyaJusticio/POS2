<template>
  <div id="kt_sidebar" class="sidebar sidebar-dark">
    <!-- Brand -->
    <div class="sidebar-logo">
      <a href="/" class="sidebar-brand">
        <img src="/media/logos/metronic.svg" alt="Metronic Logo" />
      </a>
    </div>

    <!-- Menu -->
    <div class="sidebar-menu">
      <ul class="menu">
        <li class="menu-item">
          <router-link to="/">
            <i class="menu-icon fas fa-home"></i>
            <span class="menu-text">Home</span>
          </router-link>
        </li>
        <li class="menu-item">
          <router-link to="/services">
            <i class="menu-icon fas fa-cogs"></i>
            <span class="menu-text">Services</span>
          </router-link>
        </li>
        <li class="menu-item">
          <router-link to="/about">
            <i class="menu-icon fas fa-info-circle"></i>
            <span class="menu-text">About</span>
          </router-link>
        </li>
        <li class="menu-item">
          <router-link to="/contact">
            <i class="menu-icon fas fa-envelope"></i>
            <span class="menu-text">Contact</span>
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Sidebar',
}
</script>

<style scoped>
/* Customize your sidebar styles here */
.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-y: auto;
  color: white;
}
.sidebar-logo {
  text-align: center;
  padding: 20px;
}
.sidebar-menu {
  margin-top: 20px;
}
.menu-item {
  padding: 10px 20px;
}
.menu-item a {
  color: white;
  text-decoration: none;
  display: flex;
  align-items: center;
}
.menu-item a:hover {
  background-color: #575757;
}
</style>
