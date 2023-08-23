import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'Accueil',
            component: HomeView
        },
        {
            path: '/collection',
            name: 'Collection',
            component: () => import('../views/CollectionView.vue')
        },
        {
            path: '/recompense',
            name: 'Recompense',
            component: () => import('../views/404View.vue')
        },
        {
            path: '/code',
            name: 'Entrer-code',
            component: () => import('../views/404View.vue')
        },
        {
            path: '/login',
            name: 'Connexion',
            component: () => import('../views/LoginView.vue')
        },
        {
            path: '/404',
            component: () => import('../views/404View.vue')
        },
        {
            path: '/:catchAll(.*)', 
            redirect: '/404'
        },
    ]
})

export default router