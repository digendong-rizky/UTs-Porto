<template>
  <div class="min-h-screen dashboard-gradient flex flex-col">
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow pt-12">
      <div class="flex gap-6">
        <!-- Sidebar Kategori -->
        <aside class="w-64 bg-white rounded-lg shadow-sm p-4 h-fit mt-8">
          <h3 class="font-bold text-gray-800 mb-4">Kategori Bidang</h3>
          <ul class="space-y-2">
            <li>
              <button
                @click="filterByBidangPortfolio(null)"
                :class="selectedBidangPortfolio === null ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Semua Bidang
                <span class="ml-auto text-sm text-gray-500">({{ totalPortfoliosPortfolio }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('backend')"
                :class="selectedBidangPortfolio === 'backend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Backend
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('backend') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('frontend')"
                :class="selectedBidangPortfolio === 'frontend' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Frontend
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('frontend') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('fullstack')"
                :class="selectedBidangPortfolio === 'fullstack' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Fullstack
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('fullstack') }})</span>
              </button>
            </li>
            <li>
              <button
                @click="filterByBidangPortfolio('QATester')"
                :class="selectedBidangPortfolio === 'QATester' ? 'bg-purple-100 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-100'"
                class="w-full text-left px-4 py-2 rounded-lg flex items-center gap-2 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                QA Tester
                <span class="ml-auto text-sm text-gray-500">({{ getCountByBidangPortfolio('QATester') }})</span>
              </button>
            </li>
          </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 mt-8">
              <h2 class="text-xl font-bold mb-4">Cari Mahasiswa</h2>
              <form @submit.prevent="searchStudents" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                  <input v-model="searchForm.keyword" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="Nama, NIM, dll" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Skill</label>
                  <input v-model="searchForm.skill" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="JavaScript, Python, dll" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                  <input v-model="searchForm.jurusan" type="text" class="w-full border rounded-lg px-3 py-2" placeholder="Teknik Informatika" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                  <input v-model="searchForm.fakultas" type="text" class="w-full border rounded-lg px-3 py-2" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                  <input v-model="searchForm.universitas" type="text" class="w-full border rounded-lg px-3 py-2" />
                </div>
                <div class="flex items-end">
                  <button type="submit" class="w-full bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-800">
                    Cari
                  </button>
                </div>
              </form>
            </div>

            <h2 class="text-3xl font-bold text-white mb-6">Jelajahi Portofolio Mahasiswa</h2>
            
            <!-- Portfolio Grid -->
            <div class="cards-grid">
              <article
                v-for="portfolio in paginatedPortfolios"
                :key="portfolio.id"
                class="card"
                @click="viewPortfolio(portfolio)"
              >
                <div class="card-thumb">
                  <span class="dot"></span>
                </div>
                <div class="card-footer">
                  <div class="card-avatar">
                    <span>{{ (portfolio.mahasiswa?.user?.name || 'U').charAt(0) }}</span>
                  </div>
                  <div class="card-texts">
                    <div class="card-name">{{ portfolio.mahasiswa?.user?.name || portfolio.nama }}</div>
                    <div class="card-sub">{{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}</div>
                  </div>
                  <div class="card-icon">●</div>
                </div>
                <div class="card-body">
                  <div v-if="portfolio.bidang" class="badge">{{ portfolio.bidang }}</div>
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

            <div v-if="filteredPortfoliosPortfolio.length === 0" class="text-center text-gray-500 py-12">
              <p>Tidak ada portofolio yang ditemukan</p>
            </div>

            <!-- Pagination -->
            <div v-if="filteredPortfoliosPortfolio.length > 6" class="mt-6 flex justify-center items-center gap-2">
              <button 
                @click="currentPage = Math.max(1, currentPage - 1)"
                :disabled="currentPage === 1"
                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                class="px-4 py-2 border rounded-lg bg-white text-gray-700"
              >
                ←
              </button>
              <span class="px-4 py-2 text-gray-700 bg-white border rounded-lg text-sm">
                {{ currentPage }} / {{ totalPages }}
              </span>
              <button 
                @click="currentPage = Math.min(totalPages, currentPage + 1)"
                :disabled="currentPage === totalPages"
                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                class="px-4 py-2 border rounded-lg bg-white text-gray-700"
              >
                →
              </button>
            </div>
          </main>
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-[#50145c] text-white py-16 font-roboto mt-auto">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-8">
          <!-- Informasi Kontak di kiri -->
          <div>
            <h3 class="text-2xl md:text-3xl font-bold font-poppins mb-4">Informasi Kontak</h3>
            <ul class="space-y-2 text-gray-300">
              <li>Email : <a href="mailto:unika@unika.ac.id" class="hover:text-purple-300 transition">unika@unika.ac.id</a></li>
              <li>Hotline : (024) 850 5003</li>
              <li>WhatsApp Official : <a href="https://wa.me/6281232345479" class="hover:text-purple-300 transition">08123 2345 479</a></li>
            </ul>
          </div>
        </div>

        <!-- Logo bar di tengah bawah -->
        <div class="flex items-center justify-center gap-4 mb-8">
          <div class="flex flex-col text-3xl font-poppins text-white">
            <span>Porto</span>
            <span>Connect</span>
          </div>
          <span class="text-3xl text-white">×</span>
          <div class="flex items-center gap-2">
            <img src="@/assets/logo-soegija-putih.png" alt="Logo SCU" class="h-16" />
          </div>
        </div>

        <!-- Copyright di bawah -->
        <div class="border-t border-purple-800 pt-8 text-center text-gray-500 text-sm">
          © 2025 PortoConnect. All rights reserved.
        </div>
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

const { showSuccess, showError, showWarning } = useSweetAlert()

const router = useRouter()
const currentUser = ref(null)

// Portofolio Tab Data
const portfoliosPortfolio = ref([])
const allPortfoliosPortfolio = ref([])
const selectedBidangPortfolio = ref(null)
const currentPage = ref(1)
const itemsPerPage = 6

// Search Data
const searchResults = ref([])
const searchForm = ref({
  keyword: '',
  skill: '',
  jurusan: '',
  fakultas: '',
  universitas: ''
})

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }
  
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  
  // Validasi role - pastikan user adalah perusahaan
  try {
    const res = await axios.get('/api/me')
    const user = res.data?.user
    
    if (!user || user.role !== 'perusahaan') {
      // Redirect berdasarkan role
      if (user?.role === 'admin') {
        router.push('/dashboard/admin')
      } else if (user?.role === 'mahasiswa') {
        router.push('/profile/mahasiswa')
      } else {
        router.push('/dashboard')
      }
      return
    }
    
    // Jika user adalah perusahaan, lanjutkan load data
    currentUser.value = user
    await loadPortfoliosPortfolio()
  } catch (error) {
    logger.error('Error validating company access:', error)
    if (error.response?.status === 401 || error.response?.status === 403) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      showError('Gagal memuat data. Silakan refresh halaman.')
    }
  }
})

// Portofolio Tab Functions
const loadPortfoliosPortfolio = async (bidang = null) => {
  try {
    const params = { per_page: 30 }
    if (bidang) params.bidang = bidang

    const response = await axios.get('/api/portfolios/public', { params })
    const list = response.data?.portfolios || response.data?.data || []

    allPortfoliosPortfolio.value = list
    portfoliosPortfolio.value = list
    currentPage.value = 1
  } catch (error) {
    logger.error('Error loading portfolios:', error)
  }
}

const filterByBidangPortfolio = async (bidang) => {
  selectedBidangPortfolio.value = bidang
  currentPage.value = 1 // Reset to first page when filtering
  
  // If there are search results, filter them by bidang
  if (searchResults.value.length > 0) {
    const studentIds = searchResults.value.map(s => s.id)
    const params = { per_page: 30 }
    if (bidang) params.bidang = bidang

    const response = await axios.get('/api/portfolios/public', { params })
    const source = response.data?.portfolios || response.data?.data || []

    allPortfoliosPortfolio.value = source
    portfoliosPortfolio.value = source.filter(p => 
      studentIds.includes(p.mahasiswa_id)
    )
  } else {
    // If no search results, just load all portfolios with bidang filter
    await loadPortfoliosPortfolio(bidang)
  }
}

const filteredPortfoliosPortfolio = computed(() => {
  return portfoliosPortfolio.value
})

const totalPortfoliosPortfolio = computed(() => {
  return allPortfoliosPortfolio.value.length
})

// Pagination computed
const totalPages = computed(() => {
  return Math.ceil(filteredPortfoliosPortfolio.value.length / itemsPerPage)
})

const paginatedPortfolios = computed(() => {
  if (filteredPortfoliosPortfolio.value.length <= itemsPerPage) {
    return filteredPortfoliosPortfolio.value
  }
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredPortfoliosPortfolio.value.slice(start, end)
})

const getCountByBidangPortfolio = (bidang) => {
  return allPortfoliosPortfolio.value.filter(p => p.bidang === bidang).length
}

// Search Functions
const searchStudents = async () => {
  try {
    // Check if at least one search field is filled
    const hasSearchCriteria = searchForm.value.keyword || 
                              searchForm.value.skill || 
                              searchForm.value.jurusan || 
                              searchForm.value.fakultas || 
                              searchForm.value.universitas
    
    if (!hasSearchCriteria) {
      showWarning('Silakan isi minimal satu field pencarian')
      return
    }

    logger.debug('Searching with params:', searchForm.value)

    const res = await axios.get('/api/company/search', { params: searchForm.value })
    
    logger.debug('Search API response:', res.data)
    
    // Handle pagination response - Laravel pagination returns data in 'data' key
    const students = res.data.data || []
    searchResults.value = students
    
    logger.debug('Found students:', students.length)
    
    // Get all portfolios from searched students
    if (students.length > 0) {
      const studentIds = students.map(s => s.id)
      logger.debug('Student IDs:', studentIds)
      
      // Load portfolios for these students with bidang filter
      const params = selectedBidangPortfolio.value ? { bidang: selectedBidangPortfolio.value } : {}
      const allPortfoliosRes = await axios.get('/api/portfolios/public', { params })
      const allPortfoliosList = allPortfoliosRes.data.portfolios || []
      
      logger.debug('All portfolios:', allPortfoliosList.length)
      logger.debug('Portfolio mahasiswa_ids:', allPortfoliosList.map(p => p.mahasiswa_id))
      
      // Filter portfolios to show only from searched students
      let filteredPortfolios = allPortfoliosList.filter(p => 
        studentIds.includes(p.mahasiswa_id)
      )
      
      // Additional filter: if keyword is searched, filter portfolios by keyword (nama, deskripsi, bidang)
      if (searchForm.value.keyword && searchForm.value.keyword.trim() !== '') {
        const searchKeyword = searchForm.value.keyword.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioNama = (portfolio.nama || '').toLowerCase()
          const portfolioDeskripsi = (portfolio.deskripsi || '').toLowerCase()
          const portfolioBidang = (portfolio.bidang || '').toLowerCase()
          const mahasiswaName = (portfolio.mahasiswa?.user?.name || '').toLowerCase()
          const mahasiswaNim = (portfolio.mahasiswa?.nim || '').toLowerCase()
          
          return portfolioNama.includes(searchKeyword) ||
                 portfolioDeskripsi.includes(searchKeyword) ||
                 portfolioBidang.includes(searchKeyword) ||
                 mahasiswaName.includes(searchKeyword) ||
                 mahasiswaNim.includes(searchKeyword)
        })
        logger.debug('After keyword filter:', filteredPortfolios.length)
      }
      
      // Additional filter: if skill is searched, filter portfolios by skill
      if (searchForm.value.skill && searchForm.value.skill.trim() !== '') {
        const searchSkill = searchForm.value.skill.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          // Check if portfolio has skills loaded
          if (portfolio.skills && portfolio.skills.length > 0) {
            return portfolio.skills.some(skill => 
              skill.nama && skill.nama.toLowerCase().includes(searchSkill)
            )
          }
          return false
        })
        logger.debug('After skill filter:', filteredPortfolios.length)
      }
      
      // Additional filter: if jurusan is searched, filter portfolios by jurusan
      if (searchForm.value.jurusan && searchForm.value.jurusan.trim() !== '') {
        const searchJurusan = searchForm.value.jurusan.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioJurusan = (portfolio.mahasiswa?.jurusan || '').toLowerCase()
          return portfolioJurusan.includes(searchJurusan)
        })
        logger.debug('After jurusan filter:', filteredPortfolios.length)
      }
      
      // Additional filter: if fakultas is searched, filter portfolios by fakultas
      if (searchForm.value.fakultas && searchForm.value.fakultas.trim() !== '') {
        const searchFakultas = searchForm.value.fakultas.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioFakultas = (portfolio.mahasiswa?.fakultas || '').toLowerCase()
          return portfolioFakultas.includes(searchFakultas)
        })
        logger.debug('After fakultas filter:', filteredPortfolios.length)
      }
      
      // Additional filter: if universitas is searched, filter portfolios by universitas
      if (searchForm.value.universitas && searchForm.value.universitas.trim() !== '') {
        const searchUniversitas = searchForm.value.universitas.toLowerCase().trim()
        filteredPortfolios = filteredPortfolios.filter(portfolio => {
          const portfolioUniversitas = (portfolio.mahasiswa?.universitas || '').toLowerCase()
          return portfolioUniversitas.includes(searchUniversitas)
        })
        logger.debug('After universitas filter:', filteredPortfolios.length)
      }
      
      portfoliosPortfolio.value = filteredPortfolios
      
      logger.debug('Filtered portfolios:', portfoliosPortfolio.value.length)
      
      if (portfoliosPortfolio.value.length === 0) {
        showWarning(`Ditemukan ${students.length} mahasiswa, tapi tidak ada portofolio yang sesuai dengan kriteria pencarian`)
      }
    } else {
      // If no search results, reload with current bidang filter
      await loadPortfoliosPortfolio(selectedBidangPortfolio.value)
      showWarning('Tidak ada mahasiswa yang ditemukan dengan kriteria pencarian tersebut')
    }
  } catch (error) {
    logger.error('Error searching students:', error)
    logger.debug('Error response:', error.response)
    
    const errorMessage = error.response?.data?.message || 
                        error.response?.data?.errors || 
                        'Gagal melakukan pencarian. Silakan coba lagi.'
    
    if (typeof errorMessage === 'object') {
      const errorList = Object.values(errorMessage).flat().join(', ')
      showError(`Error: ${errorList}`)
    } else {
      showError(`Error: ${errorMessage}`)
    }
    
    // If error, reload all portfolios
    await loadPortfoliosPortfolio(selectedBidangPortfolio.value)
  }
}

const viewPortfolio = (portfolio) => {
  if (portfolio.public_link) {
    router.push(`/portfolio/${portfolio.public_link}`)
  }
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
    localStorage.removeItem('token')
    router.push('/login')
  } catch (error) {
    localStorage.removeItem('token')
    router.push('/login')
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.dashboard-gradient {
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}

/* NAVBAR */
.nav-wrap { padding: 28px 0 0 0; }
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
.logout { border:none; background:transparent; cursor:pointer; }

/* Cards */
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
  cursor: pointer;
  transition: transform 0.16s ease, box-shadow 0.16s ease;
}
.card:hover { transform: translateY(-4px); box-shadow: 0 24px 48px rgba(6,6,10,0.14); }
.card-thumb {
  height: 32px;
  background: transparent;
  display:flex;
  justify-content:flex-end;
  align-items:flex-start;
  padding: 8px;
}
.dot {
  width: 8px;
  height: 8px;
  background: #d9d9d9;
  border-radius: 999px;
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
.btn { border:none; border-radius:10px; padding:10px 12px; font-weight:600; cursor:pointer; }
.btn.primary { background:#50145c; color:#fff; box-shadow: 0 6px 16px rgba(6,6,10,0.12); }

.pagination {
  margin-top: 18px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
}
.pagination button {
  padding:10px 14px;
  border-radius:12px;
  border:none;
  background:#fff;
  cursor:pointer;
  box-shadow: 0 6px 16px rgba(6,6,10,0.12);
}
.pagination span {
  color:#fff;
  font-weight:700;
  letter-spacing: 1px;
}

@media (max-width: 1000px) {
  .cards-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 720px) {
  .cards-grid { grid-template-columns: 1fr; }
}
</style>
