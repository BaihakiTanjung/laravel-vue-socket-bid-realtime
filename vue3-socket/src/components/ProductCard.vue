<template>
  <div class="product-card">
    <div class="py-6">
      <div class="flex max-w-lg bg-white shadow-lg rounded-lg overflow-hidden">
        <div
          class="w-1/3 bg-cover"
          style="
            background-image: url('https://file.sitama.co.id/storage/steam/20210629IK02004522/FotoUploadSelling/05b71309-e078-443b-833d-d2f7a2475433.jpeg');
          "
        ></div>
        <div class="w-2/3 p-4">
          <!-- <h1 class="text-gray-900 font-bold text-2xl">{{ name }}</h1> -->
          <p class="mt-2 text-gray-600 text-sm">
            Lorem ipsum dolor sit amet consectetur adipisicing elit In odit
            exercitationem fuga id nam quia
          </p>
          <div class="flex item-center justify-between mt-3">
            <div>
              <div class="custom-number-input h-10 w-100 mr-3">
                <div
                  class="
                    flex flex-row
                    h-10
                    w-full
                    rounded-lg
                    relative
                    bg-transparent
                    mt-1s
                  "
                >
                  <button
                    @click="decrement"
                    class="
                      bg-gray-300
                      text-gray-600
                      hover:text-gray-700 hover:bg-gray-400
                      h-full
                      w-20
                      rounded-l
                      cursor-pointer
                      outline-none
                    "
                  >
                    <span class="m-auto text-2xl font-thin">−</span>
                  </button>
                  <input
                    type="string"
                    class="
                      outline-none
                      focus:outline-none
                      text-center
                      w-full
                      bg-gray-300
                      font-semibold
                      text-md
                      hover:text-black
                      focus:text-black
                      md:text-basecursor-default
                      flex
                      items-center
                      text-gray-700
                      outline-none
                    "
                    name="custom-input-number"
                    :value="formatBid"
                    disabled
                  />
                  <button
                    @click="increment"
                    class="
                      bg-gray-300
                      text-gray-600
                      hover:text-gray-700 hover:bg-gray-400
                      h-full
                      w-20
                      rounded-r
                      cursor-pointer
                    "
                  >
                    <span class="m-auto text-2xl font-thin">+</span>
                  </button>
                </div>
              </div>
            </div>
            <button
              class="
                px-5
                bg-gray-800
                text-white text-xs
                font-bold
                uppercase
                rounded
              "
              :disabled="data.clicked"
              @click="submitBid"
            >
              Bid
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-for="list in priceList" :key="list.id">
      <h4 class="text-gray-700 font-bold text-center">
        {{ list.username }} - {{ formatPrice(list.price || 0) }}
      </h4>
    </div>
  </div>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive,
  computed,
  onMounted,
  watchEffect,
} from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default defineComponent({
  name: "ProductCard",
  props: {
    price: {
      type: Number,
      default: 0,
    },
    priceList: Array,
  },
  setup(props) {
    const router = useRouter();
    // const route = useRoute();

    const data = reactive({
      bid: 0,
      kelipatan: 10000000,
      clicked: false,
    });

    onMounted(() => {
      // data.bid = props.price || 0;
      // console.log(router.currentRoute.value.params.user);
    });

    watchEffect(() => (data.bid = props.price + data.kelipatan));

    const decrement = () => {
      if (data.bid === 0 || props.price + data.kelipatan >= data.bid) return;
      data.bid -= data.kelipatan;
    };

    const increment = () => {
      data.bid += data.kelipatan;
    };

    const formatPrice = (price: number) => {
      return (
        "Rp " +
        Math.floor(price)
          .toString()
          .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
      );
    };

    const formatBid = computed(() => {
      return formatPrice(data.bid);
    });

    const submitBid = async () => {
      console.log("submit bid");

      data.clicked = true;

      await axios
        .post("http://127.0.0.1:8000/api/sendBidUnit", {
          user_id: router.currentRoute.value.params.id,
          car_id: router.currentRoute.value.params.id,
          username: router.currentRoute.value.params.user,
          price: data.bid,
          channel: router.currentRoute.value.params.id,
        })
        .then((response) => {
          // console.log(response.data);
          console.log("res", response);

          setTimeout(
            function () {
              data.clicked = false;
            }.bind(this),
            1000
          );
        });
    };

    return {
      data,
      increment,
      decrement,
      formatBid,
      submitBid,
      formatPrice,
    };
  },
});
</script>
