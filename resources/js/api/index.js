import axios from 'axios';

export default axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {  
    contentType: 'application/json',
    Accept: 'application/json',
    Authorization: 'Bearer '+ localStorage.getItem('Token')
  },
});
