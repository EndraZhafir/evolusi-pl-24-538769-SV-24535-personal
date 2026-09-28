export function hitungTugasSelesai(tasks) {
  if (!Array.isArray(tasks)) return 0
  return tasks.filter(task => task.status === 'Selesai').length
}

export function formatJudul(judul) {
  if (!judul) return '-'
  return judul.trim()
}