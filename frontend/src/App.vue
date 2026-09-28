<template>
  <header>
    <nav style="padding: 16px; border-bottom: 1px solid #ddd;">
      <router-link to="/" style="margin-right: 12px;">Home</router-link>
      <router-link to="/tugas">Data Tugas</router-link>
    </nav>
  </header>

  <main>
    <router-view />
  </main>
</template>