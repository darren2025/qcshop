import axios from 'axios'


// 获取系统及时状态
export var api_bt = function() {
    return axios.get("http://www.321dz.com/bt-api.php").then(res=>{
        return res.data
    })
}