<template>

<button v-on:click="count++">You clicked me {{ count }} times. {{links}}</button>



</template>

<script>
export default {

    props: ['links'],
    data: function () {
        return {
            count: 0
        }
    }





}
</script>
