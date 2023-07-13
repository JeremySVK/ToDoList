<template>
    <div>
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Task</h1>
                <button type="button" class="btn-close" @click="method" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="titleInput" class="form-label">Task title</label>
                    <input type="text" name="title" class="form-control" v-model="data.title" id="titleInput" placeholder="title">
                    <span style="color: red" v-if="v$.$error"> {{ (v$.data.title.$errors[0].$message) }} </span>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Task description</label>
                    <textarea class="form-control" name="description" v-model="data.description" id="exampleFormControlTextarea1" rows="3"></textarea>
                    <span style="color: red" v-if="v$.$error"> {{ (v$.data.description.$errors[0].$message) }} </span>
                </div>

                <div class="mb-3">
                    <select name="priority" class="form-select" v-model="data.priority" aria-label="Default select example">
                        <option value="" selected>Choose priority</option>
                        <option value="1">Low</option>
                        <option value="2">Medium</option>
                        <option value="3">High</option>
                    </select>
                </div>

                <div class="mb-3">
                    <select name="priority" class="form-select" v-model="data.status_id" aria-label="Default select example">
                        <option v-for="(status, index) in statuses" :value="status.id" >{{ status.title }}</option>
                    </select>
                </div>

                <div>
                    <label class="form-label"> Tags: </label>
                    <br>
                    <div class="form-check form-check-inline" v-for="(tag, index) in this.tags">
                        <input class="form-check-input"
                            type="checkbox"
                            :id="tag.id"
                            :value="tag.id"
                            v-model="data.tags">
                        <label class="form-check-label" :for="tag.id"> {{ tag.title }}</label>
                    </div>
                    <span style="color: red" v-if="v$.$error"> {{ (v$.data.tags.$errors[0].$message) }} </span>

                </div>

                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="form-control" v-model="data.deadline">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="method" class="btn btn-secondary">Close</button>
                <button type="submit" id="submit" @click="saveTask()" class="btn btn-primary">Save changes</button>
            </div>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import TaskConfig from './TaskConfig';
import useValidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'


export default {
    name: 'TaskForm',

    props: {
        tasksArray: {
            type: Object,
            required: false,
        },
        apiUrl: {
            type: String,
            required: true,
        },
        statuses: {
            type: Array,
            required: true,
        },
        parentTask: {
            type: Number,
            required: false,
        },
        method: {
            type: Function,
        },
    },

    data() {
        return {
            v$: useValidate(),
            data: TaskConfig.singleTask(),
            tags: [],
        };
    },
    validations() {
        return {
            data: {
                title: { required },
                description: { required },
                tags: {required }
            }
        }
    },

    mounted() {

    },

    created() {
        this.getTags()
    },

    methods: {

        getTags() {
            axios.get(this.apiUrl + 'tag-list')
                .then(response => {
                    this.tags = response.data
                })
        },
        
        saveTask() {
            if (this.parentTask) {
                this.data.parent_task_id = this.parentTask
            }
            console.log(this.v$);
            this.v$.data.$validate()
            if (!this.v$.$error)
            {
                axios.post(this.apiUrl + 'store', this.data)
                .then(response => {
                    this.$toast.success(response.data);

                    const statusArrayIndex = this.tasksArray.statuses.findIndex(status => status.id === this.data.status_id);
                    this.tasksArray.statuses[statusArrayIndex].tasks.push(this.data);
                }).catch(error => {
                    this.$toast.error(error);
                })
            } else {
                this.$toast.error('Form failed validation check for errors');
            }

        }
    },
};
</script>

<style lang="scss" scoped>

</style>
