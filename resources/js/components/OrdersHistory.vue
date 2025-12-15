<template>
    <div class="table-wrapper">
        <strong class="title mt-4 ">Orders Hisory</strong>
      <div class="table-container">

        <table>
          <thead>
            <tr>
             <th>symbol</th>
             <th>side</th>
             <th>price</th>
             <th>amount</th>
             <th>status</th>
            </tr>
         </thead>
         <tbody>
         <tr v-for="order in OrderStore.OrdersHistory" :key="order.id" >
         <td>{{ order.symbol }}</td>
         <td>{{ order.side }}</td>
         <td>{{parseInt(order.price).toFixed(2)}} </td>
         <td>{{parseInt(order.amount).toFixed(2) }}</td>
         <td>{{ order.status == 1 ?  'Open' : order.status == 2 ? "Filled" : "Cancelled"  }}</td>
         </tr>
         </tbody>
      </table>


     </div>
  </div>
</template>

<script setup >
import { watch } from 'vue';
 import { useOrderStore } from '../store/OrderStore';


const OrderStore = useOrderStore();

watch(OrderStore.Orders,async ()=>{
 await OrderStore.getOrders();
});


</script>

<style scoped>

</style>