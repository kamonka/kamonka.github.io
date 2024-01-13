<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>今晚看什么</title>
<script type="text/javascript" src="https://lirongyao.com/player/js/playerjs.js"></script>
<script type="text/javascript" src="https://lirongyao.com/player/js/dash.all.min.js"></script>
<style type="text/css">
body,html{height:100%;background-color:#000;margin:0px;padding:0px;overflow:hidden;}
#RongYao{height:100%;}
</style>
</head>
<body>
<div id="RongYao"></div>
<script>
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    return (false);
};
var player = new Playerjs({
    id: "RongYao",
    autoplay: 1,
    file: getQueryVariable("url")
});
</script>
<script type="text/javascript">   
document.onkeydown=function(){
    var e = window.event||arguments[0];
   	if(e.keyCode==123){ alert('F12 is not allowed'); return false; }
	if((e.ctrlKey)&&(e.shiftKey)&&(e.keyCode==73)){ alert('F12 is not allowed'); return false; }
	if((e.ctrlKey)&&(e.keyCode==85)){ alert('F12 is not allowed'); return false; }
	if((e.ctrlKey)&&(e.keyCode==83)){ alert('F12 is not allowed'); return false; }
}
setInterval(function () { debugger }, 1);
</script>  
</body>
</html>