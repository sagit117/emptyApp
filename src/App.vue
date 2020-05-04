<template>
  <div id="app">
    <div id="nav">
      <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <router-link to="/" tag="h5" class="my-0 mr-md-auto font-weight-normal pointer">emptyApp</router-link>
        <nav class="my-2 my-md-0 mr-md-3">
          <!--<a class="p-2 text-dark" href="#">Features</a>
          <a class="p-2 text-dark" href="#">Enterprise</a>
          <a class="p-2 text-dark" href="#">Support</a>
          <a class="p-2 text-dark" href="#">Pricing</a>-->
        </nav>
        <LoginControler />
      </div>
    </div>
    <router-view/>

    <Alert v-if="this.$store.getters.getAlert.show"/>
    <Wait v-if="this.$store.getters.getShowWait" />
    <UpScroll v-if="visibleUpScroll" />
  </div>
</template>

<script>
import LoginControler from '@/components/LoginControler.vue'
import Alert from '@/components/Alert.vue'
import Wait from '@/components/Wait.vue'
import UpScroll from '@/components/UpScroll.vue'

export default {
  components: {
    LoginControler,
    Alert,
    Wait,
    UpScroll
  },

  data() {
    return {
      visibleUpScroll: false
    }
  },

  created() {
    window.addEventListener('scroll', this.handleScroll);

    const loginData = {
      userHash: localStorage.getItem('userHash'),
      userLogin: localStorage.getItem('userLogin')
    }
    this.$store.dispatch("userLoginedWithHash", loginData);
  },

  methods: {
    handleScroll() {
      // видимость дива с кнопкой на верх
      this.visibleUpScroll = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? true : false;
    },
  },

  destroyed() {
    window.removeEventListener('scroll', this.handleScroll);
  },

}
</script>

<style lang="scss">
  .pointer {
    cursor: pointer;
  }
  .close {
    position: absolute;
    top: 10px;
    right: 10px;
  }
</style>
