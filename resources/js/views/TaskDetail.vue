<template>
    <div class="container">
        <div class="row pt-5">
            <div class="col-12">

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Task title: </label>
                    <input type="text" name="title" v-model="data.title" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                    <textarea class="form-control" name="description" v-model="data.description" id="exampleFormControlTextarea1" rows="3"> {{ data.description}} </textarea>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Deadline</label>
                    <input type="date" name="deadline" v-model="data.deadline" class="form-control">
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
                                :id="tag.id"
                                :value="tag.id"
                                :checked="data.tags.some(assignedTag => assignedTag.id === tag.id)"
                                >
                        <label class="form-check-label" :for="tag.id"> {{ tag.title }}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 pt-3">
                        <h2>Sub-tasks</h2>
                        <div class="mb-3" style="border: 1px solid lightgray; border-radius: 10px" v-for="(subTask, subTaskIndex) in data.sub_tasks">
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
                                    <button type="submit" @click="deleteTask(subTask.id)" class="btn btn-danger"> X </button>
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
import axios from 'axios'
import TaskConfig from '@/views/TaskConfig'

export default {
    name: 'TaskDetail',

    props: {
        // apiUrl: {
        //     type: String,
        //     required: true,
        // }
        statuses: {
            type: Array,
            required: false,
        }
    },

    data() {
        return {
            data: TaskConfig.singleTask(),
            apiUrl: '/api/task/',
            tags: [],
        };
    },

    mounted() {

    },

    created() {
        this.getTask()
        this.getTags()
    },

    methods: {

        getTask() {
            axios.get(this.apiUrl + 'detail/' + this.$route.params.id)
                .then(response => {
                    this.data = response.data;
                })
        },

        getTags() {
            axios.get(this.apiUrl + 'tag-list')
                .then(response => {
                    this.tags = response.data
                })
        },
        deleteTask(id) {
            const index = this.data.sub_tasks.findIndex(subTask => subTask.id === id);
            this.data.sub_tasks.splice(index, 1);

            axios.post(this.apiUrl + 'delete/' + id)
                .then(response => {
                    console.log(response.data)
                }).catch(error => {
                    // this.axiosError(error)
                });
        }

    },
};
</script>

<style lang="scss" scoped>

</style>
