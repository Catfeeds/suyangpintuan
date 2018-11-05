/* *
 * 倒计时
 */
var Tday_jx = [];
var timeID_jx = [];
var timeID_h_jx = [];
var day_jx = 24 * 60 * 60;
var hour_jx = 60 * 60;
var minutems_jx = 60;
var Secondms_jx = 1;

//倒计时时钟
function clock_jx(key, type,join,str)
{
    var diff = Tday_jx[key];

    var DifferDay = Math.floor(diff / day_jx);
    diff -= DifferDay * day_jx;

    var DifferHour = Math.floor(diff / hour_jx);
    diff -= DifferHour * hour_jx;

    var DifferMinute = Math.floor(diff / minutems_jx);
    diff -= DifferMinute * minutems_jx;

    var DifferSecond = Math.floor(diff / Secondms_jx);
    diff -= DifferSecond * Secondms_jx;

    if (DifferSecond.toString().length < 2)DifferSecond = '0' + DifferSecond;
    if (DifferMinute.toString().length < 2)DifferMinute = '0' + DifferMinute;
    if (DifferHour.toString().length < 2)DifferHour = '0' + DifferHour;
    if (DifferDay.toString().length < 2)DifferDay = '0' + DifferDay;

    var sTime = "";
	if(join){
		if(type!=""){
			if (DifferDay > 0)sTime += "<"+type+">"+DifferDay + "</"+type+">"+"天";
			sTime +=  "<"+type+">"+ DifferHour + "</"+type+">"+"时";
			sTime +=  "<"+type+">"+ DifferMinute + "</"+type+">"+"分";
			sTime +=  "<"+type+">"+ DifferSecond+"</"+type+">"+"秒";
		}else{
			if (DifferDay > 0)sTime += DifferDay + "天";
			sTime += DifferHour + "时";
			sTime += DifferMinute + "分";
			sTime += DifferSecond + "秒";
		}  
	}else{
	    if(str!=''){
            str="<"+str+">:"+"</"+str+">";
        }else{
            str=":";
        }
		if(type!=""){
			if (DifferDay > 0)sTime += "<"+type+">"+DifferDay + "</"+type+">"+str;
			sTime +=  "<"+type+">"+ DifferHour + "</"+type+">"+str;
			sTime +=  "<"+type+">"+ DifferMinute + "</"+type+">"+str;
			sTime +=  "<"+type+">"+ DifferSecond+"</"+type+">";
		}else{
			if (DifferDay > 0)sTime += DifferDay + str;
			sTime += DifferHour +str;
			sTime += DifferMinute + str;
			sTime += DifferSecond;
		}  
	}
      
    document.getElementById("leftTimeJx" + key).innerHTML = sTime; //显示倒计时信息	
    if (Tday_jx[key] <= 0) {
        //结束计时
        
    } else {
        Tday_jx[key] = Tday_jx[key] - 1;
    }
}

/**
 * 倒计时入口函数
 * @param key       计时DIV的循环ID key,即 id="leftTimeJx{$key}"
 * @param diff_time 倒计时时间差
 * @param type      倒计时类型：默认end 结束，going 进行中，start 即将开始
 */
function onload_leftTime_jx(key, diff_time, type,join,str) {
    type = type ? type : '';
    join = join ? join : '';
    str = str ? str : '';
    Tday_jx[key] = parseInt(diff_time);
    timeID_jx[key] = window.setInterval(function() {
        clock_jx(key, type,join,str);
    }, 1000);
}
