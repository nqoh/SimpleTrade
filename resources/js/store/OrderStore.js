import { defineStore } from "pinia";
import api from "../api";
import { useAuthStore } from '../store/AuthStore';


const AuthStore = useAuthStore()
const initAuthToken = { headers:{Authorization: 'Bearer '+ AuthStore.Token}}

export const useOrderStore = defineStore('OrderStore',{

    state: ()=>({
        error:'',
        Orders: [],
        OrdersHistory: [],
        usdBalance: 0,
        assetBalance : [],
        trades:[],
        side:'BTC',
    }),
    
    actions:{
      async  SubmitOrder(OrderForm){
            if (!OrderForm.amount || !OrderForm.price) {
                this.error = 'All fields are required'
                return
              }
              try{
               const response = await  api.post('/orders',OrderForm,initAuthToken);
               if(response.status === 201){
                this.error = '',
                this.Orders.push(response.data.Data)
                this.getBalance();
              }
        }catch(errors){
            this.error = errors.response.data.Message
        }
    },
   
    async  getBalance(){
      try{
          const response = await  api.get('/profile',initAuthToken);
          if(response.status === 200){
              this.usdBalance = response.data.usd_balance
              this.assetBalance = response.data.assets
          } 
      }catch( errors ){

       }
    },

    //  async trades(){
    //   try{
    //     const response = await  api.get('/trades',initAuthToken);
    //     if(response.status === 200){
    //         this.trades = response.data
    //     } 
    //  }catch( errors ){

    //  }
    //  },

    async getOrders(){
      try{
          const response = await  api.get('/orders?symbol='+this.side, initAuthToken);
          if(response.status === 200){
              this.Orders = response.data.orders
              this.OrdersHistory = response.data.history
          } 
      }catch( errors ){
        console.log(errors)
       }
    },

    async cancelOrder(id){
      try{
        await  api.post('orders/'+id+'/cancel', initAuthToken);
        this.getOrders(),
        this.getBalance()
      }catch( errors ){
         console.log(errors)
     }
    }
  }
})