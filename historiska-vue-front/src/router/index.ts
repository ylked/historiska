import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import {useUserStore} from "../stores/useUserStore.ts";

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
            component: () => import('../views/RewardView.vue')
        },
        {
            path: '/code',
            name: 'Entrer-code',
            component: () => import('../views/EnterCodeView.vue')
        },
        {
            path: '/login',
            name: 'Connexion',
            component: () => import('../views/LoginView.vue'),
        },
        {
            path: '/register',
            name: 'Inscription',
            component: () => import('../views/RegisterView.vue')
        },
        {
            path: '/logout',
            name: 'Deconnexion',
            component: () => import('../views/LoginView.vue'),
            props: { logout: true }
        },
        {
            path: '/account/',
            name: 'Compte',
            component: () => import('../views/Account.vue'),
            props: {
                title: 'Profil'
            }
        },
        {
            path: '/password/forget',
            name: 'mot-de-passe-oublie',
            component: () => import('../views/LoginView.vue'),
            props: {
                title: '',
                forget: true
            }
        },
        {
            path: '/account/activate',
            name: 'compte-activation',
            component: () => import('../views/Account.vue'),
            props: {
                accountActivation: true,
                title: 'Activer le compte'
            }
        },
        {
            path: '/account/activate/:code',
            name: 'compte-activation-lien',
            component: () => import('../views/ActivateAccountView.vue')
        },
        {
            path: '/account/recovery/:token',
            name: 'recovery-password',
            component: () => import('../views/RecoverPasswordView.vue'),
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

// Redirect when user has no token
router.beforeEach(async(to,from)=>{
    const authUser = useUserStore();
    const userValid = await authUser.isValidToken();

    // If user not login or register, unable to navigate through thesite
    if(!userValid &&
        to.name !== 'Connexion' &&
        to.path !== '/' &&
        to.name !== 'Inscription' &&
        to.name !== 'mot-de-passe-oublie' &&
        to.name !== 'compte-activation-lien') {
        authUser.reset();
        return{name:"Connexion"}
    } else if(authUser.authUser && userValid && to.name === "Connexion") {
        return{name:"Accueil"}
    }
})

export default router