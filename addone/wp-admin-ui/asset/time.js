//persian number
(function(a){a.extend({persianNumbers:function(b){var g={0:"۰",1:"۱",2:"۲",3:"۳",4:"۴",5:"۵",6:"۶",7:"۷",8:"۸",9:"۹"};var d=(b+"").split("");var f=d.length;var c;for(var e=0;e<=f;e++){c=d[e];if(g[c]){d[e]=g[c]}}return d.join("")}})})(jQuery);

function show_time_online() {
    now = new Date(); hour = now.getHours(); min = now.getMinutes(); sec = now.getSeconds();
    if (hour <= 9) {hour = "0" + hour;}
    if (min <= 9) {min = "0" + min;	}
    if (sec <= 9) {sec = "0" + sec;}
    if(hour +':'+ min +':'+ sec == '00:00:01'){document.location.reload();}
    jQuery("span#timespan").html( jQuery.persianNumbers(hour + ':' + min + ':' + sec));
    setTimeout("show_time_online()", 1000);
}



