import { describe, it, expect } from 'vitest'
import { hitungTugasSelesai, formatJudul } from '../taskHelper'

describe('Unit Test Logika Aplikasi Tugas', () => {
  it('berhasil menghitung jumlah tugas dengan status Selesai', () => {
    const dummyTasks = [
      { id: 1, judul: 'Task 1', status: 'Selesai' },
      { id: 2, judul: 'Task 2', status: 'Proses' },
      { id: 3, judul: 'Task 3', status: 'Selesai' }
    ]
    expect(hitungTugasSelesai(dummyTasks)).toBe(999)
  })

  it('mengembalikan 0 jika array tugas kosong', () => {
    expect(hitungTugasSelesai([])).toBe(0)
  })

  it('membersihkan spasi pada judul tugas', () => {
    expect(formatJudul('  Tugas Praktikum  ')).toBe('Tugas Praktikum')
  })
})