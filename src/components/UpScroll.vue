<template>
  <div class="upScroll" title="Наверх" @click='up'>
    <span>&#9650;</span>
  </div>
</template>

<script>
  // component upScroll
  // version: 0.0.2
  // date: 05.2020
  // sagit117@gmail.com 


  export default {

    methods: {
      up() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
      }
    }

  }
</script>

<style scoped>
  .upScroll {
    position: fixed;
    bottom: 80px;
    right: 20px;
    background-color: #41444a;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    opacity: 0.5;
    cursor: pointer;
    color: black;
    vertical-align: middle;
    text-align: center;
  }
  .upScroll:hover {
    opacity: 1;
  }

  span {
    line-height: 40px;
  }

</style>