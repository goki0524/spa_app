<template>
  
  <div>
    please <router-link to="/login">Login.</router-link>
    <!-- modal -->
    <script type="text/x-template" id="modal-template">
    <transition name="modal">
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <slot name="header">
                    default header
                    </slot>
                </div>
                <div class="modal-body">
                    <slot name="body">
                    default body
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                    <button class="btn btn-primary" @click="$emit('close')">
                        OK
                    </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
    </transition>
    </script>
    <!-- modal -->
    
    <!-- edit modal -->
        <modal v-if="showModal" @close="showModal = false">
          <h3 slot="header">編集</h3>
          <h4 slot="body">
            {{ tasks[editId].name}}
            <input type="text" v-model="tasks[editId].name" class="col-md-8 offset-md-2 mt-5 form-control">
          </h4>
        </modal>
    <!-- edit modal -->

    <div>
      <strong>Hello, TODO App!</strong>
      <p>Your tasks here.</p>

      <ul v-for="task in tasks" :key="task.id">
        <li v-if="task.is_done">
          <strike> {{ task.name }} </strike>
        </li>
        <li v-else >
          <span @dblclick="editModal(task.id)">{{ task.name }}</span>
        </li>
        <button @click="completeTask(task)" class="btn btn-sm btn-success" v-if="task.is_done">Undo</button>
        <button @click="completeTask(task)" class="btn btn-sm btn-success" v-else>Done</button>

        <button @click="removeTask(task)" class="btn btn-sm btn-danger">Remove</button>
      </ul>

      <div class="form-group">
        <div class="alert alert-danger" role="alert" v-if="showAlert">
          {{ alertMessage }}
        </div>
        <input type="text" class="form-control"
            v-model="name" @keyup.enter="addTask" placeholder="new task...">
        <button class="btn btn-primary" disabled="disabled" v-if="name === ''">
          Add task
        </button>
        <button class="btn btn-primary" @click='addTask' v-else>
          Add task
        </button>
      </div>
    </div>
  </div>
</template>
<script>
  import http from '../services/http'

  export default {
    mounted() {
      this.fetchTasks()
    },
    data() {
      return {
        tasks: [],
        name: '',
        showAlert: false,
        showModal: false,
        alertMessage: '',
        editId: 0,
      }
    },
    methods: {
      fetchTasks () {
        // TODO: not to send request when the user is not authenticated
        http.get('tasks', res => {
          this.tasks = res.data
        })
      },
      addTask () {
        if (this.name === '') {
          this.showAlert = true
          this.alertMessage = 'Task name should not be blank.'
          return false
        }
        http.post('tasks', {name: this.name}, res => {
          this.tasks[res.data.id] = res.data
          this.name = ''
          this.showAlert = false
          this.alertMessage = ''
        })
      },
      completeTask (task) {
        http.put('tasks/' + task.id, {is_done: !task.is_done}, res => {
          this.tasks[task.id] = res.data
          this.$forceUpdate()
        })
      },
      removeTask (task) {
        http.delete('tasks/' + task.id, {}, () => {
          delete this.tasks[task.id]
          this.$forceUpdate()
        })
      },
      editModal (id){
        this.editId = id;
        this.showModal = true;
      },
    }
  }

</script>
