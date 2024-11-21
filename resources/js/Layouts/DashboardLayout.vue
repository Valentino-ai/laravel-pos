<template>
    <div>
        <NavbarMasterdata @updateAuth="handleAuthChange" />
        <div class="container-fluid page-body-wrapper">
            <SidebarMasterdata />
            <div class="main-panel">
                <div class="content-wrapper">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import NavbarMasterdata from '../Components/NavbarMasterdata.vue';
import SidebarMasterdata from '../Components/SidebarMasterdata.vue';
import { ref } from 'vue';

const handleAuthChange = (authStatus) => {
    
};
</script>