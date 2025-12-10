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
          <router-link to="/explore" class="nav-link">Portofolio</router-link>
        </div>

        <div class="nav-right">
          <template v-if="user">
            <button class="user-name logout" @click="handleLogout">Logout</button>
          </template>
          <template v-else>
            <router-link to="/login" class="user-name">Sign Up | Login</router-link>
          </template>
        </div>
      </div>
    </header>

    <!-- Main Content - Flex grow untuk push footer ke bawah -->
    <div class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 w-full pt-32">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel - Profile Card -->
        <div class="lg:col-span-1">
          <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-8 text-center shadow-lg">
            <div class="w-32 h-32 mx-auto mb-4 bg-purple-600 rounded-full flex items-center justify-center text-white text-5xl font-bold">
              {{ user?.name?.charAt(0) || 'U' }}
            </div>
            <h2 class="text-3xl font-bold text-black mb-2">{{ user?.name || 'Nama User' }}</h2>
            <p class="text-black text-lg">{{ mahasiswa?.nim || 'NIM' }}</p>
            <p class="text-black">{{ mahasiswa?.jurusan || 'Jurusan' }} - {{ mahasiswa?.fakultas || 'Fakultas' }}</p>
          </div>
        </div>

        <!-- Right Panel - Details -->
        <div class="lg:col-span-2">
          <div class="bg-gray-100 rounded-2xl p-6 shadow-lg">
            <!-- Bio Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Bio</h3>
              <p class="text-gray-700">{{ mahasiswa?.deskripsi_diri || 'Belum ada deskripsi' }}</p>
            </div>

            <!-- Email Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Email address</h3>
              <p class="text-gray-700">{{ user?.email || '-' }}</p>
            </div>

            <!-- Phone Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-2">Nomer HP</h3>
              <p class="text-gray-700">{{ mahasiswa?.no_telp || '-' }}</p>
            </div>

            <!-- My Document Section -->
            <div class="mb-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">My document</h3>
              <div class="flex gap-4">
                <div 
                  @click="goToPortfolioList"
                  class="w-16 h-16 border-2 border-gray-400 rounded-lg flex items-center justify-center cursor-pointer hover:border-purple-600"
                >
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div 
                  @click="goToCreatePortfolio"
                  class="w-16 h-16 border-2 border-purple-600 rounded-lg flex items-center justify-center cursor-pointer hover:border-purple-700 bg-purple-50"
                >
                  <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { logger } from '@/utils/logger'

const router = useRouter()
const user = ref(null)
const mahasiswa = ref(null)

onMounted(async () => {
  await loadData()
})

const loadData = async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }
    
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    
    // Validasi role - pastikan user adalah mahasiswa
    try {
      const meResponse = await axios.get('/api/me')
      const userRole = meResponse.data?.user?.role
      
      if (userRole !== 'mahasiswa') {
        // Redirect berdasarkan role
        if (userRole === 'admin') {
          router.push('/dashboard/admin')
        } else if (userRole === 'perusahaan') {
          router.push('/dashboard/perusahaan')
        } else {
          router.push('/dashboard')
        }
        return
      }
    } catch (roleError) {
      logger.error('Error validating mahasiswa access:', roleError)
      if (roleError.response?.status === 401 || roleError.response?.status === 403) {
        localStorage.removeItem('token')
        router.push('/login')
        return
      }
    }

    const res = await axios.get('/api/mahasiswa/profile')
    user.value = res.data.mahasiswa.user
    mahasiswa.value = res.data.mahasiswa
  } catch (error) {
    logger.error('Error loading profile:', error)
    if (error.response?.status === 401 || error.response?.status === 403) {
      localStorage.removeItem('token')
      router.push('/login')
    }
  }
}

const goToPortfolioList = () => {
  router.push('/portfolio/list')
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

const goToCreatePortfolio = () => {
  // Langsung redirect ke halaman preview dengan ID "new"
  router.push('/portfolio/preview/new')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }

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
.logout { border:none; background:transparent; cursor:pointer; }
.sep { color:#999; }

.dashboard-gradient {
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}
</style>

