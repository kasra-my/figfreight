<template>
    <div class="ettrtts">
        <div class="tableFilters">
            <input class="input" type="text" v-model="tableData.search" placeholder="Search Table" @input="getEttrtts()">
            
            <div class="control">
                <div class="select">
                    <select v-model="tableData.lenght" @change="getEttrtts()">
                        <option value="30" selected="selected">30</option>
                        <option value="60">60</option>
                        <option value="90">90</option>
                    </select> 
                </div>
            </div>
        </div>
    
        <datatable :columns="columns" :sortkey="sortkey" :sortOrders="sortOrders" @sort=sortBy">
        
            <tbody>
                <tr v-for="ettrtt in ettrtts" :key="ettrtt.id">
                    <td>{{ ettrtt.zone_from }}</td>
                    <td>{{ ettrtt.zone_to }}</td>
                    <td>{{ ettrtt.ett }}</td>
                    <td>{{ ettrtt.mean_rtt }}</td>
                </tr>
            </tbody>
        </datatable>
        <pagination :pagination="pagination"
            @prev="getEttrtts(pagination.prevPageUrl)"
            @next="getEttrtts(pagination.nextPageUrl)">
        
        </pagination>
    </div>
    
    <a href="/processcsv" type="button" class="btn btn-default">Calculate mean</button>
</template>

<script>
    import Database from './Datatable.vue';
    import Database from './Pagination.vue';

    export default {
        components: {datatable: Datatable, pagination: Pagination},
    }; 
</script>
