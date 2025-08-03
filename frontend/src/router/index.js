import { createRouter, createWebHistory } from 'vue-router'
import AppointmentForm from '../views/appointment/ApointmentForm.vue'
import AppointmentSuccess from '../views/appointment/ApointmentSuccess.vue'
import AdminLogin from '../views/admin/AdminLogin.vue'
import AdminDashboard from '../views/admin/AdminDashboard.vue'
import AppointmentDetails from '../views/appointment/AppointmentDetails.vue'
import Materials from '../views/material/Materials.vue'

// Stores
import { useAppointmentStore } from '@/store/useAppointmentStore'
import { useAuthStore } from '@/store/useAuthStore'

// Guard para proteger /success
const successGuard = (to, from, next) => {
    const appointmentStore = useAppointmentStore()

    if (appointmentStore.lastSuccess) {
        next()
    } else {
        next('/login')
    }
}

const routes = [
    {
        path: '/',
        component: AppointmentForm
    },
    {
        path: '/success',
        component: AppointmentSuccess,
        beforeEnter: successGuard
    },
    {
        path: '/login',
        component: AdminLogin
    },
    {
        path: '/admin',
        component: AdminDashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/appointments/:id',
        component: AppointmentDetails,
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/materials',
        component: Materials,
        meta: { requiresAuth: true }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore()

    if (auth.isLoading) {
        await auth.initializeAuth()
    }

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        next('/login')
    } else if (to.path === '/login' && auth.isAuthenticated) {
        next('/admin')
    } else {
        next()
    }
})

export default router