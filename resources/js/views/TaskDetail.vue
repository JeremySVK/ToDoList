<template>

    <div class="container">
        <div class="row pt-5">
            <div class="col-12">

                <div class="mb-3">
                    <label for="titleEditInput" class="form-label">Task title: </label>
                    <input type="text" name="title" v-model="data.title" class="form-control" id="titleEditInput" placeholder="name@example.com">
                </div>

                <div class="mb-3">
                    <label for="descriptionEditInput" class="form-label">Example textarea</label>
                    <textarea class="form-control" name="description" v-model="data.description" id="descriptionEditInput" rows="3"> {{ data.description}} </textarea>
                </div>

                <div class="mb-3">
                    <label for="deadlineEditInput" class="form-label">Deadline</label>
                    <input type="date" id="deadlineEditInput" name="deadline" v-model="data.deadline" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label"> Status: </label>
                    <select name="status_id" class="form-select" aria-label="Default select example" v-model="data.status_id">
                        <option selected :value="data.status_id"> {{ data.status.title }}</option>

                    </select>
                </div>
                
                <div>
                    <label class="form-label"> Tags: </label>
                    <br>

                    <div class="form-check form-check-inline" v-for="(tag, tagIndex) in this.tags">
                        <input class="form-check-input"
                                name="tags[]"
                                type="checkbox"
                                v-model="data.tags"
                                :id="tag.id"
                                :value="tag.id"
                                :checked="data.tags.some((assignedTag) => assignedTag.id == tag.id)"
                                >
                        <label class="form-check-label" :for="tag.id"> {{ tag.title }}</label>
                    </div>
                </div>
                <button type="submit" id="submit" @click="saveChanges()" class="btn btn-primary m-2">Save changes</button>

                <button v-if="!data.parent_task_id" type="button" class="btn btn-primary  m-2" @click="toggleShow">New sub taskTask</button>
                    <Transition name="slide-fade">
                        <task-form v-show="show"
                            :apiUrl="apiUrl"
                            :statuses="statuses"
                            :tasksArray="data"
                            :parentTask="data.id"
                            :method="toggleShow"
                            >
                        </task-form>
                    </Transition>

                <div class="row">
                    <div class="col-12 pt-3">
                        <h2>Sub-tasks</h2>
                        <div class="mb-3" style="border: 1px solid lightgray; border-radius: 10px" v-for="(subTask, subTaskIndex) in data.sub_tasks.data">
                            <div class="row p-1">
                                <div class="col-5">
                                    <h5>{{ subTask.title }}</h5>
                                    <p> Dedaline: {{ subTask.deadline }}</p>
                                    <p> Status: {{ subTask.status.title }}</p>
                                    <p>
                                        <span v-for="(tag, tagIndex) in subTask.tags" class="btn btn-success m-1">
                                                {{ tag.title }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <strong> Description: </strong>
                                    <p>{{ subTask.description }}</p>
                                </div>
                                <div class="m-1">
                                    <button type="submit" @click="deleteTask(subTask.id)" class="btn btn-danger m-1"> Remove </button>
                                    <button v-if="subTask.id" class="btn btn-primary m-2">
                                        <router-link :to="{name: 'task-detail', params: {id: subTask.id}}" style="color: white;" @click="getTask()">Detail</router-link>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <Bootstrap5Pagination :data="data.sub_tasks" @pagination-change-page="getTask" />

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
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { ref } from 'vue';



export default {
    name: 'TaskDetail',
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

    props: {

    },

    computed: {

    },

    data() {
        return {
            data: TaskConfig.singleTask(),
            apiUrl: '/api/task/',
            tags: [],
            statuses: [],
        };
    },

    mounted() {

    },

    created() {
        this.getTask()
        this.getTags()
        this.getStatuses()
    },

    methods: {

        async getTask(page = 1) {
            const response = await axios.get(this.apiUrl + 'detail/' + this.$route.params.id + `?page=${page}`);
            this.data = await response.data;
        },

        getTags() {
            axios.get(this.apiUrl + 'tag-list')
                .then(response => {
                    this.tags = response.data
                })
        },

        getStatuses() {
            axios.get(this.apiUrl + 'status-list')
                .then(response => {
                    this.statuses = response.data
                })
        },

        deleteTask(id) {
            const index = this.data.sub_tasks.data.findIndex(subTask => subTask.id === id);
            this.data.sub_tasks.data.splice(index, 1);

            axios.post(this.apiUrl + 'delete/' + id)
                .then(response => {
                    console.log(response.data)
                }).catch(error => {
                    // this.axiosError(error)
                });
        },
        saveChanges() {
            axios.post(this.apiUrl + 'edit-task/' + this.$route.params.id, this.data)
                .then(response => {
                    this.$toast.success(response.data)
                    this.getTask()
                }).catch(error => {
                    this.$toast.error(error)
                })
        }

    },
    components: {
        Bootstrap5Pagination,
        TaskForm
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
