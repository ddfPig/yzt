<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"C:\phpStudy\PHPTutorial\WWW\tp/application/index\view\home\home_list.html";i:1537507700;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="/public/static/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/public/static/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/public/static/js/sdmenu.js"></script>
<script type="text/javascript" src="/public/static/js/laydate/laydate.js"></script>
    <title>Document</title>
</head>
<body>
      
    
       
        <div>
                <!--<span>请选择您所在区/县</span>-->
                <select id="province" name="province" value="省" onchange="province()">
                <option value="0">请选择您所在省</option>
                <?php foreach($data as $k=>$v): ?>
                <option value="<?php echo $v['ID']; ?>"><?php echo $v['Name']; ?></option>
                <?php endforeach; ?>
                </select>
                 <!--<span>请选择您所在城市</span>-->
                <select id="city" name="city" onchange="city()">
                        <option value="0">请选择您所在城市</option>
                </select>
                <!--<span>请选择您所在区/县</span>-->
                <select id="area" name="area">
                <option value="0">请选择您所在区/县</option>
                </select>
               
         </div>
         <input type="submit" name="submit" value="Submit" onclick="save()" />
        
</body>
<script>
function  save(){
   var province= $("#province").val();
   var city= $("#city").val();
   var area= $("#area").val();
   $.ajax({
        url: "<?php echo url('index/home/save'); ?>",
        type: "post",
        data: {
            province:province,
            city:city,
            area:area
            },
        success: function (res) {
           console.log(res)
        },
    });

}    



function province(){
    var obj = document.getElementById('province');
    var index = obj.selectedIndex; 
    var value = obj.options[index].value;
    var text = obj.options[index].text; 
 console.log(value)
     $.ajax({
        url: "<?php echo url('index/home/setvalue'); ?>",
        type: "post",
        data: {provinceid:value},
        success: function (res) {
		console.log(res)
            $("#city").empty();
            var Pro2 = "<option value='0'>请选择您所在城市</option>";
            $('#city').append(Pro2);
            var res=JSON.parse( res );
            var Pro1 = '';
			for( var i=0; i<res.length; i++){
				var Pro = '<option value='+res[i].ID+'>'+ res[i].Name +'</option>';
				Pro1 += Pro;
			}
			$('#city').append(Pro1);
            city();
        },
    });
}
function city(){
    var obj = document.getElementById('city');
    var index = obj.selectedIndex; 
    var value = obj.options[index].value;
    $.ajax({
        url: "<?php echo url('index/home/setvalue'); ?>",
        type: "post",
        data: {provinceid:value},
        success: function (res) {
            $("#area").empty();
            var Pro2 = "<option value='0'>请选择您所在区/县</option>";
            $('#area').append(Pro2);
            var res=JSON.parse( res );
            var Pro1 = '';
			for( var i=0; i<res.length; i++){
				var Pro = '<option value='+res[i].ID+'>'+ res[i].Name +'</option>';
				Pro1 += Pro;
			}
			$('#area').append(Pro1);
         
        },
    });
}


</script>
</html>