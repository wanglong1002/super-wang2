<?php 
session_start();
require "../common/connect.php";

$resurt = $db->select_all("wl_comment");
if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case "delete":
			if(isset($_GET['Id']))
			{
				$where = "where Id = {$_GET['Id']}";
				$rs = $db->deleted("wl_comment",$where);
				
				header("location:admin.php?page=message_list");
			}
			else
			{
				echo "未设置删除ID";
			}
		break;
		case "deleteall":
			$ids = $_POST['ids'];
			//echo json_encode($data);
			$where = "where Id in ('{$ids}')";
			$rs = $db->deleted("wl_comment",$where);
			if($rs)
			{
				echo "success";
			}
			else
			{
				echo "error";
			}
			exit();
		break;
	}
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		留言列表
	</div>
	<div class="panel-body">
		<div class="group" style="border:1px solid #ccc; padding: 5px;">
			<div class="btn-group">
				<button type="button" class="btn btn-success menu-item" data-page="add_message">
					<span class="glyphicon glyphicon-plus"></span> 添加
				</button>
				<button id="checkall" type="button" class="btn btn-info">
				 <span class="glyphicon glyphicon-th"></span> 全选
				</button>
				<button id="deleteall" type="button" class="btn btn-danger">
				 <span class="glyphicon glyphicon-minus"></span> 删除
				</button>
			</div>
		</div>
		<table class="table table-bordered table-hover">
			<br>
		  <thead>
		    <tr style="background: #ccc;">
		    	<th style="width: 30px;">ID</th>
		      <th style="width: 120px;">留言日期</th>
		      <th>留言内容</th>
		      <th style="width: 80px;">是否回复</th>
		      <th style="width: 120px;">操作</th>
		    </tr>
		  </thead>
		  <tbody>
		  <?php foreach($resurt as $data){?>
		    <tr>
		    	<td> 
				    <label class="checkbox-inline">
				        <input type="checkbox" name="id" value="<?php echo $data['Id'];?>"> <?php echo $data['Id'];?>
				    </label>
				</td>
		      <td><?php echo $data['comment_date'];?></td>
		      <td><?php echo $data['comment_content'];?></td>
		      <td><?php 
			  if($data['comment_reply'] == "")
			  {
				  echo "否";
			  }
			  else
			  {
				  echo "是";
			  }
			  ?></td>
		      <td>
		      	<div class="btn-group">
				    <a href="admin.php?page=message_reply&para=a&Id=<?php echo $data['Id'];?>" class="btn btn-xs btn-success">回复</a>
				    <a href = "message_list.php?action=delete&Id=<?php echo $data['Id']?>" class="btn btn-xs btn-danger">删除</a>
				</div>
		      </td>
		    </tr>
		  <?php }?>
		  </tbody>
		</table>
	</div>
</div>
<script>
var flag = false;
$("#checkall").click(function(){
	if(flag)
	{
		$("input[name='id']").each(function() { 
			this.checked = false; 
		}); 
		$("#checkall").text("全选");
		flag = !flag;
	}
	else
	{
		$("input[name='id']").each(function() { 
			this.checked = true; 
		}); 
		$("#checkall").text("取消全选");
		flag = !flag;
	}
});
$("#deleteall").click(function(){
	var postModel="";  
	  //遍历复选框获取要删除的数据ID 存放到数组中  
	  $("input[name='id']").each(function () {  
		  if (this.checked) 
		  {
			 postModel+=$(this).val()+"','";  
		  }
	   });	   
	 if(postModel.length == 0) {  
		 alert('请先选择要删除的内容!');  
		 return;  
	  }
	  postModel= postModel.substr(0,postModel.length-3);
	$.post(
	"message_list.php?action=deleteall",
	{"ids":postModel},
	function(rs){
		if(rs == "error")
		{
			alert("删除失败！");
		}
		window.location.reload();
      }
	);
});
</script>