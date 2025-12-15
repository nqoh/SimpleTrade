import { defineStore  } from "pinia";
import api from '../api'


export const useAuthStore = defineStore('AuthStore',{
    
      state:()=>({
        error:'',
        user: '', 
        loading:false,
        Token : localStorage.getItem('Token'),
      }),

    actions:{
     async Login(form, router){
        if (!form.email || !form.password) {
            this.error = 'Email and password are required'
            return
          }

         this.loading = true
         this.error = ''
 
       try{
        const response = await api.post('/login', form)
            if(response.status === 200){

              localStorage.setItem('Token', response.data.Data.Token)
              localStorage.setItem('userID', response.data.Data.User.id);

              this.Token = localStorage.getItem('Token')
              this.user = response.data.Data.User
              this.loading = false
              router.push({name:'dashboard'})

             }
           }catch(errors){

              this.error = 'Invalid credential'
              this.loading = false

         }finally {
        this.loading = false
      }
     },

    async hlandleLogout(router){

      const response = await api.get('/logout',{ headers:{Authorization: 'Bearer '+ this.Token}})

      if(response.status === 204){
        localStorage.removeItem('Token')
        this.Token = localStorage.removeItem('Token')
        router.push({name:'login'})
      }

   }  
  } 
});