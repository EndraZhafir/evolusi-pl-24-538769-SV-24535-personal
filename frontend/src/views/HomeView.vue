<template>
  <div class="home">
    <h1>Dashboard CI/CD Frontend</h1>
    <p>Aplikasi ini mempraktikkan pipeline empat gerbang menggunakan GitHub Actions dan Vue 3.</p>
    <router-link to="/tugas" class="btn">Lihat Data Tugas Laravel</router-link>
  </div>
</template>

<style scoped>
.home {
  padding: 24px;
  font-family: sans-serif;
}
.btn {
  display: inline-block;
  margin-top: 12px;
  padding: 8px 16px;
  background: #42b883;
  color: white;
  text-decoration: none;
  border-radius: 4px;
}
</style>