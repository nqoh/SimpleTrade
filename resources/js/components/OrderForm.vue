<template>
     <div class="fixed bg-white shadow-md max-h-[510px] p-4 mt-4  overflow-y-auto ml-5 rounded w-96  ">
    <form @submit.prevent="submit">
      <div class="mb-2">
         <h1 class="title">Place Order</h1>
        </div>
          <span v-if="OrderStore.error" class="text-red-500 flex justify-center"> {{ OrderStore.error }}</span>
        <div class="mt-4">
        <select v-model="OrderForm.symbol" class="input" >
        <option>BTC</option>
        <option>ETH</option>
        </select>
       </div>

      <select v-model="OrderForm.side" class="input">
        <option value="buy">Buy</option>
        <option value="sell">Sell</option>
      </select>
  
      <input v-model="OrderForm.price" type="number" placeholder="Price" class="input" />
      <input v-model="OrderForm.amount" type="number" placeholder="Amount" class="input" />
  
      <button class="btn-primary mt-5 w-full cursor-pointer">Place Order</button>
     </form>

     <div class="flex justify-center mt-2 " >
     <strong class="inline-block">BALANCE</strong>
     </div>

<div class="flex justify-center mt-2 " >
    <div class="space-x-4">
     <small class="inline-block"> <b>USDT</b> : {{ parseInt(OrderStore.usdBalance).toFixed(2) }}</small>
      <template v-for="balance in OrderStore.assetBalance" :key="balance.symbol">
         <small><b>{{ balance.symbol }}</b> : {{ parseInt(balance.amount).toFixed(2) }}</small>
      </template>
    </div>
</div>


    </div>
   
  </template>
  
  <script setup>
import { reactive} from 'vue';
import { useOrderStore } from '../store/OrderStore';

const OrderForm = reactive({
    symbol: 'BTC',
    side : 'buy',
    price : '',
    amount: '',
    status: 1
})

const OrderStore = useOrderStore();
OrderStore.getBalance();

const submit = async () => {
  await OrderStore.SubmitOrder(OrderForm)
  OrderForm.amount = 0
  OrderForm.price = 0
};
</script>
