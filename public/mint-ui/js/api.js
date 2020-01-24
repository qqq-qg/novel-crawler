import axios from "axios";

//请求拦截器
axios.interceptors.request.use(
    config => {
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

//响应拦截器即异常处理
axios.interceptors.response.use(
    response => {
        return response;
    },
    err => {
        //todo 处理异常请求
        return Promise.resolve(err.response);
    }
);

//设置默认请求头
axios.defaults.headers = {
    "X-Requested-With": "XMLHttpRequest"
};

export default {
    //get请求
    get(url, param) {
        VueStore.state.common.tableLoading = true;
        return new Promise((resolve, reject) => {
            axios({
                method: "get",
                url,
                params: param
            })
                .then(res => {
                    resolve(res.data);
                })
                .catch(res => {
                    reject(res.data);
                }).finally(() => {
                VueStore.state.common.tableLoading = false;
            });
        });
    },
    //post请求
    post(url, param) {
        const loading = Bus.$loading({
            lock: true,
            text: '拼命加载中...',
            spinner: 'el-icon-loading',
            background: 'rgba(255, 255, 255, 0.7)',
            fullscreen: true
        });
        return new Promise((resolve, reject) => {
            axios({
                method: "post",
                url,
                data: param
            }).then(res => {
                resolve(res.data);
            }).finally(() => {
                loading.close();
            });
        });
    },
    all(requests) {
        return new Promise((resolve, reject) => {
            axios
                .all(requests)
                .then(res => {
                    resolve(res);
                })
                .catch(res => {
                    reject(res);
                });
        });
    }
};
