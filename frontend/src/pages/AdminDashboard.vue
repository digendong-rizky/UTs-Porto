<template>
  <div class="page-portfolio">
    <!-- NAVBAR -->
    <header class="nav-wrap">
      <div class="nav-bar">
        <div class="nav-left">
          <span class="brand">Porto Connect</span>
          <img src="@/assets/logo-soegija.png" alt="soegija" class="nav-logo" />
        </div>

        <div class="nav-center">
          <router-link to="/" class="nav-link">Home</router-link>
        </div>

        <div class="nav-right">
          <button class="user-name logout" @click="handleLogout">Logout</button>
          </div>
        </div>
    </header>

    <!-- SEARCH ROW -->
    <section class="search-row">
      <div class="search-inner">
        <div class="search-pill">Dashboard Admin</div>
        <div class="search-input">Kelola user & portofolio</div>
      </div>
    </section>

    <!-- MAIN -->
    <main class="main-container">
      <!-- Tabs -->
      <div class="tabs-card">
        <div class="tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
            :class="['tab-btn', activeTab === tab.id ? 'active' : '']"
            >
              {{ tab.label }}
            </button>
        </div>
      </div>

      <!-- HOME TAB -->
      <section v-if="activeTab === 'home'" class="panel">
      <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card" v-for="item in statItems" :key="item.label">
            <h3>{{ item.label }}</h3>
            <p>{{ item.value }}</p>
        </div>
      </div>

        <!-- Users management -->
        <div class="table-card">
          <div class="table-header">
            <h2>Manajemen Users</h2>
            <div class="actions">
              <input v-model="searchQuery" type="text" placeholder="Cari user..." />
              <select v-model="roleFilter">
              <option value="">Semua Role</option>
              <option value="mahasiswa">Mahasiswa</option>
              <option value="perusahaan">Perusahaan</option>
              <option value="admin">Admin</option>
            </select>
              <button class="btn primary" @click="loadUsers">Cari</button>
              <button class="btn success" @click="showCreateModal = true">+ Tambah User</button>
          </div>
        </div>

          <div class="table-wrapper">
            <table class="data-table">
              <thead>
              <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Aksi</th>
              </tr>
            </thead>
              <tbody>
              <tr v-for="user in paginatedUsers" :key="user.id">
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                  <td>
                    <span :class="user.email_verified_at ? 'status success' : 'status danger'">
                    {{ user.email_verified_at ? 'Verified' : 'Unverified' }}
                  </span>
                </td>
                  <td class="actions-cell">
                    <button 
                      v-if="!user.email_verified_at"
                      class="link primary"
                      @click="verifyUser(user.id)" 
                    >
                      Verify
                    </button>
                    <button class="link danger" @click="deleteUser(user.id)">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
          <div v-if="users.length > itemsPerPage" class="pagination">
            <div class="info">
              Menampilkan
              {{ (currentPage - 1) * itemsPerPage + 1 }}
              -
              {{ Math.min(currentPage * itemsPerPage, users.length) }}
              dari {{ users.length }} users
          </div>
            <div class="pager">
            <button 
              @click="currentPage = Math.max(1, currentPage - 1)"
              :disabled="currentPage === 1"
            >
              Sebelumnya
            </button>
              <span>Halaman {{ currentPage }} dari {{ totalPages }}</span>
            <button 
              @click="currentPage = Math.min(totalPages, currentPage + 1)"
              :disabled="currentPage === totalPages"
            >
              Selanjutnya
            </button>
          </div>
        </div>
      </div>
      </section>

      <!-- PORTFOLIOS TAB -->
      <section v-if="activeTab === 'portfolios'" class="portfolio-section">
        <div class="main-wrap">
          <!-- Sidebar -->
          <aside class="left-sidebar">
            <div class="sidebar-scroll">
              <div
                class="sidebar-item"
                :class="{ active: selectedBidang === null }"
                  @click="filterByBidang(null)"
              >
                <span class="si-text">Semua</span>
                <span class="si-count">{{ totalPortfolios }}</span>
              </div>
              <div
                class="sidebar-item"
                :class="{ active: selectedBidang === 'backend' }"
                  @click="filterByBidang('backend')"
              >
                <span class="si-text">Backend</span>
                <span class="si-count">{{ getCountByBidang('backend') }}</span>
              </div>
              <div
                class="sidebar-item"
                :class="{ active: selectedBidang === 'frontend' }"
                  @click="filterByBidang('frontend')"
              >
                <span class="si-text">Frontend</span>
                <span class="si-count">{{ getCountByBidang('frontend') }}</span>
              </div>
              <div
                class="sidebar-item"
                :class="{ active: selectedBidang === 'fullstack' }"
                  @click="filterByBidang('fullstack')"
              >
                <span class="si-text">Fullstack</span>
                <span class="si-count">{{ getCountByBidang('fullstack') }}</span>
              </div>
              <div
                class="sidebar-item"
                :class="{ active: selectedBidang === 'QATester' }"
                  @click="filterByBidang('QATester')"
              >
                <span class="si-text">QA Tester</span>
                <span class="si-count">{{ getCountByBidang('QATester') }}</span>
              </div>
            </div>
          </aside>

          <!-- Cards panel -->
          <section class="cards-panel">
            <div class="cards-inner">
              <div class="cards-grid">
                <article
                v-for="portfolio in paginatedPortfolios"
                :key="portfolio.id"
                  class="card simple-card"
              >
                  <div class="card-thumb">
                    <span class="dot"></span>
                <button
                  @click.stop="confirmDelete(portfolio)"
                      class="delete-btn"
                  title="Hapus Portofolio"
                >
                      ×
                </button>
                  </div>
                  <div class="card-footer">
                    <div class="card-avatar">
                      <span>{{ (portfolio.mahasiswa?.user?.name || 'U').charAt(0) }}</span>
                </div>
                    <div class="card-texts">
                      <div class="card-name">{{ portfolio.mahasiswa?.user?.name || portfolio.nama }}</div>
                      <div class="card-sub">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || 'Portofolio Baru' }}</div>
                    </div>
                    <div class="card-icon">●</div>
                    </div>
                  <div class="card-body">
                    <div class="badge">{{ portfolio.bidang || 'Portofolio' }}</div>
                    <p v-if="portfolio.deskripsi" class="desc line-clamp-2">
                    {{ portfolio.deskripsi }}
                  </p>
                    <div v-if="portfolio.skills && portfolio.skills.length" class="skills">
                    <span
                      v-for="skill in portfolio.skills.slice(0, 3)"
                      :key="skill.id"
                    >
                      {{ skill.nama }}
                    </span>
                      <span v-if="portfolio.skills.length > 3" class="more">+{{ portfolio.skills.length - 3 }}</span>
                  </div>
                    <button class="btn primary full" @click.stop="viewPortfolio(portfolio)">
                    Lihat Portofolio
                  </button>
                </div>
                </article>
            </div>

              <div v-if="filteredPortfolios.length === 0" class="empty-state">
                Tidak ada portofolio yang ditemukan
            </div>

            <!-- Pagination -->
              <div v-if="filteredPortfolios.length > itemsPerPagePortfolio" class="cards-pagination">
              <button 
                @click="currentPagePortfolio = Math.max(1, currentPagePortfolio - 1)"
                :disabled="currentPagePortfolio === 1"
              >
                ←
              </button>
                <span>{{ currentPagePortfolio }} / {{ totalPagesPortfolio }}</span>
              <button 
                @click="currentPagePortfolio = Math.min(totalPagesPortfolio, currentPagePortfolio + 1)"
                :disabled="currentPagePortfolio === totalPagesPortfolio"
              >
                →
              </button>
            </div>
        </div>
          </section>
      </div>
      </section>
    </main>

    <!-- Modal Create User -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Tambah User Baru</h3>
        
        <form @submit.prevent="createUser()">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
              <input
                v-model="userForm.name"
                type="text"
                required
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Nama lengkap"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input
                v-model="userForm.email"
                type="email"
                required
                class="w-full border rounded-lg px-3 py-2"
                placeholder="email@example.com"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input
                v-model="userForm.password"
                type="password"
                required
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Minimal 8 karakter"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
              <select v-model="userForm.role" required class="w-full border rounded-lg px-3 py-2">
                <option value="">Pilih Role</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="perusahaan">Perusahaan</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          
          <div class="flex gap-2 mt-6">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 disabled:opacity-50"
            >
              {{ loading ? 'Memproses...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4 text-red-600">Konfirmasi Hapus Portofolio</h3>
        <p class="text-gray-700 mb-4">
          Apakah Anda yakin ingin menghapus portofolio <strong>"{{ portfolioToDelete?.nama || 'Portofolio' }}"</strong> milik <strong>{{ portfolioToDelete?.mahasiswa?.user?.name || 'Mahasiswa' }}</strong>?
        </p>
        <p class="text-sm text-gray-500 mb-6">
          Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex gap-2">
          <button
            @click="showDeleteModal = false"
            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
          >
            Batal
          </button>
          <button
            @click="deletePortfolio"
            :disabled="deleting"
            class="flex-1 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 disabled:opacity-50"
          >
            {{ deleting ? 'Menghapus...' : 'Ya, Hapus' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Portfolio Detail Modal -->
    <div v-if="showPortfolioModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
      <div class="bg-white rounded-lg p-6 w-full max-w-4xl my-8 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-2xl font-bold text-gray-800">Detail Portofolio</h3>
          <button
            @click="showPortfolioModal = false"
            class="text-gray-500 hover:text-gray-700"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div v-if="selectedPortfolio" class="space-y-6">
          <!-- Portfolio Header -->
          <div class="bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 rounded-lg p-6 text-white">
            <h2 class="text-3xl font-bold mb-2">{{ selectedPortfolio.nama || 'Portofolio' }}</h2>
            <p v-if="selectedPortfolio.bidang" class="text-lg opacity-90">{{ selectedPortfolio.bidang }}</p>
          </div>

          <!-- Student Info -->
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
              {{ selectedPortfolio.mahasiswa?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div>
              <h4 class="font-bold text-gray-800 text-lg">{{ selectedPortfolio.mahasiswa?.user?.name }}</h4>
              <p class="text-gray-600">{{ selectedPortfolio.mahasiswa?.universitas || '-' }}</p>
              <p class="text-gray-600">{{ selectedPortfolio.mahasiswa?.jurusan || '-' }}</p>
            </div>
          </div>

          <!-- Description -->
          <div v-if="selectedPortfolio.deskripsi">
            <h4 class="font-bold text-gray-800 mb-2">Deskripsi</h4>
            <p class="text-gray-700 whitespace-pre-line">{{ selectedPortfolio.deskripsi }}</p>
          </div>

          <!-- Skills -->
          <div v-if="selectedPortfolio.skills && selectedPortfolio.skills.length > 0">
            <h4 class="font-bold text-gray-800 mb-3">Skills</h4>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="skill in selectedPortfolio.skills"
                :key="skill.id"
                class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ skill.nama }} ({{ skill.level }})
              </span>
            </div>
          </div>

          <!-- Public Link -->
          <div v-if="selectedPortfolio.is_public && selectedPortfolio.public_link" class="p-4 bg-gray-50 rounded-lg">
            <h4 class="font-bold text-gray-800 mb-2">Link Publik</h4>
            <div class="flex gap-2">
              <input
                :value="getPublicUrl(selectedPortfolio.public_link)"
                readonly
                class="flex-1 border rounded-lg px-3 py-2 bg-white text-sm"
              />
              <button
                @click="copyToClipboard(getPublicUrl(selectedPortfolio.public_link))"
                class="bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800 text-sm"
              >
                Salin Link
              </button>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2 pt-4 border-t">
            <button
              @click="showPortfolioModal = false"
              class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
            >
              Kembali
            </button>
            <button
              v-if="selectedPortfolio.public_link"
              @click="window.open(`/portfolio/${selectedPortfolio.public_link}`, '_blank')"
              class="flex-1 bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800"
            >
              Buka di Tab Baru
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="page-footer">
      <div class="footer-inner">
        <div class="contact">
          <h3>Informasi Kontak</h3>
          <div class="c-line">Email : <a href="mailto:unika@unika.ac.id">unika@unika.ac.id</a></div>
          <div class="c-line">Hotline : (024) 850 5003</div>
          <div class="c-line">WhatsApp Official : <a href="https://wa.me/6281232345479">08123 2345 479</a></div>
        </div>

        <div class="footer-logos">
          <div class="porto">Porto<br/>Connect</div>
          <div class="x">✕</div>
          <div class="uni">
            <img src="@/assets/logo-soegija-putih.png" alt="SCU" />
          </div>
        </div>

        <div class="copy">© 2025 PortoConnect. All rights reserved.</div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useSweetAlert } from '@/composables/useSweetAlert'
import { logger } from '@/utils/logger'
const fallbackImage = new URL('@/assets/card-sample.jpg', import.meta.url).href

const { showSuccess, showError, showWarning, showConfirm } = useSweetAlert()

const router = useRouter()
const currentUser = ref(null)
const users = ref([])
const stats = ref({})
const searchQuery = ref('')
const roleFilter = ref('')
const showCreateModal = ref(false)
const loading = ref(false)
const activeTab = ref('home')
const portfolios = ref([])
const allPortfolios = ref([])
const selectedBidang = ref(null)
const currentPagePortfolio = ref(1)
const itemsPerPagePortfolio = 6
const showDeleteModal = ref(false)
const portfolioToDelete = ref(null)
const deleting = ref(false)
const showPortfolioModal = ref(false)
const selectedPortfolio = ref(null)
const currentPage = ref(1)
const itemsPerPage = 10

const userForm = ref({
  id: null,
  name: '',
  email: '',
  password: '',
  role: ''
})

const tabs = [
  { id: 'home', label: 'Home' },
  { id: 'portfolios', label: 'Portofolios' }
]

// Pagination computed
const totalPages = computed(() => {
  return Math.ceil(users.value.length / itemsPerPage)
})

const paginatedUsers = computed(() => {
  if (users.value.length <= itemsPerPage) {
    return users.value
  }
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return users.value.slice(start, end)
})

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }
  
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  
  // Validasi role - pastikan user adalah admin
  try {
    const response = await axios.get('/api/me')
    const user = response.data?.user
    currentUser.value = user
    
    if (!user || user.role !== 'admin') {
      // Redirect berdasarkan role
      if (user?.role === 'mahasiswa') {
        router.push('/profile/mahasiswa')
      } else if (user?.role === 'perusahaan') {
        router.push('/dashboard/perusahaan')
      } else {
        router.push('/dashboard')
      }
      return
    }
    
    // Jika user adalah admin, lanjutkan load data
    await loadStats()
    await loadUsers()
    await loadPortfolios()
  } catch (error) {
    logger.error('Error validating admin access:', error)
    if (error.response?.status === 401 || error.response?.status === 403) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      showError('Gagal memuat data. Silakan refresh halaman.')
    }
  }
})

const loadStats = async () => {
  try {
    const res = await axios.get('/api/admin/dashboard-stats')
    stats.value = res.data
  } catch (error) {
    logger.error('Error loading stats:', error)
  }
}

const loadUsers = async () => {
  try {
    const params = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (roleFilter.value) params.role = roleFilter.value
    
    const res = await axios.get('/api/admin/users', { params })
    users.value = res.data.data || res.data
    // Reset to first page when loading new data
    currentPage.value = 1
  } catch (error) {
    logger.error('Error loading users:', error)
  }
}

const loadPortfolios = async (bidang = null) => {
  try {
    const params = { per_page: 30 }
    if (bidang) params.bidang = bidang
    
    const response = await axios.get('/api/admin/portfolios', { params })
    const list = response.data?.portfolios || response.data?.data || []
    portfolios.value = list
    allPortfolios.value = list
    currentPagePortfolio.value = 1
  } catch (error) {
    logger.error('Error loading portfolios:', error)
  }
}

const filterByBidang = (bidang) => {
  selectedBidang.value = bidang
  currentPagePortfolio.value = 1 // Reset to first page when filtering
  loadPortfolios(bidang)
}

const filteredPortfolios = computed(() => {
  return portfolios.value
})

const totalPortfolios = computed(() => {
  return allPortfolios.value.length
})

const totalPagesPortfolio = computed(() => {
  return Math.ceil(filteredPortfolios.value.length / itemsPerPagePortfolio)
})

const paginatedPortfolios = computed(() => {
  if (filteredPortfolios.value.length <= itemsPerPagePortfolio) {
    return filteredPortfolios.value
  }
  const start = (currentPagePortfolio.value - 1) * itemsPerPagePortfolio
  const end = start + itemsPerPagePortfolio
  return filteredPortfolios.value.slice(start, end)
})

const getCountByBidang = (bidang) => {
  return allPortfolios.value.filter(p => p.bidang === bidang).length
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const confirmDelete = (portfolio) => {
  portfolioToDelete.value = portfolio
  showDeleteModal.value = true
}

const deletePortfolio = async () => {
  if (!portfolioToDelete.value) return
  
  deleting.value = true
  try {
    await axios.delete(`/api/admin/portfolios/${portfolioToDelete.value.id}`)
    showSuccess('Portofolio berhasil dihapus')
    showDeleteModal.value = false
    portfolioToDelete.value = null
    await loadPortfolios(selectedBidang.value)
    // Reload all portfolios for count
    const allResponse = await axios.get('/api/admin/portfolios')
    allPortfolios.value = allResponse.data.portfolios || []
  } catch (error) {
    showError('Gagal menghapus portofolio: ' + (error.response?.data?.message || 'Unknown error'))
  } finally {
    deleting.value = false
  }
}

const verifyUser = async (id) => {
  try {
    await axios.post(`/api/admin/users/${id}/verify`)
    showSuccess('User berhasil diverifikasi')
    await loadUsers()
  } catch (error) {
    showError('Gagal memverifikasi user')
  }
}

const createUser = async () => {
  loading.value = true
  try {
    await axios.post('/api/admin/users', {
      name: userForm.value.name,
      email: userForm.value.email,
      password: userForm.value.password,
      role: userForm.value.role
    })
    showSuccess('User berhasil dibuat')
    closeModal()
    await loadUsers()
    await loadStats()
  } catch (error) {
    const message = error.response?.data?.message || 'Gagal membuat user'
    const errors = error.response?.data?.errors
    if (errors) {
      showError(message + ': ' + Object.values(errors).flat().join(', '))
    } else {
      showError(message)
    }
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id) => {
  const result = await showConfirm('Yakin ingin menghapus user ini?', 'Ya, Hapus', 'Batal')
  if (!result.isConfirmed) return
  
  try {
    await axios.delete(`/api/admin/users/${id}`)
    showSuccess('User berhasil dihapus')
    await loadUsers()
    await loadStats()
  } catch (error) {
    showError('Gagal menghapus user')
  }
}

const closeModal = () => {
  showCreateModal.value = false
  userForm.value = {
    id: null,
    name: '',
    email: '',
    password: '',
    role: ''
  }
}

const getPublicUrl = (publicLink) => {
  return `${window.location.origin}/portfolio/${publicLink}`
}

const copyToClipboard = async (text) => {
  try {
    await navigator.clipboard.writeText(text)
    showSuccess('Link berhasil disalin ke clipboard!')
  } catch (error) {
    logger.error('Failed to copy:', error)
    showError('Gagal menyalin link')
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    logger.warn('Logout error:', err)
  } finally {
    try { localStorage.removeItem('token') } catch (e) { logger.warn('localStorage remove error', e) }
    delete axios.defaults.headers.common['Authorization']
    router.replace('/login')
  }
}
</script>

<style scoped>
.page-portfolio {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: radial-gradient( circle at 50% -20%,
    #000 2%,
    #50145c 18%,
    #50145c 36%,
    #50145c 50%,
    #ffffff 100%
  );
  color: #111;
  font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}

/* NAVBAR */
.nav-wrap { padding: 28px 0; }
.nav-bar {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  align-items:center;
  justify-content:space-between;
  background: rgba(255,255,255,0.95);
  border-radius: 26px;
  padding: 12px 28px;
  box-shadow: 0 8px 30px rgba(6,6,10,0.12);
  height: 64px;
}
.nav-left, .nav-center, .nav-right { display:flex; align-items:center; gap:14px; }
.brand { font-weight:700; color:#5e1f62; }
.nav-logo { height:26px; }
.nav-center { gap:28px; margin-left: 14px;}
.nav-link { color:#222; opacity:0.9; }
.nav-link.active { font-weight:700; }
.user-name { color:#222; font-weight:600; }
.user-name.logout { cursor:pointer; }
.sep { color:#222; }

/* search row */
.search-row { margin-top: 10px; }
.search-inner {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  gap:18px;
  align-items:center;
  padding: 4px 12px;
}
.search-pill {
  min-width:140px;
  padding:10px 18px;
  border-radius:999px;
  background: #ffffff;
  border:none;
  box-shadow: 0 8px 20px rgba(6,6,10,0.06);
}
.search-input {
  flex:1;
  min-height: 42px;
  border-radius: 999px;
  padding: 0 20px;
  border: 2px solid rgba(0,0,0,0.8);
  background: white;
  color: #222;
  box-shadow: 0 8px 20px rgba(6,6,10,0.06);
  display:flex;
  align-items:center;
}

/* MAIN CONTAINER */
.main-container {
  width: min(1200px, 94%);
  margin: 24px auto 0;
  flex: 1;
}

/* Tabs */
.tabs-card {
  background: rgba(255,255,255,0.9);
  border-radius: 16px;
  box-shadow: 0 16px 40px rgba(6,6,10,0.08);
  padding: 12px;
  margin-bottom: 18px;
}
.tabs {
  display:flex;
  gap: 8px;
}
.tab-btn {
  padding: 10px 16px;
  border-radius: 12px;
  border: none;
  background: transparent;
  font-weight: 600;
  color: #333;
  cursor: pointer;
}
.tab-btn.active {
  background: #50145c;
  color: #fff;
  box-shadow: 0 10px 20px rgba(6,6,10,0.16);
}

/* Panel */
.panel {
  background: rgba(255,255,255,0.9);
  border-radius: 18px;
  box-shadow: 0 20px 60px rgba(6,6,10,0.1);
  padding: 18px;
  margin-bottom: 24px;
}

/* Stats */
.stats-grid {
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 12px;
}
.stat-card {
  background: #fff;
  border-radius: 14px;
  padding: 14px 16px;
  box-shadow: 0 12px 30px rgba(6,6,10,0.08);
}
.stat-card h3 { margin:0; font-size:13px; color:#666; }
.stat-card p { margin:4px 0 0 0; font-size:24px; font-weight:700; color:#111; }

/* Table card */
.table-card {
  margin-top: 18px;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 16px 40px rgba(6,6,10,0.08);
}
.table-header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding: 14px 16px;
  border-bottom: 1px solid #eee;
}
.table-header h2 { margin:0; font-size:18px; font-weight:700; }
.actions { display:flex; gap:8px; flex-wrap:wrap; }
.actions input, .actions select {
  border:1px solid #ddd;
  border-radius:10px;
  padding:8px 10px;
}
.btn { border:none; border-radius:10px; padding:9px 12px; font-weight:600; cursor:pointer; }
.btn.primary { background:#50145c; color:#fff; }
.btn.success { background:#16a34a; color:#fff; }
.btn.danger { background:#ef4444; color:#fff; }

.table-wrapper { overflow-x:auto; }
.data-table { width:100%; border-collapse: collapse; }
.data-table thead { background:#f8f8f8; }
.data-table th, .data-table td {
  padding: 12px 14px;
  text-align:left;
  border-bottom:1px solid #f0f0f0;
  font-size:14px;
}
.status { padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; }
.status.success { background:#e0f2f1; color:#0f766e; }
.status.danger { background:#fee2e2; color:#b91c1c; }
.actions-cell { display:flex; gap:10px; align-items:center; }
.link { border:none; background:none; font-weight:600; cursor:pointer; }
.link.primary { color:#2563eb; }
.link.danger { color:#e11d48; }

.pagination {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding: 20px 16px;
  border-top:1px solid #eee;
  color:#444;
  margin-top: 12px;
}
.pager { display:flex; gap:10px; align-items:center; }
.pager button {
  padding:8px 12px;
  border-radius:10px;
  border:1px solid #ddd;
  background:#fff;
  cursor:pointer;
}
.pager button:disabled { opacity:0.5; cursor:not-allowed; }

/* Cards pagination (portfolios) */
.cards-pagination {
  margin-top: 18px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
}
.cards-pagination button {
  padding:10px 14px;
  border-radius:12px;
  border:none;
  background:#fff;
  cursor:pointer;
  box-shadow: 0 6px 16px rgba(6,6,10,0.12);
}
.cards-pagination span {
  color:#fff;
  font-weight:700;
  letter-spacing: 1px;
}

/* Portfolio section (cards) */
.portfolio-section { margin-top: 12px; }
.main-wrap {
  display:grid;
  grid-template-columns: 220px 1fr;
  gap: 24px;
  align-items:start;
}
.left-sidebar {
  border-radius: 18px;
  padding: 14px;
  background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
  box-shadow: 0 20px 60px rgba(6,6,10,0.06);
  height: calc(100vh - 360px);
  overflow: hidden;
}
.sidebar-scroll { height:100%; overflow:auto; padding-right:8px; }
.sidebar-item {
  display:flex;
  align-items:center;
  gap:10px;
  padding: 12px;
  margin-bottom: 12px;
  color: rgba(255,255,255,0.9);
  opacity: 0.92;
  border-radius: 12px;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.15s ease;
}
.sidebar-item.active, .sidebar-item:hover {
  background: rgba(255,255,255,0.12);
  transform: translateY(-2px);
}
.si-text { flex:1; }
.si-count { font-weight:700; }

.cards-panel {
  border-radius: 26px;
  background: linear-gradient(180deg, rgba(90,25,78,0.85), rgba(187,135,180,0.15));
  padding: 24px;
  box-shadow: 0 30px 80px rgba(6,6,10,0.12);
}
.cards-inner {
  background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.04));
  padding: 18px;
  border-radius: 18px;
}
.cards-grid {
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  padding: 18px;
  background: #50145c;
  border-radius: 20px;
  box-shadow: 0 30px 80px rgba(6,6,10,0.12);
}
.card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 16px 40px rgba(6,6,10,0.08);
  display:flex;
  flex-direction:column;
  min-height: 260px;
  position: relative;
}
.card-thumb {
  height: 32px;
  background: transparent;
  display:flex;
  justify-content:flex-end;
  align-items:flex-start;
  padding: 8px;
  position: relative;
}
.dot {
  width: 8px;
  height: 8px;
  background: #d9d9d9;
  border-radius: 999px;
}
.delete-btn {
  position:absolute;
  top:6px;
  right:6px;
  background:#ef4444;
  color:#fff;
  border:none;
  width:26px;
  height:26px;
  border-radius:999px;
  cursor:pointer;
  box-shadow: 0 10px 20px rgba(239,68,68,0.25);
}
.card-footer {
  display:flex;
  align-items:center;
  gap:12px;
  padding: 12px 16px 0 16px;
}
.card-avatar {
  width:44px; height:44px; border-radius:999px; background:#5e1f62; color:#fff;
  display:flex; align-items:center; justify-content:center; font-weight:700; border:3px solid #fff;
}
.card-texts .card-name { font-weight:700; color:#111; }
.card-sub { font-size:13px; color:#777; margin-top:4px; }
.card-icon { margin-left:auto; color:#bbb; }

.card-body { padding: 10px 16px 16px 16px; display:flex; flex-direction:column; gap:8px; }
.badge {
  display:inline-block;
  background:#f3e8ff;
  color:#5e1f62;
  padding:6px 10px;
  border-radius:999px;
  font-size:12px;
  font-weight:700;
}
.desc { color:#555; font-size:13px; }
.skills { display:flex; flex-wrap:wrap; gap:6px; }
.skills span {
  background:#f5f5f5;
  color:#333;
  padding:4px 8px;
  border-radius:10px;
  font-size:12px;
}
.skills .more { color:#777; }
.btn.full { width:100%; text-align:center; }

.empty-state { margin-top:16px; color:#fff; text-align:center; }

/* Footer */
.page-footer {
  margin-top: 48px;
  background: #50145c;
  color:#fff;
  padding: 48px 0;
}
.footer-inner {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  flex-direction:column;
  gap:28px;
  align-items:center;
}
.contact { align-self:flex-start; }
.contact h3 { margin:0 0 6px 0; font-weight:700; }
.contact a { color:#fff; text-decoration: underline; }
.footer-logos {
  width:100%;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:36px;
  padding-top: 8px;
}
.porto { font-weight:700; font-size:20px; text-align:center; }
.x { font-size:34px; color:#fff; transform:translateY(6px); }
.uni img { height:48px; margin-right:12px; }
.copy { border-top:1px solid rgba(255,255,255,0.2); padding-top:12px; color:rgba(255,255,255,0.8); width:100%; text-align:center; }

/* responsive */
@media (max-width: 1000px) {
  .cards-grid { grid-template-columns: repeat(2, 1fr); }
  .main-wrap { grid-template-columns: 160px 1fr; }
}
@media (max-width: 720px) {
  .nav-bar { padding: 8px 14px; height:58px; }
  .main-wrap { grid-template-columns: 1fr; }
  .left-sidebar { display:none; }
  .cards-grid { grid-template-columns: 1fr; }
  .search-inner { flex-direction:column; gap:12px; align-items:stretch; }
}
</style>
