<template>
    <div class="grid grid-cols-3 mt-1 p-5 ">

      <div>
        <order-form />
      </div>

      <div class=" col-span-2">
        <Orders  />
        <OrdersHistory class="mt-2" />
        <!-- <Trades class="mt-2" /> -->
      </div>

    </div>

</template>

<script setup>

import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
// import Trades from '../components/Trades.vue';
import OrdersHistory from '../components/OrdersHistory.vue';
import OrderForm from '../components/OrderForm.vue';
import { useOrderStore } from '../store/OrderStore';
import Orders from '../components/Orders.vue';


const userID =  ref(localStorage.getItem('userID'))
const router = useRouter();

const OrderStore = useOrderStore()

onMounted(() => {
  
  if (!localStorage.getItem('Token')) {
     router.push({ name: 'login' })
  }
  OrderStore.getOrders();
  // OrderStore.trades();
  OrderStore.getBalance();
})

window.Echo.private('users.'+userID).listen('OrderMatched' , (event)=>{
   alert('Order Matched')
   OrderStore.getOrders();
  //  OrderStore.trades();
   OrderStore.getBalance();
});

</script>

<style scoped>

</style>