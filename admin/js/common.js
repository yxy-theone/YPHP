$(document).ready(function($) {
    
});
var checkPhone = function(phone){
    var regu = /^(134|135|136|137|138|139|147|150|151|152|157|158|159|178|182|183|184|187|188|130|131|132|145|155|156|176|185|186|133|153|177|180|181|189|170)[0-9]{8}$/; 
    var re = new RegExp(regu);
    if(re.test(phone)){
        return true;
    }else{
        return false;
    }
}
var checkEmpty = function(p){
    if(p == ''){
        return false;
    }else{
        return true;
    }
}
//密码必须由6-12位数字或者字母组成
var checkPassword = function(pwd){
    var regu = /^(\w){6,12}$/;
    var re = new RegExp(regu);
    if(re.test(pwd)){
        return true;
    }else{
        return false;
    }
}
var getUrl = function(url){
    return url;
}