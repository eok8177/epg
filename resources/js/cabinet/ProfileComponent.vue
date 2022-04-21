<template>
  <div class="cabinet-main">

    <div class="container">

      <h4>Change password</h4>

      <input type="password" class="form-control form-control-sm mb-3 input" style="max-width:320px"
        v-model="password"
        placeholder="Password"
      >

      <input type="password" class="form-control form-control-sm mb-3 input" style="max-width:320px"
        v-model="rePassword"
        placeholder="Repeat password"
      >

      <button type="button" class="btn btn-sm btn-outline-secondary"
        @click="changePassword()"
      >Update</button>

    </div>


  </div>
</template>

<script>
  import {api} from './api'

  export default {
    data: () => ({
      password: '',
      rePassword: '',

      loading: false,
    }),

    methods: {

      async changePassword() {
        if (this.password.length <= 4 || this.password != this.rePassword) {
          $('.input').addClass('is-invalid');
          return;
        }
        this.loading = true;
        await api.changePassword(this.password);
        this.loading = false;
        $('.input').removeClass('is-invalid');
      },

    },
    created() {},
    mounted() {}
  }
</script>

<style module>
</style>
