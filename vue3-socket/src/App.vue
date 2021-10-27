<template>
  <div>
    <div class="product-card bg-yellow-300 items-center h-screen p-10">
      <div class="flex justify-center p-5 text-gray-700 text-center">
        <h1 class="text-3xl font-bold">
          {{ msg }}
        </h1>
      </div>
      <div class="flex justify-center p-5">
        <product-card
          v-if="price !== 'undefined'"
          :price="price"
          :priceList="priceList"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import productCard from "./components/ProductCard.vue";
import axios from "axios";

export default defineComponent({
  name: "App",
  components: { productCard },
  setup() {
    const msg = ref("Simple realtime bid with vue + socket.io");
    const price = ref(0);
    const priceList = ref(null);
    // const kelipatan = ref(1000000);

    const getPriceList = async () => {
      await axios.get("http://127.0.0.1:8000/api/priceList").then(response => {
        console.log(response.data);
        priceList.value = response.data.data;

        if (response.data.data.length > 0) {
          price.value = response.data.data[0].price;
        }
      });
    };

    onMounted(() => {
      getPriceList();
      // getLastPrice();

      window.Echo.channel("BidChannel").listen(".BidPrice", function(e) {
        priceList.value = e.data;
        price.value = e.lastPrice.price;
        console.log(e);
      });
    });

    return {
      msg,
      price,
      priceList
    };
  }
});
</script>
