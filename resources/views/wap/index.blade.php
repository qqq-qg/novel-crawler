<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/mint-ui/css/mint-ui.css">
    <style>
        ul li {
            padding: 0;
            margin: 5px;
            list-style: none;
            width: 30%;
            float: left;
        }

        img {
            width: 100%;
            /*height: 150px;*/
            margin: auto;
            /*float: left;*/
        }
    </style>
</head>
<body>
<div id="app">
    <mt-header fixed title="书架" v-if="active == 'bookshelf'"></mt-header>
    <mt-tab-container v-model="active">
        <mt-tab-container-item id="bookshelf">
            <div style="margin-top:50px;">
                <ul>
                    <li v-for="item in books" style="" :key="item.key">
                        <div @click="reedBook(item.id)">
                            <img v-lazy="'/mint-ui/image/100x100.jpg'">
                            <span v-text="item.title"></span>
                        </div>
                    </li>
                </ul>
            </div>
        </mt-tab-container-item>
        <mt-tab-container-item id="myHome">
            myHome
        </mt-tab-container-item>
    </mt-tab-container>

    <mt-tabbar fixed v-model="active">
        <mt-tab-item id="bookshelf">
            <img slot="icon" src="/mint-ui/image/100x100.jpg">
            书架
        </mt-tab-item>
        <mt-tab-item id="myHome">
            <img slot="icon" src="/mint-ui/image/100x100.jpg">
            我的
        </mt-tab-item>
    </mt-tabbar>
</div>
</body>
<script src="/mint-ui/js/vue.js"></script>
<script src="/mint-ui/js/mint-ui.js"></script>
<script>
    new Vue({
        el: '#app',
        data: () => {
            return {
                active: 'bookshelf',
                books: @json($data),
            }
        },
        methods: {
            reedBook(id) {
                window.location.href = '/s/r?id=' + id;
            }
        }
    })
</script>
</html>
