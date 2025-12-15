import { createRouter , createWebHistory } from 'vue-router'

 const routes = [
    {
        path: '/',
        name : 'login',
        component: ()=>import('../Pages/auth/login.vue')
        
    },
    {
        path: '/dashboard',
        name : 'dashboard',
        component: ()=>import('../Pages/Dashboard.vue'),
        meta: { requiresAuth: true }
    },
]




export const router = createRouter({
     routes,
     history:createWebHistory()
})