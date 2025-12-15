<template>
    <div>
        <form @submit.prevent="submitLogin" class="form mt-10">
            <p class="text-2xl font-medium m-auto">
                <span class="text-indigo-500">login</span>
            </p>
            <span v-if="authStore.error" class="text-red-500 text-sm">{{ authStore.error }}</span>
            <div class="w-full ">
                <p>Email</p>
                <input v-model="form.email" placeholder="Email here" class="input" type="email" required />
            </div>
            <div className="w-full ">
                <p>Password</p>
                <input v-model="form.password"  placeholder="Password here" class="input" type="password" required />
            </div>
            <button class="btn-primary w-full mt-4" type="submit">
                 <span >Login</span>
                 <!-- <sapn v-else>Please Wait...</sapn> -->
            </button>
        </form>
    </div>
</template>

<script setup >
  import  { useAuthStore } from '../../store/AuthStore';
  import { reactive, onMounted } from 'vue';
  import { useRouter } from 'vue-router';

  const authStore = useAuthStore();

  const form  = reactive({
     email: '',
     password: ''
   })

 
  const router = useRouter();

  const submitLogin = async () => {
   await authStore.Login(form, router)
}


onMounted(() => {
  if (localStorage.getItem('Token')) {
     router.push({ name: 'dashboard' })
  }
});
</script>

<style scoped>

</style>