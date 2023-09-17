<template>
  <div class="cabinet">

    <div class="container">
      <nav class="navbar navbar-light justify-content-end mb-4" :class="{'load-animation': busy}">

        <router-link :to="{ name: 'Main'}" class="icon-link">
          <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="var(--ci-primary-color, currentColor)" d="M136,24H16V144H136Zm-32,88H48V56h56Z"/>
            <path fill="var(--ci-primary-color, currentColor)" d="M136,200H16V320H136Zm-32,88H48V232h56Z"/>
            <path fill="var(--ci-primary-color, currentColor)" d="M136,376H16V496H136Zm-32,88H48V408h56Z"/>
            <rect width="320" height="32" x="176" y="23.998" fill="var(--ci-primary-color, currentColor)"/>
            <rect width="256" height="32" x="176" y="111.998" fill="var(--ci-primary-color, currentColor)"/>
            <rect width="320" height="32" x="176" y="199.998" fill="var(--ci-primary-color, currentColor)"/>
            <rect width="256" height="32" x="176" y="287.998" fill="var(--ci-primary-color, currentColor)"/>
            <rect width="256" height="32" x="176" y="463.998" fill="var(--ci-primary-color, currentColor)"/>
            <rect width="320" height="32" x="176" y="375.998" fill="var(--ci-primary-color, currentColor)"/>
          </svg>
          Channels
        </router-link>

        <router-link :to="{ name: 'Profile'}" class="icon-link ms-3">
          <svg fill="none" width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <g stroke="var(--ci-primary-color, currentColor)" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
              <path d="m12.12 12.78c-.07-.01-.16-.01-.24 0-1.76-.06-3.16003-1.5-3.16003-3.27002 0-1.81 1.46003-3.28 3.28003-3.28 1.81 0 3.28 1.47 3.28 3.28-.01 1.77002-1.4 3.21002-3.16 3.27002z"/>
              <path d="m18.74 19.3801c-1.78 1.63-4.14 2.62-6.74 2.62-2.59999 0-4.95999-.99-6.73999-2.62.1-.94.7-1.86 1.77-2.58 2.74-1.82 7.21999-1.82 9.93999 0 1.07.72 1.67 1.64 1.77 2.58z"/>
              <path d="m12 22c5.5228 0 10-4.4772 10-10 0-5.52285-4.4772-10-10-10-5.52285 0-10 4.47715-10 10 0 5.5228 4.47715 10 10 10z"/>
            </g>
          </svg>
          Profile
        </router-link>

        <a class="icon-link ms-3" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <svg width="16" height="16" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd" stroke="var(--ci-primary-color, currentColor)" stroke-linecap="round" stroke-linejoin="round" transform="translate(4 3)">
              <path d="m12.717 5.379-.068 4.175-4.175.067" transform="matrix(.70710678 .70710678 .70710678 -.70710678 -2.199921 5.311079)"/>
              <path d="m9 3v9" transform="matrix(0 1 -1 0 16.5 -1.5)"/>
              <path d="m-1.74806976 2.74806976.0022166 7.50105294c.00091744 1.1043385.89625231 1.9994088 2.00059101 1.9999999h9.99438475c1.1045695 0 2-.8954305 2-2v-7.50105284" transform="matrix(0 1 -1 0 12.749 2.249)"/>
            </g>
          </svg>
          Exit
        </a>

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
