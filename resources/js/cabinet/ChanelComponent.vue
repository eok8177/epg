<template>
  <div class="cabinet-main">

    <div class="container">

      <h4>{{chanel.title}}</h4>

      <div class="btn-group" role="group">
        <button type="button" class="btn btn-sm btn-outline-secondary"
          @click="prevDay"
          :disabled="disablePrevBtn"
        >prev</button>
        <div class="input-group-text">{{ todayDate }}</div>
        <button type="button" class="btn btn-sm btn-outline-secondary"
          @click="nextDay"
          :disabled="disableNextBtn"
        >next</button>
      </div>

      <hr>

      <div class="row mb-2" v-if="chanel.programs.length > 0">
        <div class="col-md-2 col-5"><b>Time</b></div>
        <div class="col-md-4 col-7"><b>Title</b></div>
        <div class="col-md-5 col-10"><b>Description</b></div>
        <div class="col-md-1 col-2"></div>
      </div>

      <div class="row" v-for="program, index in chanel.programs">
        <input type="hidden" v-model="program.id">
        <div class="col-md-2 col-5">
          <input type="time" class="form-control form-control-sm mb-2 time-input" 
            :value="getTime(program.start)"
            @blur="onChangeTime(index, $event)"
            ref="items"
          >
          <!-- <div class="form-text mt-0 mb-1">{{ getTime(program.stop) }}</div> -->
        </div>
        <div class="col-md-4 col-7">
          <input type="text" class="form-control form-control-sm mb-2" placeholder="Title" 
            v-model="program.title"
            @blur="saveProgram(index)"
          >
        </div>
        <div class="col-md-5 col-10">
          <textarea rows="1" class="form-control form-control-sm mb-2" placeholder="Desription"
            v-model="program.description"
            @blur="saveProgram(index)"
          ></textarea>
        </div>
        <div class="col-md-1 col-2">
          <button type="button" class="btn btn-sm btn-outline-danger"
            @click="deleteProgram(index)"
          >X</button>
        </div>
      </div>

      <hr>

      <button type="button" class="btn btn-sm btn-outline-secondary"
        @click="addProgram"
        :disabled="disableAddBtn"
      >add program</button>

    </div>


  </div>
</template>

<script>
  import {api} from './api'

  export default {
    data: () => ({
      chanelID: '',
      chanel: {
        date: '',
        programs: [],
      },

      loading: false,
    }),
    computed: {
      disablePrevBtn() {
        return this.loading || !this.chanel.has_prev;
      },
      disableNextBtn() {
        return this.loading || this.chanel.programs.length < 1;
      },
      todayDate() {
        return this.getDate(this.chanel.date);
      },
      disableAddBtn() {
        return this.loading;
      },
    },
    methods: {
      async getChanel() {
        this.loading = true;
        this.chanel = await api.getChanel(this.chanelID, this.chanel.date);
        this.loading = false;
      },

      async saveProgram(index) {
        if (!this.checkInputDate(index, true)) return;
        this.loading = true;
        this.chanel.programs[index] = await api.saveProgram(this.chanel.programs[index], this.chanelID);
        this.loading = false;
        $('.time-input').removeClass('is-invalid');
      },

      async deleteProgram(index) {
        if (!confirm('Are you sure you want to delete?')) return false;
        this.loading = true;
        await api.deleteProgram(this.chanel.programs[index].id);
        this.chanel.programs.splice(index, 1);
        this.loading = false;
      },

      addProgram() {

        if (!this.checkInputDate()) return;

        this.chanel.programs.push({
          id: '',
          start: false,
          title: '',
          description: ''
        });

        this.focusLastInput();
      },

      onChangeTime(index, event) {
        let time = event.target.value;
        let arr = time.split(':');
        let start = this.chanel.date + arr[0]*60*60 + arr[1]*60;
        this.chanel.programs[index].start = start;
        // console.log(arr + start)

        if (!this.checkInputDate(index, true)) return;

        this.saveProgram(index);
      },

      prevDay() {
        this.chanel.date -= 60*60*24;
        this.getChanel();
      },
      nextDay() {
        this.chanel.date += 60*60*24;
        this.getChanel();
      },

      getTime(unixTime) {

        let date = new Date(unixTime * 1000);

        if (date == 'Invalid Date') return '';
        if (date.getFullYear() == '1970') return '';

        let hours = "0" + date.getUTCHours();
        let minutes = "0" + date.getMinutes();

        return hours.substr(-2) + ':' + minutes.substr(-2);
      },

      getDate(unixTime) {
        let date = new Date(unixTime * 1000);

        let year = date.getFullYear();
        let month = (date.getMonth()<9?'0':'') + (date.getMonth()+1);
        let day = (date.getDate()<10?'0':'') + date.getDate();
        // console.log(date.getTime()/1000)
        return day + '.' + month + '.' + year;
      },

      checkInputDate(lastIndex = false, onlyShowError = false) {
        let lastElementIndex = this.chanel.programs.length - 1;
        if (!lastIndex) lastIndex = lastElementIndex;
        let lastItem = lastIndex >= 0 ? this.chanel.programs[lastIndex] : false;

        let prevIndex = lastIndex - 1;
        let prevItem = this.chanel.programs[prevIndex];

        if (lastItem && prevItem && lastItem.start <= prevItem.start) {
          this.$nextTick(()=> {
            $(this.$refs.items[lastIndex]).addClass('is-invalid');
            if (!onlyShowError) this.$refs.items[lastIndex].focus();
            // if item inside list, not last
            if (lastElementIndex != lastIndex) this.$refs.items[lastIndex].focus();
          });
          return false;
        }
        return true;
      },

      focusLastInput() {
        let lastIndex = this.chanel.programs.length - 1;
        this.$nextTick(()=> {
          this.$refs.items[lastIndex].focus();
        });
      },

    },
    created() {
      this.chanelID = this.$route.params.chanel;
      this.getChanel();
    },
    mounted() {}
  }
</script>

<style module>
</style>
