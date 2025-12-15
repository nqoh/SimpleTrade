<template>
    <div class="table-wrapper">
        <strong class="title mt-4 ">Trade Hisory</strong>
      <div class="table-container">
        <table>
          <thead>
            <tr>
             <th>BuyerID</th>
             <th>SellerID</th>
             <th>symbol</th>
             <th>price</th>
             <th>amount</th>
             <th>fee</th>
            </tr>
         </thead>
         <tbody>
         <tr v-for="trade in OrderStore.trades" :key="trade.id" >
         <td>{{ trade.buy_order_id }}</td>
         <td>{{ trade.sell_order_id }}</td>
         <td>{{ trade.symbol }}</td>
         <td>{{parseInt(trade.price).toFixed(2)}} </td>
         <td>{{parseInt(trade.amount).toFixed(2) }}</td>
         <td>{{parseInt(trade.fee).toFixed(2) }}</td>
         </tr>
         </tbody>
      </table>
     </div>
  </div>
</template>

<script setup >
import { onMounted } from 'vue';
import { useOrderStore } from '../store/OrderStore';

const OrderStore = useOrderStore();
onMounted(()=>{
    OrderStore.trades
})

</script>

<style scoped>

</style>