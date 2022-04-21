<template>
  <div class="cabinet">

    <div class="container">
      <nav class="navbar navbar-light justify-content-end" :class="{'load-animation': busy}">

        <div class="navbar-nav me-2">
          <router-link :to="{ name: 'Main'}" class="nav-item nav-link">Channels</router-link>
        </div>

        <div class="navbar-nav me-2">
          <router-link :to="{ name: 'Profile'}" class="nav-item nav-link">Profile</router-link>
        </div>

        <div class="navbar-nav">
          <a class="nav-item nav-link" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Exit</a>
        </div>
      </nav>
    </div>

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
