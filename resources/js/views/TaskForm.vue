<template>
    <div>
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Task title</label>
                    <input type="text" name="title" class="form-control" v-model="data.title" id="exampleFormControlInput1" placeholder="title">
                </div>
                {{ console.log(data.title) }}
                <input type="radio" name="parent_task_id" :value="this.parentTask" v-model="data.parent_task_id">

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Task description</label>
                    <textarea class="form-control" name="description" v-model="data.description" id="exampleFormControlTextarea1" rows="3"></textarea>
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
                    {{ console.log(data.tags) }}
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control" v-model="data.deadline">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="submit" @click="saveTask()" class="btn btn-primary">Save changes</button>
            </div>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import TaskConfig from './TaskConfig';

export default {
    name: 'TaskForm',

    props: {
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
    },

    data() {
        return {
            data: TaskConfig.singleTask(),
            tags: [],
        };
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
            axios.post(this.apiUrl + 'store', this.data)
                .then(response => {
                    console.log(response.data);
                })
        }
    },
};
</script>

<style lang="scss" scoped>

</style>
