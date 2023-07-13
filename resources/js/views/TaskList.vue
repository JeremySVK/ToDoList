<template>
    <div class="container">
        <div class="row pt-5 mb-2">
            <div class="col-12">
                <div class="d-grid gap-2 d-md-block">
                    <button type="button" class="btn btn-primary m-1" @click="toggleShow">New Task</button>
                    <Transition name="slide-fade">
                        <task-form v-show="show"
                            :apiUrl="apiUrl"
                            :statuses="statuses"
                            :tasksArray="data"
                            :method="toggleShow"
                            >
                        </task-form>
                    </Transition>
                    <button type="button" class="btn btn-primary m-1">New Tag</button>
                </div>
            </div>
        </div>
        <div class="row" style="border: 1px solid lightgrey; border-radius:10px; min-width:100%; align-content:center">
            <div v-if="data.statuses" class="col-4" v-for="(status, statusIndex) in data.statuses">
                <div class="col-12" dropzone="true"
                    @drop="onDrop($event, status.id, statusIndex)"
                    @dragenter.prevent
                    @dragover.prevent
                >
                    <h1>{{ status.title }}</h1>
                    <div v-if="status.tasks" v-for="(task, index) in status.tasks" :key="task.id">
                            <div class="card-mt-2 task-card" v-if="(task.parent_task_id == null)"
                                draggable="true"
                                @dragstart="startDrag($event, task, statusIndex)"
                            >

                                <div class="m-1">
                                <button type="submit" @click="deleteTask(task.id, statusIndex)" class="btn btn-danger"> X </button>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">{{ task.title }}</h5> <strong>deadline:</strong> {{ task.deadline }}
                                    <p class="card-text">{{ task.description }}</p>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"> Tags: </h6>
                                    <span class="btn btn-success m-1" v-for="(tag, index) in task.tags">
                                        {{ tag.title }}
                                    </span>
                                </div>
                                <ul class="list-group list group-flush">
                                    <li class="list-group-item"> <strong>Sub-tasks:</strong> </li>
                                    <li class="list-group-item" v-for="(subTask, index) in task.sub_tasks">
                                        <strong>{{ subTask.title }}</strong>
                                        {{ subTask.description }}
                                        <br>
                                    </li>
                                </ul>

                                <button v-if="task.id" class="btn btn-primary mt-2">
                                    <router-link :to="{name: 'task-detail', params: {id: task.id}}" style="color: white;">Detail</router-link>
                                </button>
                            </div>
                        </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import TaskConfig from '@/views/TaskConfig';
    import TaskForm from '@/views/TaskForm.vue';
    import { ref } from 'vue';

    export default {
        name: 'TaskList',

        setup() {
            const show = ref(false);

            const toggleShow = () => {
                show.value = !show.value;
            };

            return {
                show,
                toggleShow,
            };
        },

        computed: {

            statuses() {
                const statusesArray = this.data.statuses.map(status => {
                    return {
                        id: status.id,
                        title: status.title
                    };
                });
                return statusesArray;
            }
        },

        data() {

            return {
                apiUrl: '/api/task/',
                data: TaskConfig.data(),
            };
        },

        created() {
            this.getTasks();
        },

        methods: {

            getTasks() {
                axios.get(this.apiUrl)
                    .then(response => {
                        this.data.statuses = response.data;
                    })
                    .catch(error => {
                        this.$toaster.error(error);
                    });
            },

            startDrag(event, task, statusIndex) {
                event.dataTransfer.dropEffect = 'move'
                event.dataTransfer.effectAllowed = 'move'
                event.dataTransfer.setData('taskID', task.id)
                event.dataTransfer.setData('statusIndex', statusIndex)
            },

            onDrop(event, statusID) {
                const taskID = event.dataTransfer.getData('taskID')
                const originalStatus = event.dataTransfer.getData('statusIndex')
                const task = this.data.statuses[originalStatus].tasks.find(task => task.id == taskID)

                task.status_id = statusID

                axios.post(this.apiUrl + 'edit-status/' + task.id, task)
                    .then(response => {
                        this.getTasks();
                    })
            },

            deleteTask(id, statusIndex) {

                const index = this.data.statuses[statusIndex].tasks.findIndex(task => task.id === id);
                this.data.statuses[statusIndex].tasks.splice(index, 1);

                axios.post(this.apiUrl + 'delete/' + id)
                    .then(response => {
                        this.$toast.info(response.data);
                    }).catch(error => {
                        this.$toast.error(error);
                    });
            }
        },
        components: {
            TaskForm,
        }
    };
</script>

<style lang="scss" scoped>

    .task-card {
        border: 1px solid lightgray;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 20px;
    }

    .slide-fade-enter-active {
  transition: all 0.3s ease-out;
    }

    .slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
    }

</style>
