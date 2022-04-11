<template>
  <div class="cabinet-main">

    <div class="container">

      <h4>{{chanel.title}}</h4>

      <h5>{{getDate(chanel.date)}}</h5>

      <hr>
      
      <div class="row" v-for="program, index in chanel.programs">
        <input type="hidden" v-model="program.id">
        <div class="col-md-2">
          <input type="time" class="form-control mb-2" 
            :value="getTime(program.start)"
            @blur="onChangeTime(index, $event)"
          >
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control mb-2" placeholder="Title" v-model="program.title">
        </div>
        <div class="col-md-6">
          <textarea rows="1" class="form-control mb-2" placeholder="Desription" v-model="program.description"></textarea>
        </div>
      </div>

      <hr>
      <button @click="addProgram" type="button" class="btn btn-sm btn-outline-secondary">add program</button>

    </div>


  </div>
</template>

<script>
  import {api} from './api'

  export default {
    data: () => ({
      chanelID: '',
      chanel: {}
    }),
    methods: {
      async getChanel(id, date) {
        this.$root.$emit('busy', true);
        this.chanel = await api.getChanel(id, date);
        this.$root.$emit('busy', false);
      },

      addProgram() {
        this.chanel.programs.push({
          id: '',
          start: false,
          title: '',
          description: ''
        });
      },

      getTime(unixTime) {
        let date = new Date(unixTime * 1000);

        let hours = date.getHours();
        // Minutes part from the timestamp
        let minutes = "0" + date.getMinutes();
        // Seconds part from the timestamp
        // let seconds = "0" + date.getSeconds();

        return hours + ':' + minutes.substr(-2);
      },

      onChangeTime(index, event) {
        console.log(event.target.value)
      },

      getDate(unixTime) {
        let date = new Date(unixTime * 1000);

        let year = date.getFullYear();
        let month = (date.getMonth()<9?'0':'') + (date.getMonth()+1);
        let day = (date.getDate()<10?'0':'') + date.getDate();
        // console.log(date.getTime()/1000)
        return year + '/' + month + '/' + day;
      },

    },
    created() {
      this.chanelID = this.$route.params.chanel;
      this.getChanel(this.chanelID);
    },
    mounted() {}
  }
</script>

<style module>
</style>
