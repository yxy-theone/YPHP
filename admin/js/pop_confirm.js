$.fn.pop_confirm=function(text,ok_fun,cancel_fun){
    var _this=$(this),iden=Math.floor(new Date());
    _this.popover({content:'<p>'+text+'</p>' +
    '<a href="javascript:void(0);" id="cancel_'+iden+'" class="btn btn-default btn-xs">取消</a>'+
    '<a href="javascript:void(0);" id="yes_'+iden+'" class="pull-right btn btn-danger btn-xs">确定</a>'
        ,placement:"bottom",trigger:'manual',html:true}).popover('show').on('shown.bs.popover', function () {
        $("#cancel_"+iden).click(function(){
            _this.popover('destroy');
            if(typeof cancel_fun=='function'){
                cancel_fun();
            }
        });
        $("#yes_"+iden).click(function(){
            if(typeof ok_fun=='function'){
                ok_fun();
            }
        });
    });
}