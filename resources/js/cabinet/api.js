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

    getChanels: async(id) => {
        try {
            let res = await HTTP.post('chanels');
            return res.data;
        } catch (error) {
            console.log('getChanels: '+ error);
        }
    },

    getChanel: async(id, date) => {
        try {
            let res = await HTTP.post('chanel/'+id, {
                date: date
            });
            return res.data;
        } catch (error) {
            console.log('getChanel: '+ error);
        }
    },


}


export {api}