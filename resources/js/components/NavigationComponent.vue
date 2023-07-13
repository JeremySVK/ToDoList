<template>
    <div>
        <nav class="navigation">
            <button class="btn btn-primary m-3">
                <router-link to="/task/home" style="color: white"> Dashboard </router-link>

            </button>
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
