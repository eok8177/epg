<template>
  <div class="cabinet-main">

    <div class="container">

      <div class="text-end mb-3">
        <button type="button" class="btn btn-sm btn-outline-secondary"
          @click="addChanel"
        >Add new channel</button>
      </div>

      <div class="row" v-if="chanels.length > 0">
        <div class="col-6 col-sm-4">
          <b>Title</b>
        </div>
        <div class="col-4 col-sm-3">
          <b>Channel ID</b>
        </div>
        <div class="col-2 col-sm-2">
          <b>GMT</b>
        </div>
      </div>

      <div v-for="chanel, index in chanels" class="row mb-3">

          <div class="col-6 col-sm-4 mb-2">
            <input type="text" class="form-control form-control-sm"
              placeholder="Title"
              :readonly="!chanel.edit"
              v-model="chanel.title"
              ref="items"
            >
          </div>
          <div class="col-4 col-sm-3 mb-2">
            <input type="text" class="form-control form-control-sm"
              placeholder="ID" 
              :readonly="!chanel.edit"
              v-model="chanel.chanel_id"
            >
          </div>

          <div class="col-2 col-sm-2 mb-2">
            <select class="form-select form-select-sm"
              :disabled="!chanel.edit"
              v-model="chanel.offset"
            >
              <option v-for="offset, value in offsets" :value="value">{{offset}}</option>
            </select>
          </div>

          <div class="col-12 col-sm-3 text-end mb-1">
            <router-link class="btn btn-sm btn-outline-primary me-1"
              v-if="!chanel.edit"
              :to="{ name: 'Chanel', params: {chanel: chanel.id}}"
            >Programs</router-link>
            <button type="button" class="btn btn-sm btn-outline-success me-1"
              v-if="chanel.edit"
              @click="saveChanel(index)"
            >Save</button>
            <button type="button" class="btn btn-sm btn-outline-secondary me-1"
              @click="chanel.edit = 1"
            >Edit</button>

            <button type="button" class="btn btn-sm btn-outline-danger"
              @click="deleteChanel(index)"
            >X</button>
          </div>

          <div class="col-12 col-sm-12 d-flex justify-content-between mb-2" v-if="chanel.has_programs">
            <div>
              <a target="_blank" class="text-secondary"
                v-if="chanel.link"
                :href="chanel.link"
              >{{chanel.link}}</a>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary"
              v-if="chanel.has_programs"
              @click="exportChanel(index)"
              :disabled="loading"
            >create XML file</button>
          </div>


      </div>

      <div v-if="chanels.length > 1">
        <hr>
        <p>Create one XML file for all channels:
          <button type="button" class="btn btn-sm btn-outline-secondary"
            @click="exportAllChanels()"
            :disabled="loading"
          >create XML file</button>
        </p>
        <div v-if="linkAll">
          <a :href="linkAll" target="_blank" class="text-secondary">{{linkAll}}</a>
        </div>
      </div>

    </div>


  </div>
</template>

<script>
  import {api} from './api'

  export default {
    data: () => ({
      chanels: [],
      offsets: {},
      linkAll: '',

      loading: false,
      offset: Number,
    }),
    methods: {
      async getChanels() {
        this.loading = true;
        let res = await api.getChanels();
        this.chanels = res.data;
        this.offsets = res.offsets
        this.loading = false;
      },

      async saveChanel(index) {
        this.loading = true;
        let data = await api.saveChanel(this.chanels[index]);
        this.chanels[index].id = data.id;
        this.chanels[index].edit = false;;
        this.loading = false;
      },

      async deleteChanel(index) {
        if (!confirm('Are you sure you want to delete?')) return false;
        this.loading = true;
        if (this.chanels[index].id) await api.deleteChanel(this.chanels[index].id);
        this.chanels.splice(index, 1);
        this.loading = false;
      },

      async exportChanel(index) {
        this.loading = true;
        let link = await api.exportChanel(this.chanels[index].id);
        this.chanels[index].link = window.location.origin + '/' + link;
        this.loading = false;
      },

      async exportAllChanels() {
        this.loading = true;
        let link = await api.exportAllChanels();
        this.linkAll = window.location.origin + '/' + link;
        this.loading = false;
      },


      addChanel() {
        this.chanels.push({
          id: '',
          chanel_id: '',
          title: '',
          offset: this.offset,
          edit: true,
        });
        this.focusLastInput();
      },


      focusLastInput() {
        let lastIndex = this.chanels.length - 1;
        this.$nextTick(()=> {
          this.$refs.items[lastIndex].focus();
        });
      },



    },
    created() {
      this.getChanels();
      this.offset = new Date().getTimezoneOffset();
    },
    mounted() {}
  }
</script>

<style module>
</style>
