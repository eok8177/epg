<template>
  <div class="container cabinet">

    <nav class="navbar navbar-expand-xl navbar-light" :class="{'load-animation': busy}">

      <a class="navbar-brand" href="/"></a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav_bar" aria-controls="nav_bar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="nav_bar">

        <div class="navbar-nav">
          <router-link :to="{ name: 'Main'}" class="nav-item nav-link">Главная</router-link>
        </div>

        <div class="navbar-nav">
          <a class="nav-item nav-link" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
        </div>
      </div>
    </nav>

    <router-view></router-view>


  </div>
</template>

<script>
  import {api} from './api'
  export default {
    data: () => ({
      busy: false,
    }),
    methods: {},
    created() {},
    mounted() {}
  }
</script>

<style module>
</style>
