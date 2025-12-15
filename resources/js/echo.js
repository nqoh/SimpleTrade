import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher
const token = localStorage.getItem('Token');
window.Echo =  new Echo({
    broadcaster : 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ['ws','wss'],
    withCredentials:true,
    auth: {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    },
})



// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// const token = localStorage.getItem('Token');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_WS_HOST,
//     wsPort: import.meta.env.VITE_REVERB_WS_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_WSS_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
//     auth: {
//         headers: {
//             Authorization: `Bearer ${token}`,
//         },
//     },
// });