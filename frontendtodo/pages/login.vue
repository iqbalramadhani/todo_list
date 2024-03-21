<template>
  <div class="layout bg-dark">
    <div class="min-height">
      <main class="form-signin">
        <div class="card">
          <div class="card-body bg-secondary">
            <h1 class="h3 mb-3 fw-normal text-center text-white">Login</h1>
            <div v-if="message != ''" :class="[warning == true ? 'alert-warning' : 'alert-success', `alert alert-dismissible fade show`]" role="alert">
              {{ message }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="form-floating mb-3">
              <input v-model="email" name="email" type="email" :class="[email_valid != '' ? 'is-invalid' : '', `form-control bg-dark`]" id="email" placeholder="">
              <label for="email" class="text-white">Email address</label>
              <div class="invalid-feedback pb-3">
                {{ email_valid }}
              </div>
            </div>
            <div class="form-floating">
              <input v-model="password" name="password" type="password" :class="[password_valid != '' ? 'is-invalid' : '', `form-control bg-dark`]" id="password" placeholder="Password">
              <label for="password" class="text-white">Password</label>
              <div class="invalid-feedback pb-2">
                {{ password_valid }}
              </div>
            </div>
            <button class="w-100 btn btn-sm btn-primary" @click="submit" >Login</button>
            <a href="/register" class="btn btn-success mt-3">Sign</a>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import nuxtStorage from 'nuxt-storage';


export default {
  data(){
    return{
      email:'',
      password:'',
      email_valid:'',
      password_valid:'',
      message:'',
      warning:false,
    }
  },

  mounted(){
    console.log(nuxtStorage.localStorage.getData('user'));
    console.log(nuxtStorage.localStorage.getData('token'));
  },

  methods: {
    submit:function(){

      this.email_valid = ''
      this.password_valid = ''
      this.message = ''

      let valid = true
      
      if (this.email == '') {
        this.email_valid = "email harus di isi"
        valid = false
      }
      if (this.password == '') {
        this.password_valid = "password harus di isi"
        valid = false
      }

      if(valid){
        this.$axios.post(`${this.$axios.defaults.baseURL}/api/login`,{
          email:this.email,
          password:this.password
        }).then(res=>{
          nuxtStorage.localStorage.setData('token',res.data.token,1,'h');
          nuxtStorage.localStorage.setData('user',res.data.user,1,'h');
          this.$router.push('/');
        }).catch(error=>{
          this.warning = true
          this.message = error.response.data.error
          console.log(error);
        }).finally(()=>{
  
        })
      }
    }
  }
}
</script>
