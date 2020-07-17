<template>
    <div class="container">
        <table class="table table-hover list-pizza">
            <thead>
                <th> # Pizza ID </th>
                <th> Name </th>
                <th> Selling price <i class="fa fa-eur" aria-hidden="true"></i> </th>
                <th> Ingredient </th>
                <th> Delete <i class="fa fa-trash" aria-hidden="true"></i></th>
                <th> Update <i class="fa fa-refresh" aria-hidden="true"></i></th>
            </thead>
            <tbody>
                <tr v-for="product in products">
                    <th>{{ product.id }}</th>
                    <th>{{ product.name }}</th>
                    <th>{{ product.price }} </th>
                    <th>
                        <ul class="list-ingredient">
                            <li v-for = "item in JSON.parse(product.ingredient)">{{ item.name }} - {{ item.price }} <i class="fa fa-eur" aria-hidden="true"></i></li>
                        </ul>
                    </th>
                    <th><button class="btn btn-danger" v-on:click = "deletePizza(product.id)"><i class="fa fa-trash" aria-hidden="true"></i></button></th>
                    <th><button class="btn btn-success" v-on:click = "updateIngredient(product)" ><i class="fa fa-refresh" aria-hidden="true"></i></button></th>
                </tr>
            </tbody>
        </table>
        <div v-if = 'updated' class = 'updatedModal'> 
            <div class="body col-md-12">
                <h3 align="center"><i class="fa fa-info" aria-hidden="true"></i>{{ name }}</h3>
                <input class="form-control" type = 'text' :value = 'name' />
                <div class='row' v-for = 'p in newdata' ref = 'ingredient'>
                    <input class="form-control ml-3 col-md-6" type = 'text' :value = "p.name" ref = 'name' />
                    <div class="col-md-2">Price</div>
                    <input class="form-control ml-2 col-md-3" type = 'number' :value = "p.price" ref = 'price'  />
                </div>
                <button class="btn btn-info" v-on:click = "saveUpdate()">save</button>
                <button class="btn btn-success" v-on:click = "updated = false">close</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                products:[],
                updated : false,
                name : '' ,
                newdata : [],
                array : [] ,
                updateId : 0
            }
        },
        mounted() {
            axios.get('/api/allpizza').then(res => {
                this.products = res.data;
            })
        },
        methods:{
            deletePizza(id = -1) {
                axios.post('/api/pizza/delete',{id:id}).then(res=>{
                    this.products = res.data;
                })
            },
            updateIngredient(data = []) {
                this.updated = true ; 
                this.name = data.name ;
                this.newdata = JSON.parse(data.ingredient) ;
                this.updateId = data.id ;
            },
            saveUpdate() {
                var arr = this.$refs['ingredient'];
                var obj = {};
                console.log(arr);
                for(let i = 0 ; i < arr.length ; i++) {
                   obj = { name : arr[i].children[0].value , price : arr[i].children[2].value } ;
                   this.array.push(obj);
                }
                axios.post('/api/update',{ pizzaname:this.name , id : this.updateId , ingredient : JSON.stringify(this.array) }).then(
                    res => {
                        // default value 
                        this.products = res.data ;
                        this.updated = false;
                        this.newdata = [] ;
                        this.array = [] ;
                    }

                );

            }   
        }
    }
</script>
