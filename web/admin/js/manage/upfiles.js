var upfiles=function(){

};upfiles.prototype={
    /**
     * @param os 扩展上传配置
     * @param o  其它配置
     */
    files:function(os,o){
        var t=this;

        var others = {
            id:'upImgs', //表单写入DIV
            namePath:'', //路径文本框name
            nameTitle: '', //描述文本框name
            nameIosurl: '', //
            nameAnurl: '' //
        };
        others = Extend(others, o||{});
        var params = {
            id:others.id,
            maxnum:1,
            //返回的数据回调函数:data格式为:
            //图片 [{ imgSrc:'原图路径',imgPath:'相对路径',imgName:'图片名称'}]
            //文件 [{ fileUrl:文件URL地址,filePath:文件相对路径,fileName:文件名,fileExt:扩展名}]
            callback:function(data){
                data.each(function(){
                    var div = document.createElement('div');

                    var fileSrc = this.imgSrc; var filePath = this.imgPath; var fileName = this.imgName;
                    if(params.type=='file'){
                        fileSrc = this.fileSrc; filePath = this.filePath; fileName = this.fileName;
                    }

                    div.innerHTML = t.showHtml({
                        appurl:others.appurl,
                        pathName:others.namePath,
                        pathValue:filePath,
                        titleName:others.nameTitle,
                        titleValue:fileName,
                        iosurlName:others.nameIosurl,
                        anurlName:others.nameAnurl,
                    });

                    $('#'+others.id).append(div);
                });
            }
        };

        params = Extend(params, os||{});
        upload.show(params);
    },

    showHtml:function(ops){
        if(ops.appurl==1){
            var str = '<input type="text" class="form-i inputPath" name="'+ops.pathName+'" value="'+ops.pathValue+'">' +
                '<input type="text" placeholder="web链接" class="form-i inputPath" name="'+ops.titleName+'" value=""> '+
                '<a href="'+yunurl+ops.pathValue+'" target="_blank" class="iconfont c-green seePic" title="查看缩略图" onmousemove="main.seepic(this)">&#xe602;<span><img src="'+yunurl+ops.pathValue+'" /></span></a> ' +
                '<a href="javascript:;" class="a-del" title="移除" onclick="[this.parentNode].remove()">×</a> ' +
                '<br><input type="text" placeholder="苹果链接" class="form-i inputPath" name="'+ops.iosurlName+'" value="">'+
                '<input type="text" placeholder="安卓链接" class="form-i inputPath" name="'+ops.anurlName+'" value="">'+
                '';
        }else{
            var str = '<input type="text" class="form-i inputPath" name="'+ops.pathName+'" value="'+ops.pathValue+'">' +
                '<input type="text" placeholder="web链接" class="form-i inputTitle" name="'+ops.titleName+'" value="'+ops.titleValue+'"> '+
                '<a href="'+yunurl+ops.pathValue+'" target="_blank" class="iconfont c-green seePic" title="查看缩略图" onmousemove="main.seepic(this)">&#xe602;<span><img src="'+yunurl+ops.pathValue+'" /></span></a> ' +
                '<a href="javascript:;" class="a-del" title="移除" onclick="[this.parentNode].remove()">×</a> ' +
                '';
        }

        return str;
    }
};upfiles = new upfiles;