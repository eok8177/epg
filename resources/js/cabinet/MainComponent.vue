<template>
  <div class="cabinet-main">
    
    <div class="container">
      
      <div class="text-end mb-3">
        <button type="button" class="btn btn-sm btn-outline-secondary" @click="addChanel">
          <svg width="18" height="18" viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
            <g style="fill:none; stroke:var(--ci-secondary-color, currentColor);stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-width:2">
              <path d="m36.0731 22.3267v27.2502"/>
              <path d="m49.6982 35.9518h-27.2503"/>
              <circle cx="36.0731" cy="35.9518" r="23"/>
            </g>
          </svg>
          Add new channel
        </button>
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
          <input type="text" class="form-control form-control-sm" placeholder="Title" :readonly="!chanel.edit" v-model="chanel.title" ref="items" >
        </div>
        <div class="col-4 col-sm-3 mb-2">
          <input type="text" class="form-control form-control-sm" placeholder="ID" :readonly="!chanel.edit" v-model="chanel.chanel_id" >
        </div>
        
        <div class="col-2 col-sm-2 mb-2">
          <div class="input-group">
            <select class="form-select form-select-sm" :disabled="!chanel.edit" v-model="chanel.offset" >
              <option v-for="offset, value in offsets" :value="value">{{offset}}</option>
            </select>
            
            <div class="form-check ms-2">
              <input class="form-check-input" type="checkbox" :id="'cron_'+index" :disabled="!chanel.edit" v-model="chanel.cron" >
              <label class="form-check-label" :for="'cron_'+index">Cron</label>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-3 text-end mb-1">
          
          <button @click="uploadInit(index)" class="btn btn-sm btn-outline-primary me-1">
            <svg width="14" height="14" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
              <path d="m807.186 686.592 272.864 272.864h-1080.05v112.94h1080.05l-272.864 272.978 79.736 79.849 409.296-409.183-409.296-409.184zm1063.233-251.902-329.221-329.11c-31.51-31.51-75.219-49.58-119.718-49.58h-969.707v730.612h112.94v-617.671h790.584v451.762h451.762v1129.405h-1242.345v-508.233h-112.94v621.173h1468.226v-1308.528c0-45.176-17.619-87.754-49.58-119.83zm-402.181-242.37 315.443 315.442h-315.443v-315.443z" fill-rule="evenodd" fill="var(--ci-primary-color, currentColor)"/>
            </svg>
            Import
          </button>
          
          <router-link class="btn btn-sm btn-outline-primary me-1" v-if="!chanel.edit" :to="{ name: 'Chanel', params: {chanel: chanel.id}}" >Programs</router-link>
          
          <button type="button" class="btn btn-sm btn-outline-success me-1" v-if="chanel.edit" @click="saveChanel(index)" >Save</button>
          
          <button type="button" class="btn btn-sm btn-outline-secondary me-1" @click="chanel.edit = 1" >Edit</button>
          
          <button type="button" class="btn btn-sm btn-outline-danger" @click="deleteChanel(index)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" fill="var(--ci-danger-color, currentColor)"/>
            </svg>
          </button>
        </div>
        
        <div class="col-12 col-sm-12 d-flex justify-content-between mb-2" v-if="chanel.has_programs">
          <div>
            <a target="_blank" class="text-secondary" v-if="chanel.link" :href="chanel.link" >{{chanel.link}}</a>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary" v-if="chanel.has_programs" @click="exportChanel(index)" :disabled="loading" >create XML file</button>
        </div>
        
        
      </div>
      
      <div v-if="chanels.length > 1">
        <hr>
        <p>Create one XML file for all channels:
          <button type="button" class="btn btn-sm btn-outline-secondary" @click="exportAllChanels()" :disabled="loading" >create XML file</button>
        </p>
        <div v-if="linkAll">
          <a :href="linkAll" target="_blank" class="text-secondary">{{linkAll}}</a>
        </div>
      </div>
      
    </div>
    
    <input type="file" class="d-none" ref="file" accept=".xlsx" @change.prevent="handleFileUpload">
    
    
    <div v-if="show_import_popup" class="modal" :class="{'show':show_import_popup}">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="parsed" class="modal-title">Result for: {{ chanels.find(i=>i.id==chanel).title }}</h5>
            <h5 v-else class="modal-title">Compare columns for: {{ chanels.find(i=>i.id==chanel).title }}</h5>
            <button @click="show_import_popup = false" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div v-if="parsed">
              <h6>Added rows count: {{ added_count }}</h6>
              <h6>Skipped rows count: {{ skip_count }}</h6>
              <table class="table table-bordered">
                <tr v-for="(row,index) in skipped_rows" :key="index">
                  <td v-for="(col,i) in row" :key="i">
                    {{ i == 'start' ? getDateFormated(col) : col }}
                  </td>
                </tr>
              </table>
            </div>
            <table v-else class="table table-bordered">
              <tr>
                <td v-for="(col,index) in xls_rows[0]">
                  <select class="form-select" v-model="select[index]">
                    <option selected value="skip">skip</option>
                    <option v-for="(title, id) in fields" :key="id" :value="id">{{ title }}</option>
                  </select>
                </td>
              </tr>
              <tr v-for="(row,index) in xls_rows" :key="index">
                <td v-for="col in row">{{ col }}</td>
              </tr>
            </table>
          </div>
          <div v-if="parsed" class="modal-footer">
            <button @click="show_import_popup = false" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          </div>
          <div v-else class="modal-footer">
            <button @click="show_import_popup = false" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button @click="parseFile" type="button" class="btn btn-outline-primary">Import file</button>
          </div>
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
    
    chanel: '',

    fields: {},
    xls_rows: [],
    show_import_popup: false,
    select: [],
    filename: '',

    skip_count: '',
    skipped_rows: {},
    added_count: '',
    parsed: false,
    
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
    
    uploadInit(index) {
      this.chanel = this.chanels[index].id;
      const elem = this.$refs.file;
      elem.click();
    },
    async handleFileUpload() {
      this.file = this.$refs.file.files[0];
      
      const res = await api.uploadFile({file:this.file,chanel:this.chanel});
      this.xls_rows = res.table;
      this.$refs.file.value = '';
      this.filename = res.filename;
      this.fields = res.fields;
      this.select = this.xls_rows[0].map((i,key)=>{
        if(key == 0) return 'date';
        if(key == 1) return 'time';
        if(key == 2) return 'title';
        if(key == 3) return 'description';
        return 'skip';
      }),
      this.show_import_popup = true;
      this.parsed = false;
    },
    async parseFile() {
     
      const res = await api.parseFile({
        filename: this.filename,
        chanel: this.chanel,
        select: this.select,
      });
      console.log(res)

      this.skip_count = res.skip_count;
      this.skipped_rows = res.skipped_rows;
      this.added_count = res.added_count;
      this.parsed = true;
      
      // this.show_import_popup = false;
    },
    getDateFormated(time) {
      if(time > 0) {
        let date = new Date(time * 1000);
        let year = date.getUTCFullYear();
        let month = (date.getUTCMonth()<9?'0':'') + (date.getUTCMonth()+1);
        let day = (date.getUTCDate()<10?'0':'') + date.getUTCDate();
        let hours = (date.getUTCHours()<10?'0':'') + date.getUTCHours();
        let minutes = (date.getUTCMinutes()<10?'0':'') + date.getUTCMinutes();
        let formattedTime = day + '.' + month + '.' + year + ' ' + hours + ':' + minutes;
        return formattedTime;
      }
      return time;
    }
    
  },
  created() {
    this.getChanels();
    this.offset = new Date().getTimezoneOffset();
  },
  mounted() {}
}
</script>

<style>
.modal.show {
  display: block;
}
</style>
