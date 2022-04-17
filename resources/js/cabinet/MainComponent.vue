<template>
  <div class="cabinet-main">

    <div class="container">

      <div class="text-end mb-3">
        <button type="button" class="btn btn-sm btn-outline-secondary"
          @click="addChanel"
        >Add new channel</button>
      </div>


      <div v-for="chanel, index in chanels" class="row mb-3">

          <div class="col-4">
            <input type="text" class="form-control form-control-sm"
              placeholder="Title"
              :readonly="!chanel.edit"
              v-model="chanel.title"
              ref="items"
            >
          </div>
          <div class="col-5">
            <input type="text" class="form-control form-control-sm"
              placeholder="ID" 
              :readonly="!chanel.edit"
              v-model="chanel.chanel_id"
            >
          </div>

          <div class="col-3 text-end">
            <router-link class="btn btn-sm btn-outline-primary me-1"
              v-if="!chanel.edit"
              :to="{ name: 'Chanel', params: {chanel: chanel.id}}"
            >Open</router-link>
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

          <div class="col-12 d-flex justify-content-between mt-1" v-if="chanel.has_programs">
            <div>
              <a :href="'/epg/'+chanel.id+'_'+chanel.chanel_id+'.xml'" target="_blank" class="text-secondary">{{ domain() +'/epg/'+chanel.id+'_'+chanel.chanel_id+'.xml'}}</a>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary"
              v-if="chanel.has_programs"
              @click="exportChanel(index)"
            >create XML file</button>
          </div>


      </div>

    </div>


  </div>
</template>

<script>
  import {api} from './api'

  export default {
    data: () => ({
      chanels: {},

      loading: false,
    }),
    methods: {
      async getChanels() {
        this.loading = true;
        this.chanels = await api.getChanels();
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
        await api.exportChanel(this.chanels[index].id);
        this.loading = false;
      },


      addChanel() {
        this.chanels.push({
          id: '',
          chanel_id: '',
          title: '',
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

      domain() {
        return window.location.origin;
      }


    },
    created() {
      this.getChanels();
    },
    mounted() {}
  }
</script>

<style module>
</style>
