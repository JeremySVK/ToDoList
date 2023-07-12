import { createRouter, createWebHistory } from 'vue-router';
import TaskList from '@/views/TaskList.vue';
import TaskDetail from '@/views/TaskDetail.vue';


const routes = [
    {
        path: '/task/home',
        component: TaskList,
    },
    {
        path: '/task/detail/:id',
        component: TaskDetail,
        name: 'task-detail',
        props: true,
    }
];


const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router
