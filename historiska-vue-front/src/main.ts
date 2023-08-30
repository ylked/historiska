import { createApp } from 'vue'
import router from './router'
import App from './App.vue'
import { createMetaManager } from 'vue-meta'
import { createPinia } from "pinia";

const app = createApp(App)
app.config.globalProperties.$store = {};

const pinia = createPinia();
app.use(pinia);
app.use(router)
app.use(createMetaManager())
app.mount('#app')
