<template>
  <div class="tugas-container">
    <h2>Daftar Tugas dari Laravel</h2>
    <router-link to="/">Kembali ke Beranda</router-link>

    <div v-if="loading" class="status">Memuat data dari server...</div>

    <div v-else-if="error" class="error-box">
      <p><strong>Peringatan:</strong> {{ error }}</p>
      <p class="hint">Pastikan server Laravel telah dijalankan dengan <code>php artisan serve</code>.</p>
    </div>

    <ul v-else class="list">
      <li v-for="item in daftarTugas" :key="item.id" class="card">
        <h3>{{ item.judul }}</h3>
        <span class="badge">{{ item.status }}</span>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const daftarTugas = ref([])
const loading = ref(true)
const error = ref(null)

const fetchTugas = async () => {
  loading.value = true
  error.value = null
  try {
    const apiUrl = import.meta.env.VITE_API_URL
    const response = await fetch(`${apiUrl}/tugas`)
    if (!response.ok) {
      throw new Error(`Gagal mengambil data (Status: ${response.status})`)
    }
    const result = await response.json()
    daftarTugas.value = result.data || []
  } catch (err) {
    error.value = `Tidak dapat terhubung ke backend Laravel: ${err.message}`
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchTugas()
})
</script>

<style scoped>
.tugas-container {
  padding: 24px;
  font-family: sans-serif;
}
.error-box {
  background: #fee2e2;
  color: #b91c1c;
  padding: 12px;
  border-radius: 6px;
  margin-top: 12px;
}
.card {
  border: 1px solid #e5e7eb;
  padding: 12px;
  margin-bottom: 8px;
  border-radius: 4px;
  list-style: none;
}
.badge {
  background: #e0f2fe;
  color: #0369a1;
  padding: 2px 8px;
  border-radius: 9999px;
  font-size: 12px;
}
</style>