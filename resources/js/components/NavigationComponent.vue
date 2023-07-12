<template>
    <div>
        <nav class="navigation">
            <router-link to="/task/home"> Tasks </router-link>
        </nav>
        <div class="App-content">
            <router-view />
        </div>
    </div>
</template>

<script>
    import 'bootstrap/dist/css/bootstrap.min.css';

    export default {
        name: 'NavigationComponent',

        data() {
            return {
            
            };
        },

        mounted() {

        },

        methods: {

        },
    };
</script>

<style lang="scss" scoped>

</style>
