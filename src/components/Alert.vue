<template>
  <div class="alert cover" role="alert" :class="{ 'alert-success': state === 1, 'alert-danger': state === 2 }">
    <h4 class="alert-heading">{{ title }}</h4>
    <hr>
    <p class="mb-0">{{ text }}</p>
    <button type="button" class="close" aria-label="Close" @click="close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: this.$store.getters.getAlert.title,
      text: this.$store.getters.getAlert.text,
      state: this.$store.getters.getAlert.state
    }
  },
  methods: {
    close() {
      this.$store.commit('setShowAlert', { show: false, title: "", test: "", state: 0 });
    },
  }

}
</script>

<style lang="scss" scoped>
  .cover {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
  }
</style>>