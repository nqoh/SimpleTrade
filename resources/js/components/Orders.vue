<template>
      <div class="table-wrapper">
        <select v-model="side" @change="OrderStore.side = side" class="input mb-2">
        <option value="BTC">BTC</option>
        <option value="ETH">ETH</option>
      </select>
        <div class="table-container">
     
          <table>
            <thead>
              <tr>
               <th>symbol</th>
               <th>side</th>
               <th>price</th>
               <th>amount</th>
               <th>status</th>
               <th>cancel</th>
              </tr>
           </thead>
           <tbody>
           <tr v-for="order in OrderStore.Orders" :key="order.id" >
           <td>{{ order.symbol }}</td>
           <td>{{ order.side }}</td>
           <td>{{parseInt(order.price).toFixed(2)}} </td>
           <td>{{parseInt(order.amount).toFixed(2) }}</td>
           <td>{{ order.status == 1 ?  'Open' : order.status == 2 ? "Filled" : "Cancelled"  }}</td>
           <td class="flex justify-center" @click="cancelOrder(order.id)">
             <img v-if="order.user_id == userID && order.status != 2" src="../../assets/images/bin.png" class=" w-5 cursor-pointer" />
           </td>
           </tr>
           </tbody>
        </table>
       </div>
    </div>
</template>

<script setup >
import { ref, watch } from 'vue';
import { useOrderStore } from '../store/OrderStore';

const side = ref('BTC')
const OrderStore = useOrderStore();

const userID = ref(localStorage.getItem('userID'))

watch(side,async ()=>{
   await OrderStore.getOrders();
});

const cancelOrder = async (id)=>{
    await OrderStore.cancelOrder(id);
}
</script>

<style scoped>

</style>