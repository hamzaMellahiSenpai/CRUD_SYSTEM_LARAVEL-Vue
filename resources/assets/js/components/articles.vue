<template>
<div class="container">
    <div class="card">
        <div class="card-header">Add Article</div>
        <div class="card-body">
            <form @submit.prevent = "AddArticle">
                <div class="form-group">
                    <label for="">title</label>
                    <input type="text" class="form-control" v-model="article.title">
                </div>
                <div class="form-group">
                    <label for="">Body</label>
                    <input type="text" class="form-control" v-model="article.body">
                </div>
                <button class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <nav aria-label="Page navigation example mx-5">
        <ul class="pagination">
            <li @click="fetchArticles(pagination.prev_page_url)" v-bind:class="[{ disabled : !pagination.prev_page_url }]" class="page-item list-group-item-primary"><a class="page-link" href="#">Previous</a></li>
            <li  class="page-item"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }} </a></li>
            <li @click="fetchArticles(pagination.next_page_url)" v-bind:class="[{ disabled : !pagination.next_page_url }]" class="page-item list-group-item-danger"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
    <h1>articles</h1>
    <input type="text" class="form-control" v-model="search">
    <div  class="card my-3 mx-3" v-for="article in filtredArticles" v-bind:key="article.id">
        <div class="card-header">
            <h2>{{ article.title }}</h2>
        </div>
        <div class="card-body">
            <p>{{ article.body}}</p>
            <hr>
            <!--<button @click="editArticle(article)" class="btn btn-primary" data-toggle="modal" data-target="#editArticle">
                Launch demo modal
            </button>-->
            <button @click="DeleteArticle(article.id)" class="btn btn-danger">Delete</button>
            <button @click="editArticle(article)" class="btn btn-primary">Edit</button>
        </div>
    </div>
    <!-- Modal -
    <div v-show="showBox" class="modal fade" id="editArticle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent = "AddArticle">
                        <div class="form-group">
                            <label for="">title</label>
                            <input type="text" class="form-control" v-model="article.title">
                        </div>
                        <div class="form-group">
                            <label for="">Body</label>
                            <input type="text" class="form-control" v-model="article.body">
                        </div>
                        <hr>
                        <button class="btn btn-primary" type="submit">update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>-->
</div>
</template>

<script>
    export default {
        data(){
            return {
                articles:[],
                article:{
                    id:"",
                    title:"",
                    body:""
                },
                search:"",
                article_id:'',
                pagination:{},
                edit:false,
                showBox:true
            }
        },
        created() {
            this.fetchArticles();
            console.log('Component mounted.');
        },
        computed: {
            filtredArticles:function(){
                return this.articles.filter((article) => {
                    return article.title.match(this.search) || article.body.match(this.search);
                })
            }
        },
        methods: {
            fetchArticles(page_url){
                let vm = this;
                page_url = page_url || "api/articles";
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.articles = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta , links){
                let pagination = {
                    current_page : meta.current_page,
                    last_page    : meta.last_page,
                    next_page_url : links.next,
                    prev_page_url : links.prev,
                };
                this.pagination = pagination
            },
            DeleteArticle($id){
                if(confirm('Are you sure sir ?')){
                    fetch("api/article/" + $id, {
                        method : "delete"
                    }).then(res => res.json())
                      .then(data => {
                        alert("article is deleted");
                        this.fetchArticles("api/articles")
                    })
                }
            },
            AddArticle(){
                if(this.edit === false){
                    fetch('api/article', {
                        method:"post",
                        body: JSON.stringify(this.article),
                        headers : {
                            "content-type" : "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then( data => {
                        alert('The article added');
                        this.fetchArticles();
                        this.article.title = "";
                        this.article.body = "";
                    })
                }else {
                    fetch('api/article', {
                        method:"put",
                        body:JSON.stringify(this.article),
                        headers : {
                            "content-type" : "application/json"
                        }
                    }).then(res => res.json)
                    .then(data => {
                        this.article.title = "";
                        this.article.body = "";
                        this.edit = false;
                        this.fetchArticles();
                        this.showBox = false
                    })
                }
            }, 
            editArticle(article){
                this.edit = true;
                this.article.id = article.id;
                this.article.article_id = article.id;
                this.article.title = article.title;
                this.article.body = article.body;
            }
        }
    }
</script>
