<!-- src/pages/Home.vue -->
<template>
  <div class="home-gradient text-white min-h-screen">
    <!-- NAVBAR -->
    <header class="nav-wrap">
      <div class="nav-bar">
        <div class="nav-left">
          <span class="brand">Porto Connect</span>
          <img src="@/assets/logo-soegija.png" alt="soegija" class="nav-logo" />
        </div>

        <div class="nav-center">
          <router-link to="/" class="nav-link active">Home</router-link>
            <router-link 
            v-if="!currentUser || currentUser.role === 'mahasiswa'"
            to="/explore"
            class="nav-link"
          >
            Portofolio
            </router-link>
          </div>

        <div class="nav-right">
          <template v-if="currentUser">
            <template v-if="currentUser.role === 'mahasiswa'">
              <router-link to="/profile/mahasiswa" class="user-name">Dashboard</router-link>
              <span class="sep">|</span>
              <button class="user-name logout" @click="handleLogout">Logout</button>
            </template>
            <template v-else-if="currentUser.role === 'perusahaan'">
              <router-link to="/dashboard/perusahaan" class="user-name">Dashboard</router-link>
              <span class="sep">|</span>
              <button class="user-name logout" @click="handleLogout">Logout</button>
            </template>
            <template v-else>
              <router-link to="/dashboard/admin" class="user-name">Dashboard</router-link>
              <span class="sep">|</span>
              <button class="user-name logout" @click="handleLogout">Logout</button>
            </template>
          </template>
          <template v-else>
            <router-link to="/login" class="user-name">Sign Up | Login</router-link>
          </template>
        </div>
      </div>
    </header>

    <!-- HERO -->
    <main class="pt-32 pb-32 min-h-screen flex items-center relative main-section-curved">
      <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-16 px-6">
        <div class="text-left">
          <h1 class="hero-title text-5xl md:text-6xl font-poppins mb-6 leading-tight">
            Showcase Your Potential.
            <span class="text-white">Connect with Top Employers.</span>
          </h1>
          <blockquote class="text-lg text-white/80 italic mb-4 font-roboto">
            "I'm convinced that about half of what separates successful entrepreneurs from the non-successful ones is pure perseverance."
          </blockquote>
          <p class="text-white mb-8 font-roboto">Steve Jobs (Co-founder Apple)</p>
          <button @click="scrollToTeams" class="bg-white text-black font-semibold py-2 px-8 rounded-full shadow-lg hover:bg-gray-200 transition">About</button>
        </div>

        <div class="flex justify-center">
          <img src="@/assets/logo-s-besar.png" alt="Logo S" class="w-64 h-64 md:w-96 md:h-96" />
        </div>
      </div>
    </main>

    <!-- FOLDER GRIP (3 folder shapes) -->
    <FolderGrip />

    <!-- TEAMS -->
    <section id="our-teams" class="py-24 bg-white text-black">
      <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-poppins text-purple-800 mb-8">"If you want to go fast, go alone. If you want to go far, go together."</h2>
        <button class="bg-white text-black font-semibold py-2 px-8 rounded-full border-2 border-black hover:bg-gray-100 transition mb-16">Our Teams</button>

        <!-- FOTO DI DALAM LINGKARAN -->
        <div class="flex justify-center items-center gap-8">
          <div class="circle-img border-4 border-purple-800">
            <img src="@/assets/team1.jpg" alt="Team 1" />
          </div>

          <div class="circle-img border-4 border-yellow-400">
            <img src="@/assets/team2.jpg" alt="Team 2" />
          </div>

          <div class="circle-img border-4 border-black">
            <img src="@/assets/team3.jpg" alt="Team 3" />
          </div>

          <div class="circle-img border-4 border-yellow-400">
            <img src="@/assets/team4.jpg" alt="Team 4" />
          </div>

          <div class="circle-img border-4 border-purple-800">
            <img src="@/assets/team5.jpg" alt="Team 5" />
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#50145c] text-white py-16 font-roboto">
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
/* multi-word name to satisfy lint rules */
defineOptions({ name: 'HomePage' })

import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import FolderGrip from '@/components/FolderGrip.vue'
import { logger } from '@/utils/logger'

const router = useRouter()
const currentUser = ref(null)

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    try {
      const res = await axios.get('/api/me')
      currentUser.value = res.data.user
    } catch (err) {
      logger.warn('Error fetching /api/me:', err)
    }
  }
})

const scrollToTeams = () => {
  const el = document.getElementById('our-teams')
  if (el) el.scrollIntoView({ behavior: 'smooth' })
}

const handleLogout = async () => {
  try {
    await axios.post('/api/logout')
  } catch (err) {
    logger.warn('Logout error:', err)
  } finally {
    try { localStorage.removeItem('token') } catch (e) { logger.warn('localStorage remove error', e) }
    delete axios.defaults.headers.common['Authorization']
    currentUser.value = null
    router.push('/')
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;900&family=Roboto:wght@400&display=swap');
.font-poppins { font-family: 'Poppins', sans-serif; }
.font-roboto { font-family: 'Roboto', sans-serif; }

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
.sep { color:#999; }
.logout { border:none; background:transparent; cursor:pointer; }

/* HERO */
.hero-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 900;
  font-size: clamp(48px, 8.5vw, 70px);
  line-height: 0.94;
  letter-spacing: -1px;
  -webkit-text-fill-color: #50145C;
  color: #50145C;
  -webkit-text-stroke: 4px #ffffff;
}

/* PAGE BG */
.home-gradient {
  background: radial-gradient(
    ellipse 160% 120% at 50% -55%,
    #000000 48%,
    #50145C 60%,
    #ffffff 80%
  );
}

/* curved section */
.main-section-curved {
  border-bottom-left-radius: 150px;
  border-bottom-right-radius: 150px;
  overflow: hidden;
  margin-bottom: -80px;
  padding-bottom: 100px;
  position: relative;
  z-index: 1;
}

/* circle images (team) */
.circle-img {
  width: 120px;
  height: 120px;
  border-radius: 999px;
  overflow: hidden;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.circle-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* responsive tweaks */
@media (min-width: 768px) {
  .circle-img { width: 140px; height: 140px; }
  .hero-title { -webkit-text-stroke: 5px #ffffff; }
}

@media (max-width: 640px) {
  .circle-img { width: 88px; height: 88px; }
  .hero-title { font-size: 36px; -webkit-text-stroke: 3px #ffffff; }
}
</style>

