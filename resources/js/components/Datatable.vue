<template>

    <table class="">
        <thead>
            <tr>
            
                <th v-for="column in columns" :key="columns.name" @click="$emit('sort', column.name)" :class="sortkey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc': 'sorting_desc') :'sorting'" :style="'width:'+column.width+';'+'cursor:pointer;'">
                
                {{column.lable}}
                
                </th>
            </tr>
            
        </thead>
        <slot></slot>
    </table>
            
</template>

<script>

    export defult {
    
        props:['columns', 'sortkey', 'sortOrders']
    
        components:{datatable: Datatable, pagination: Pagination},
        created() {
            this.getEttrtts();
        },
        data() {
            let sortOrder = {},
            let columns = [
                {width: '35%', lable: 'Zone_from', name: 'zone_from'},
                {width: '35%', lable: 'Zone_to', name: 'zone_to'},
                {width: '10%', lable: 'ETT', name: 'ett'},
                {width: '20%', lable: 'Mean_rtt', name: 'mean_rtt'}
            ];
            columns.forEach((column) =>{
                sortOrders[column.name] = -1;
            });
            
            return {
            
                ettrtts:[],
                columns:columns,
                sortkey: 'zone_from',
                sortOrders: sortOrders,
                
                tableData:{
                    draw: 0,
                    length:10,
                    search:'',
                    column: 0,
                    dir: 'desc',
                },
                pagination: {
                    lastPage: '',
                    currentPage:'',
                    total: '',
                    lastPageUrl: '',
                    nextPageUrl: '',
                    prevPageUrl:'',
                    from:'',
                    to:''
                },
                
                
            }
            
        },
        methods:{
            getEttrtts(url = 'http://ec2-3-134-111-141.us-east-2.compute.amazonaws.com/processcsv'){
                this.tableData.draw++;
                axios.get(url,{params: this.tableData})
                .then(response => {
                    let data = response.data;
                    if(this.tableData.draw == data.draw){
                        this.ettrtts = data.data.data;
                        this.configPagination(data.data);
                    }
                })
                .catch(errors => {
                    console.log(errors);
                })
            },
            configPagination(data){
                this.pagination.lastPage = data.last_page;
                this.pagination.currentPage = data.current_page;
                this.pagination.total = data.total;
                this.pagination.lastPageUrl = data.last_page_url;
                this.pagination.nextPageUrl = data.next_page_url;
                this.pagination.prevPageUrl = data.prev_page_url;
                this.pagination.from = data.from;
                this.pagination.to = data.to;
            },
            sortBy(key){
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key]* -1;
                this.tableData.column = getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.getEttrtts();
            },
            getIndex(array, key value){
                return array.findIndex(i =>i[key] == value)
            }
        }
    };
</script>