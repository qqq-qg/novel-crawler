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

        .chapter-title {
            font-size: 26px;
        }

        .chapter-split-line {
            height: 40px;
            line-height: 40px;
            text-align: center;
            padding-bottom: 30px;
        }

        .chapter-split-line .line {
            display: inline-block;
            width: 35%;
            border-top: 1px dotted #DBDBDB;
        }

        .chapter-split-line .txt {
            color: #DBDBDB;
            vertical-align: middle;
            font-size: 14px;
        }

        .page-infinite-wrapper {
            margin-top: -1px;
            overflow: scroll;
        }

        .page-infinite-loading {
            text-align: center;
            height: 50px;
            line-height: 50px;
        }

        .page-infinite-loading div {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }

        .mint-popup-chapter {
            width: 75%;
            height: 100%;
            background-color: #fff;
        }

        .mint-popup-chapter .mint-button {
            position: absolute;
            width: 90%;
            top: 50%;
            left: 5%;
            transform: translateY(-50%);
        }

        .mint-popup-bottom, .mint-popup-top {
            width: 100%;
        }

        .mint-popup-bottom .picker-slot-wrapper, .picker-item {
            backface-visibility: hidden;
        }

        .mint-popup-bottom .mt-range {
            width: 100%;
        }

        .mint-popup-bottom .mint-cell-value {
            flex: 2.5;
            position: relative;
        }

        .chapter-content {
            margin-block-start: 0;
            margin-block-end: 0;
            padding-inline-start: 10px;
            padding-inline-end: 10px;
            margin-top: 30px;
        }

        .color-setting div {
            display: inline-block;
            float: left;
            width: 44px;
            height: 44px;
            margin-right: 10px;
            border-radius: 44px;
        }

        .click-area {
            position: absolute;
            display: block;
            background-color: rgb(219, 219, 219);
            opacity: 0.5;
        }

        .listSelected {
            background-color: #dbdbdb;
        }

        .mint-header {
            background-color: #000000;
        }
    </style>
</head>
<body style="margin:0;padding:0;">
<div id="app">
    <div class="page-infinite-wrapper" ref="wrapper" @click="centerClick"
         :style="{ height: wrapperHeight + 'px','line-height': settingConfig.lineHeight.value+'px',
         'background-color': settingConfig.backgroundColor.value }">
        <ul v-infinite-scroll="loadMore"
            infinite-scroll-disabled="loading"
            infinite-scroll-distance="100"
            class="chapter-content"
            :style="{ 'font-size': settingConfig.fontSize.value + 'px' }"
        >
            <li v-for="chapter in list">
                {{--标题--}}
                <div class="chapter-title"><span v-text="chapter.title"></span></div>
                {{--正文--}}
                <div v-html="chapter.content"></div>
                {{--分割线--}}
                <div class="chapter-split-line">
                    <span class="line"></span>
                    <span class="txt">本章完</span>
                    <span class="line"></span>
                </div>
            </li>
        </ul>
        <p v-show="loading && getNext" class="page-infinite-loading">
            <mt-spinner type="fading-circle"></mt-spinner>
            正在获取下一章...
        </p>
        {{--<div class="click-area"--}}
        {{--:style="{--}}
        {{--width: (ePoint.right.x-ePoint.left.x) + 'px',--}}
        {{--height: (ePoint.right.y-ePoint.left.y) + 'px',--}}
        {{--top: ePoint.left.y + 'px',--}}
        {{--left: ePoint.left.x + 'px'--}}
        {{--}"></div>--}}
    </div>

    {{--操作--}}
    <mt-popup v-model="popupVisibleOperation" position="top" class="mint-popup-top" :modal="false">
        <mt-header :title="book.title">
            <mt-button icon="back" slot="left" @click="back"></mt-button>
        </mt-header>
    </mt-popup>
    <mt-popup v-model="popupVisibleOperation" position="bottom" class="mint-popup-bottom" :modal="false">
        <div v-show="!popupVisibleSetting">
            <mt-button plain type="default" @click="showList" style="width:50%;float:left;">目录</mt-button>
            <mt-button plain type="default" @click="setting" style="width:50%;">设置</mt-button>
        </div>
        <div v-show="popupVisibleSetting">
            {{--字体大小--}}
            <mt-cell :title="settingConfig.fontSize.title" :label="''+settingConfig.fontSize.value">
                <mt-range v-model="settingConfig.fontSize.value" :min="settingConfig.fontSize.min"
                          :max="settingConfig.fontSize.max" :step="settingConfig.fontSize.step || 2">
                    <div slot="start" v-if="settingConfig.fontSize.start"><span
                                v-text="settingConfig.fontSize.start"></span></div>
                    <div slot="end" v-if="settingConfig.fontSize.end"><span v-text="settingConfig.fontSize.end"></span>
                    </div>
                </mt-range>
            </mt-cell>
            {{--行高--}}
            <mt-cell :title="settingConfig.lineHeight.title" :label="''+settingConfig.lineHeight.value">
                <mt-range v-model="settingConfig.lineHeight.value" :min="settingConfig.lineHeight.min || 14"
                          :max="settingConfig.lineHeight.max" :step="settingConfig.lineHeight.step || 2">
                    <div slot="start" v-if="settingConfig.lineHeight.start"><span
                                v-text="settingConfig.lineHeight.start"></span></div>
                    <div slot="end" v-if="settingConfig.lineHeight.end"><span
                                v-text="settingConfig.lineHeight.end"></span></div>
                </mt-range>
            </mt-cell>
            {{--背景色--}}
            <mt-cell :title="settingConfig.backgroundColor.title" :label="''">
                <div class="color-setting">
                    <div style="background-color: #F6EBCD;" @click="backgroundColorSet('#F6EBCD')"></div>
                    <div style="background-color: #CDEFCE;" @click="backgroundColorSet('#CDEFCE')"></div>
                </div>
            </mt-cell>
        </div>
    </mt-popup>

    {{--章节列表 :showIndicator="false"--}}
    <mt-popup v-model="popupVisibleChapterList" position="left" class="mint-popup-chapter">
        <mt-index-list>
            <mt-index-section v-for="(cl, index) in chapterGroup" :index="cl[0].chapter_index+''" :key="index">
                <mt-cell v-for="chapter in cl" :title="chapter.title" :key="chapter.chapter_index"
                         :class="chapter.chapter_index == chapterIndex ? 'listSelected':''"
                         @click.native="chooseChapter(chapter.chapter_index)"></mt-cell>
            </mt-index-section>
        </mt-index-list>
    </mt-popup>
</div>
</body>
<script src="/mint-ui/js/vue.js"></script>
<script src="/mint-ui/js/mint-ui.js"></script>
<script src="/mint-ui/js/lockr.min.js"></script>
<script src="/mint-ui/js/lodash.min.js"></script>
<script src="/js/jquery.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data: () => {
            return {
                book: @json($data),
                list: [],
                loading: false,
                getNext: true,
                wrapperHeight: 0,
                chapterIndex: 0,
                contentUrl: '/s/c',
                chapterListUrl: '/s/l',

                popupVisibleOperation: false,
                popupVisibleSetting: false,
                popupVisibleChapterList: false,
                chapterGroup: [],

                settingConfig: {
                    fontSize: {
                        title: '字体大小', start: '12', end: '24', min: 12, max: 24, value: 20, step: 2, barHeight: 5
                    },
                    lineHeight: {
                        title: '行高', start: '20', end: '50', min: 20, max: 50, value: 32, step: 2, barHeight: 5
                    },
                    backgroundColor: {
                        title: '背景颜色', value: '#F6EBCD', list: ['#F6EBCD', '#CDEFCE', '#F6EBCD']
                    },
                },

                ePoint: {
                    left: {x: 0, y: 0},
                    right: {x: 0, y: 0},
                }
            }
        },
        created() {
            this.initConfig();
        },
        mounted() {
            this.wrapperHeight = document.documentElement.clientHeight - this.$refs.wrapper.getBoundingClientRect().top;
        },
        methods: {
            initConfig() {
                let setting = Lockr.get("settingConfig");
                if (typeof setting !== 'undefined') {
                    this.settingConfig = setting;
                }
                const clientWidth = document.documentElement.clientWidth;
                const clientHeight = document.documentElement.clientHeight;
                const x_per = 0.2;
                const y_per = 0.2;
                this.ePoint.left.x = _.floor(clientWidth * x_per);
                this.ePoint.left.y = _.floor(clientHeight * y_per);
                this.ePoint.right.x = _.floor(clientWidth * (1 - x_per));
                this.ePoint.right.y = _.floor(clientHeight * (1 - y_per));
            },
            centerClick(e) {
                if (e.clientX >= this.ePoint.left.x && e.clientX <= this.ePoint.right.x
                    && e.clientY >= this.ePoint.left.y && e.clientY <= this.ePoint.right.y) {
                    if (this.popupVisibleOperation) {
                        this.popupVisibleSetting = false;
                        this.hideOperate();
                    } else {
                        this.moreOperate();
                    }
                }
            },
            moreOperate() {
                this.popupVisibleOperation = true;
            },
            hideOperate() {
                this.popupVisibleOperation = false;
            },
            back() {
                window.location.href = '/s';
            },

            loadMore() {
                if (this.loading === true) {
                    return false;
                }
                this.loading = true;
                this.nextChapter();
            },
            nextChapter(chapterIndex) {
                let book_id = this.book.id || '';
                if (book_id === '') {
                    this.loading = false;
                    return false;
                }
                let nextChapterIndex = '0';
                if (typeof chapterIndex !== 'undefined') {
                    nextChapterIndex = chapterIndex;
                } else if (this.chapterIndex === 0) {
                    this.chapterIndex = Lockr.get("ChapterIndex_" + this.book.id);
                    nextChapterIndex = this.chapterIndex;
                } else {
                    nextChapterIndex = this.chapterIndex + 1;
                }
                let params = {id: book_id, chapter_index: nextChapterIndex};
                $.ajax({
                    url: this.contentUrl,
                    type: 'get',
                    dataType: 'json',
                    data: params,
                    success: (res) => {
                        if (res.code === 0) {
                            if (res.data.length === 0) {
                                this.$toast({
                                    message: '没有最新章节了...',
                                    position: 'bottom',
                                    duration: 2000
                                });
                                this.getNext = false;
                                return false;
                            }
                            _.forEach(res.data, item => {
                                this.list.push(item);
                                this.chapterIndex = parseInt(item.chapter_index);
                            });
                            //设置本地阅读位置
                            Lockr.set("ChapterIndex_" + this.book.id, this.chapterIndex);
                            this.loading = false;
                        } else {
                            this.$toast({
                                message: res.message,
                                position: 'bottom',
                                duration: 2000
                            });
                        }
                    },
                    error: (jqXHR, textStatus, errorMessage) => {
                        this.$toast({
                            message: jqXHR.responseJSON.message, osition: 'center',
                            duration: 2000
                        });
                        this.loading = false;
                    }
                })
            },

            showList() {
                this.popupVisibleOperation = false;
                this.popupVisibleChapterList = true;
                $.ajax({
                    url: this.chapterListUrl,
                    type: 'get',
                    dataType: 'json',
                    data: {id: this.book.id, chapter_index: this.chapterIndex},
                    success: (res) => {
                        if (res.code === 0) {
                            this.chapterGroup = res.data;
                            let scrollPx = this.calcChapterScrollPx(res.data);
                            setTimeout(() => {
                                $('.mint-indexlist-content').scrollTop(scrollPx);
                            }, 1000);
                        } else {
                            this.$toast({
                                message: res.message,
                                position: 'bottom',
                                duration: 2000
                            });
                        }
                    },
                    error: (jqXHR, textStatus, errorMessage) => {
                        this.$toast({
                            message: jqXHR.responseJSON.message, osition: 'center',
                            duration: 2000
                        });
                    }
                });
            },
            chooseChapter(chapterId) {
                this.popupVisibleChapterList = false;
                this.loading = false;
                this.list = [];
                this.nextChapter(chapterId);
            },
            calcChapterScrollPx(data) {
                let scrollPx = false;
                for (let i = 0; i < data.length; i++) {
                    let gArr = data[i];
                    for (let j = 0; j < gArr.length; j++) {
                        if (gArr[j].chapter_index === this.chapterIndex) {
                            scrollPx = (100 * i + j) * 48 + (i + 1) * 41 + j;
                            break;
                        }
                    }
                    if (scrollPx !== false) {
                        break;
                    }
                }
                return scrollPx;
            },

            setting() {
                this.popupVisibleSetting = true;
            },
            backgroundColorSet(color) {
                this.settingConfig.backgroundColor.value = color;
            },
        },
        watch: {
            settingConfig: {
                handler: function (val, oldval) {
                    Lockr.set("settingConfig", val);
                },
                deep: true
            }
        }
    })
</script>
</html>
