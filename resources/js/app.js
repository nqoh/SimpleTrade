import './bootstrap';
import './echo';
import { createApp  } from 'vue';
import App from './Pages/App.vue';
import { router } from './routes';
import { createPinia } from 'pinia';
const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app');
