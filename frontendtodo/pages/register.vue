<template>
  <div class="layout bg-dark">
    <div class="min-height">
      <main class="form-signin">
        <div class="card">
          <div class="card-body bg-secondary">
            <h1 class="h3 mb-3 fw-normal text-center text-white">Register Akun</h1>
            <div v-if="message != ''" :class="[warning == true ? 'alert-warning' : 'alert-success', `alert alert-dismissible fade show`]" role="alert">
              {{ message }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="form-floating text-white mb-2">
              <input v-model="name" id="" type="text" :class="[valid_name != '' ? 'is-invalid': '', `form-control bg-dark`]" placeholder="">
              <label for="name">Name</label>
              <div class="invalid-feedback pb-2">
                {{ valid_name }}
              </div>
            </div>
            <div class="form-floating text-white mb-2">
              <input v-model="email" id="" type="email" :class="[valid_email != '' ? 'is-invalid': '', `form-control bg-dark`]" placeholder="">
              <label for="email">Email</label>
              <div class="invalid-feedback pb-2">
                {{ valid_email }}
              </div>
            </div>
            <div class="form-floating text-white">
              <input v-model="password" id="" type="password" :class="[valid_password != '' ? 'is-invalid': '', `form-control bg-dark`]" placeholder="Password">
              <label for="password">Password</label>
              <div class="invalid-feedback pb-2">
                {{ valid_password }}
              </div>
            </div>
            <button class="w-100 btn btn-primary" @click="submit">Sign in</button>
            <a href="/login" class="btn btn-success mt-3">Login</a>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',

  data(){
    return{
      name:'',
      email:'',
      password:'',
      valid_name:'',
      valid_email:'',
      valid_password:'',
      message:'',
      warning:false,
    }
  },
  methods:{
    async submit() {

      this.valid_email = ''
      this.valid_name = ''
      this.valid_password = ''
      this.message = ''

      if (this.name == ''){
        this.valid_name = 'Nama harus di isi'
      }
      if (this.email == ''){
        this.valid_email = 'Email harus di isi'
      }
      if (this.password == ''){
        this.valid_password = 'Password harus di isi'
      }else{
        this.$axios.post(`${this.$axios.defaults.baseURL}/api/register`,{
          name:this.name,
          email:this.email,
          password:this.password
        }).then(res=>{
          console.log(res);
          this.warning = false
          this.message = 'Sukkses membuat akun, silahkan login !'
        }).catch(error=>{
          this.warning = true
          this.message = error.response.data.error
          // console.log(error.response.data.error);
        }).finally(()=>{
  
        })
      }
    }
  }
}
</script>
