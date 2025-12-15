<template>
    <div class="top-0 sticky bg-slate-800 text-white py-4 overflow-hidden">
       <div class="flex items-center justify-between">
         <RouterLink to="/">
         <h1 class="uppercase font-black ml-5 text-2xl md:text-3xl  tracking-wider">Simple Trade</h1>
         </RouterLink>
         
         <div class="mr-5 space-x-4" v-if="authStore.Token">
           <button  @click="logout" class="bg-slate-600 px-4 py-2 rounded hover:bg-slate-700 transition">Logout</button>
         </div>
    </div>
   </div>
   </template>
   
   <script setup >
     import { useRouter } from 'vue-router';
     import { useAuthStore } from '../store/AuthStore';

     const router = useRouter();
     
     const authStore = useAuthStore();

      const logout = ()=>{
          authStore.hlandleLogout(router)
      }

   </script>
   
   <style scoped>
   
   </style>