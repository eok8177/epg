<template>
  <div class="cabinet-main">

    <div class="container">


      <ul v-for="chanel in chanels" class="nav flex-column">
        <li class="nav-item">
          <router-link :to="{ name: 'Chanel', params: {chanel: chanel.id}}" class="nav-link">{{chanel.title}} <small>[ {{chanel.name}} ]</small></router-link>
        </li>
      </ul>

    </div>


  </div>
</template>

<script>
  import {api} from './api'

  export default {
    data: () => ({
      chanels: {}
    }),
    methods: {
      async getChanels() {
        this.$root.$emit('busy', true);
        this.chanels = await api.getChanels();
        this.$root.$emit('busy', false);
      },
    },
    created() {
      this.getChanels();
    },
    mounted() {}
  }
</script>

<style module>
</style>
