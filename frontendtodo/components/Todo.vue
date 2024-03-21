<!-- Please remove this file from your project -->
<template>
  <div class="layout bg-dark">
    <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
      <div class="container">
        <a class="navbar-brand text-white" href="#">Selamat Datang, {{ user.name }}</a>
        <div class="d-flex">
          <button class="btn text-white" @click="logout">Logout</button>
        </div>
      </div>
    </nav>
    <div class="mt-5">
      <h1 class="text-center">Todo List</h1>
      <div class="d-flex justify-content-center">
        <div class="card w-75 mt-3 bg-secondary text-white">
          <div class="card-body">
            <div v-if="message != ''" class="alert-warning alert alert-dismissible fade show" role="alert">
              {{ message }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <ul class="nav nav-tabs bg-dark border-0 text-white" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" @click="statusTab('all')"><h5>All</h5></button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" @click="statusTab('on_going')"><h5>On Going</h5></button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" @click="statusTab('done')"><h5>Done</h5></button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row mb-4 mt-3 justify-content-center">
                  <div class="col-md-11">
                    <div class="form-floating">
                      <input v-model="content" type="text" class="form-control w-100 bg-dark text-white" placeholder="">
                      <label for="todo" class="text-secondary">Tambahkan Todo List</label>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <button class="btn btn-primary mt-2" @click="submit">Simpan</button>
                  </div>
                </div>
                <div v-for="(todo,i) in todos" :key="todo.id">
                  <div class="card mb-3 border-0 rounded">
                    <div class="card-body bg-dark rounded">
                      <div class="d-flex justify-content-between">
                        <input v-if="todo.edit" type="text" class="border" v-model="todo.name">
                        <div v-else class="form-check">
                          <input class="form-check-input" :checked="todo.completed" type="checkbox" @click="updateStatus(todo,i)" value="" :id="[todo.id,`checkData`]">
                          <label class="form-check-label" :for="[todo.id,`checkData`]">
                            <p :class="todo.completed ? `text-decoration-line-through` : ''">{{ todo.name }}</p>
                          </label>
                        </div>
                        <div>
                          <button v-if="todo.edit" class="btn btn-sm btn-outline-success" @click="saveEditTodo(todo,i)">Save</button>
                          <button v-else class="btn btn-sm btn-outline-warning" @click="editTodo(todo,i)">Edit</button>
                          <button class="btn btn-sm btn-outline-danger" @click="deleteTodo(todo,i)">Hapus</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div v-for="(todo,i) in todos" :key="todo.id" class="mt-3">
                  <div class="card bg-dark">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p>{{ todo.name }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div v-for="(todo,i) in todos" :key="todo.id" class="mt-3">
                  <div class="card bg-dark">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p>{{ todo.name }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

import nuxtStorage from 'nuxt-storage';

export default {
  name: 'NuxtJs',

  data(){
    return{
      content:'',
      todos:[],
      user:'',
      message:'',
      edit:[],
      newContent:'',
      ini:'data'
    }
  },
    mounted(){
    this.user = nuxtStorage.localStorage.getData('user');
    if (!this.user){
      this.$router.push('/login');
    }

    this.$axios.get(`${this.$axios.defaults.baseURL}/api/items`,{
        headers:{Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`}
      }).then(res => {
        this.todos = res.data
        for (var i = 0; i < res.data.length; i++) {
            this.todos[i].edit = false
        }
        this.edit = Array(res.data.length).fill(false)
        console.log(this.todos);
      }).catch(err=>{
        console.log(err);
      })
  },

  methods:{
    submit: function(){
      this.message = ''
      if (this.content == ''){
        this.message = 'isi list harus di isi'
      }else{
        this.$axios.post(`${this.$axios.defaults.baseURL}/api/item/store`,
          {name:this.content},
          {headers: {Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`,},
        }).then(res=>{
          let store = res.data
          store.edit = false
          this.todos.unshift(store);
          this.edit.unshift(false);
          this.content = '';
        }).catch(error=>{
          console.log(error);
        }).finally(()=>{
        })
      }

    },

    updateStatus:function(todo,i){
      this.message = ''
      this.$axios.put(`${this.$axios.defaults.baseURL}/api/item/${todo.id}`,{
        name:todo.name,
        completed:todo.completed
      },{
        headers:{Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`}
      }).then(res=>{
        this.todos[i].completed = res.data.completed;
      }).catch(error=>{
        this.message = error.response.data.error
      }).finally(()=>{

      })
    },

    deleteTodo:function(todo,i){
      this.message = ''
      this.$axios.delete(`${this.$axios.defaults.baseURL}/api/item/${todo.id}`,{
        headers:{Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`}
      }).then(res=>{
        if(res.data.status == true){
          this.todos.splice(i,1);
          this.edit.splice(i,1);
        }
      }).catch(error=>{
        console.log(error);
        this.message = error.response.data.error
      }).finally(()=>{
      })
    },

    statusTab: async function(status){
      console.log(status);
      await this.$axios.get(`${this.$axios.defaults.baseURL}/api/items?status=${status}`,{
        headers:{Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`}
      }).then(res => {
        this.todos = res.data
      }).catch(err=>{
        console.log(err);
      })
    },

    editTodo:function(todo,i){
      this.todos[i].name = todo.name+' '
      this.todos[i].edit = true
      console.log(this.todos);
    },
    
    saveEditTodo:function(todo,i){
      this.$axios.put(`${this.$axios.defaults.baseURL}/api/item/${todo.id}`,{
        name:todo.name
      },{
        headers:{Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`}
      }).then(res=>{
        console.log(res);
        this.todos[i].name = res.data.name+' '
        this.todos[i].edit = false
      }).catch(error=>{
        this.message = error.response.data.error
      })
    },

    logout:function(){
      this.$axios.post(`${this.$axios.defaults.baseURL}/api/logout`,
        {},
        {headers: {Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`,},
      }).then(res=>{
        nuxtStorage.localStorage.clear();
        this.$router.push('/login');
      }).catch(error=>{
        console.log(error);
        this.message = error.response.data.error
      }).finally(()=>{
      })
    }
  },

  watch: {
  }

  // async fetch(){
  //   await fetch(`${this.$axios.defaults.baseURL}/api/items`,{
  //     headers:{Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`}
  //   }).then(res => {
  //     this.todos = res.json()
  //   }).catch(err=>{
  //     console.log(err);
  //   })
  //   console.log(this.todos);
  // }

  // async function getData(){
  //   this.$axios.post(`${this.$axios.defaults.baseURL}/api/item/store`,
  //       {name:this.content},
  //       {headers: {Authorization: `Bearer ${nuxtStorage.localStorage.getData('token')}`,},
  //       }).then(res=>{
  //         this.todos.unshift(res.data);
  //         this.content = '';
  //       }).catch(error=>{
  //         console.log(error);
  //       }).finally(()=>{
  
  //       })
  // }
    // console.log(this.todos);

}
</script>
