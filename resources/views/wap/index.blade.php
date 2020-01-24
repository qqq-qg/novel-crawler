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
            margin: 0;
            list-style: none;
        }

        img {
            width: 110px;
            height: 150px;
            margin: auto;
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
                    <li v-for="item in bookshelf" style="float:left;margin:10px;">
                        <a href="/s/r?id=1">
                            <img v-lazy="item">
                        </a>
                    </li>
                </ul>
            </div>
        </mt-tab-container-item>
        <mt-tab-container-item id="category">
            <mt-cell v-for="n in 5" title="tab-container 2"></mt-cell>
        </mt-tab-container-item>
        <mt-tab-container-item id="myHome">
            <mt-cell v-for="n in 7" title="tab-container 3"></mt-cell>
        </mt-tab-container-item>
    </mt-tab-container>

    <mt-tabbar fixed v-model="active">
        <mt-tab-item id="bookshelf">
            <img slot="icon" src="/mint-ui/image/100x100.jpg">
            书架
        </mt-tab-item>
        <mt-tab-item id="category">
            <img slot="icon" src="/mint-ui/image/100x100.jpg">
            分类
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
                bookshelf: [
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                    '/mint-ui/image/100x100.jpg',
                ],
            }
        },
        methods: {
            handleClick: function () {
                this.$toast('Hello world!')
            }
        }
    })
</script>
</html>
