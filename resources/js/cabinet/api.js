import axios from 'axios';

const clientID = document.querySelector('meta[name="client-id"]').content;
const clientToken = document.querySelector('meta[name="client-token"]').content;

const HTTP = axios.create({
  baseURL: "/api/",
  headers: {
    'Authorization': clientToken,
    'Client': clientID
  }
});

const api = {

    getChanels: async() => {
        try {
            let res = await HTTP.post('chanels');
            return res.data;
        } catch (error) {console.log('getChanels: '+ error)}
    },

    getChanel: async(id, date) => {
        let data = {};
        if(date) {
            data = {
                date: date
            };
        }
        try {
            let res = await HTTP.post('chanel/'+id, data);
            return res.data;
        } catch (error) {console.log('getChanel: '+ error)}
    },


    saveChanel: async(data) => {
        try {
            let res = await HTTP.post('save-chanel', data);
            return res.data;
        } catch (error) {console.log('saveChanel: '+ error)}
    },

    saveProgram: async(data, chanel) => {
        try {
            let res = await HTTP.post('save-program/'+chanel, data);
            return res.data;
        } catch (error) {console.log('saveProgram: '+ error)}
    },

    changePassword: async(password) => {
        try {
            let res = await HTTP.post('update-client/', {
                password: password
            });
            return res.data;
        } catch (error) {console.log('changePassword: '+ error)}
    },


    deleteProgram: async(program) => {
        try {
            let res = await HTTP.post('delete-program/'+program);
            return res.data;
        } catch (error) {console.log('deleteProgram: '+ error)}
    },

    deleteChanel: async(chanel) => {
        try {
            let res = await HTTP.post('delete-chanel/'+chanel);
            return res.data;
        } catch (error) {console.log('deleteChanel: '+ error)}
    },



    exportChanel: async(chanel) => {
        try {
            let res = await HTTP.post('export-chanel/'+chanel);
            return res.data;
        } catch (error) {console.log('exportChanel: '+ error)}
    },

    exportAllChanels: async() => {
        try {
            let res = await HTTP.post('export-all-chanels');
            return res.data;
        } catch (error) {console.log('exportAllChanels: '+ error)}
    },

    uploadFile: async(data) => {
        try {
            let formData = new FormData();
            formData.append('file', data.file);
            formData.append('chanel', data.chanel);

            let res = await HTTP.post('upload-file', formData);
            return res.data;
        } catch (error) {console.log('exportAllChanels: '+ error)}
    },

    parseFile: async(data) => {
        try {
            let res = await HTTP.post('parse-file', data);
            return res.data;
        } catch (error) {console.log('parseFile: '+ error)}
    },


}


export {api}