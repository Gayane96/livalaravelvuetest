<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">New Pizza <button v-on:click="savepizza()" class="float-right btn-success btn">save <i class="fa fa-floppy-o"></i></button></div>
                    <div class="card-body">
                        <form class="container">
                            <div class="form-group">
                                <label class="label-control">Name <b>*</b></label>
                                <input type="text" v-model="pizzaname" class="form-control" name="name" placeholder="Name"/>
                            </div>
                            <div>
                                <label class="label-control">Ingredient <b>*</b></label>
                                <div class="row container">
                                    <input type="text" v-model='ingredientname' class="form-control col-md-6" name="ingredient[]" placeholder="Ingredient" />
                                    <span class="col-md-3">Ingredient price <i class="fa fa-eur" aria-hidden="true"></i></span>
                                    <input type="number" v-model='ingredientprice' class="form-control col-md-3" step="0.1" min="0.1" value="0.1" name="price[]"/>
                                </div>  
                            </div>
                            <div  v-if="ingredient.length > 0" class='all-ingredients'>
                                <h4 class="label-control">Ingredients for {{ pizzaname }} <b>Total Price : {{ totalprice.toFixed(2) }} </b> <i class="fa fa-eur" aria-hidden="true"></i></h4>
                                <ul>
                                    <li v-for='(item,index) in ingredient'> 
                                            <b>name</b> : {{ item.name }} 
                                            <b>price:</b> {{item.price}} 
                                            <i class="fa fa-eur" aria-hidden="true"></i>
                                            <i class="fa fa-trash btn btn-link" arid-hidden = "true" v-on:click='deleteIngredient(index)'></i>
                                    </li>
                                </ul>
                            </div>
                            <div class="float-right mt-3">
                                <a href="#" class="btn btn-success" v-on:click="newIngredient()"><i class="fa fa-plus-circle" aria-hidden="true"> new ingredient</i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ingredient:[],
                pizzaname:'',
                ingredientname:'',
                ingredientprice:0.1,
                totalprice:0
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            newIngredient() {
                this.totalprice = 0;
                if(this.ingredientname != '') {
                    this.ingredient.push({name:this.ingredientname,price:this.ingredientprice});
                    this.ingredient.map( a => this.totalprice+=Number(a.price));
                    var selling  = (this.totalprice * 50 / 100) ;
                    this.totalprice = this.totalprice + selling;
                    this.ingredientname = '';
                    this.ingredientprice = 0.1;
                }else {
                    alert("ingredient name can't empty");
                }
            },
            deleteIngredient(i = -1) {
                this.ingredient.splice(i,1);
            },
            savepizza() {
                var data = {
                    pizzaname:this.pizzaname,
                    ingredient:JSON.stringify(this.ingredient)
                };
                if(data.pizzaname!='' && this.ingredient.length > 0) { 
                    axios.post('/api/newpizza',data).then(res=>{
                        if(res.data != 'error') {
                            this.ingredient = [],
                            this.pizzaname = '',
                            this.ingredientname = '',
                            this.ingredientprice = 0.1,
                            this.totalprice = 0;
                        }else {
                            alert('have error');
                        }
                    })
                }else {
                    alert("data can't be empty");
                }
            }
        }
    }
</script>
