<?php
session_start();
require "../common/connect.php";
if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case "add":
			$data['project_title'] = $_POST['title'];
			$data['project_class'] = $_POST['class'];
			$data['project_file'] = $_POST['file_path'];
			$data['project_addr'] = $_POST['addr'];
			$data['date'] = date('y-m-d H:i:s',time());
			if($_POST['id'] != "")
			{
				$id = $_POST['id'];
				$where = "where Id = {$id}";
				$resurt = $db->update("wl_project", $data, $where);
			}
			else
			{
				$resurt = $db->insert("wl_project", $data);
			}
			if($resurt)
			{
				header("location:admin.php?page=add_project&para=action=success");
			}
			else
			{
				header("location:admin.php?page=add_project&para=action=error");
			}
		break;
		case "edit":
			if(isset($_GET['Id']))
			{
				$where = "where Id = {$_GET['Id']}";
				$resurt = $db->select_one("wl_project",$where);
				$data['Id'] = $_GET['Id'];
				$data['project_title'] = $resurt['project_title'];
				$data['project_class'] = $resurt['project_class'];
				$data['project_file'] = $resurt['project_file'];
				$data['project_addr'] = $resurt['project_addr'];
				//$data['date'] = date('y-m-d h:i:s',time());
			}
		break;
		case "success":
			echo "发布成功！"; 
		break;
		default:
			echo "发布失败！"; 
		break;
	}
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		发布项目
	</div>
	<div class="panel-body">
		<div class="group" style="border:1px solid #ccc; padding: 5px;">
			<div class="btn-group">
				<button type="button" id="send" class="btn btn-success">
					<span class="glyphicon glyphicon-send"></span> 发布
				</button>
				<button type="button" id="clear" class="btn btn-danger">
				 <span class="glyphicon glyphicon-retweet"></span> 清空
				</button>
			</div>
		</div>
		<form class="form-horizontal" id="add_project_form" style="margin-top: 20px;"
		role="form" action="add_project.php?action=add"  method="post">
			<input type="hidden" name="id" value="<?php if(isset($data['Id'])){ echo $data['Id'];}?>">
			<div class="form-group">
				<label class="col-sm-2 control-label">项目名称：</label>
				<div class="col-sm-10">
					<input class="form-control" name="title" type="text"
					value="<?php if(isset($data['project_title'])){ echo $data['project_title'];}?>"
					placeholder="请输入项目名称">
				</div>
			</div>  
			<div class="form-group">
				<label class="col-sm-2 control-label">项目分类：</label>
				<div class="col-sm-10">
					<input class="form-control" name="class" type="text"
					value="<?php if(isset($data['project_class'])){ echo $data['project_class'];}?>"
					placeholder="请输入项目分类">
				</div>
			</div> 
			<div class="form-group">
				<label class="col-sm-2 control-label" for="name">项目文件：</label>
				<div class="col-sm-7">
						<input type="text" class="form-control" name="file_path" placeholder="项目文件"
						value="<?php if(isset($data['project_file'])){ echo $data['project_file'];}?>" readonly="readonly">
				</div>
				<div class="col-sm-3">
					<label class="sr-only" for="inputfile">文件输入</label>
					<input type="file" name="project" id="inputFile" onchange = "uploadFile()">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">项目链接：</label>
				<div class="col-sm-10">
						<input class="form-control" name="addr" type="text"
						value="<?php if(isset($data['project_addr'])){ echo $data['project_addr'];}?>"
						placeholder="请输入项目链接" readonly="readonly">
				</div>
			</div> 
		</form>
		<script>
			function uploadFile()
			{
				var formData = new FormData();
				var fileObj = document.getElementById("inputFile").files[0];
				formData.append("file",fileObj);
				$.ajax({
					url: "uploadProject.php",
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					success: function (json) {
						//上传成功
						console.log(json);
						res=JSON.parse(json);
						$("input[name='file_path']").val(res.file_path);
						$("input[name='addr']").val(res.file_addr);
					},
					error: function (data) {
						//上传失败
						console.log(data);
					}
				});
			}
		</script>
	</div>
</div>
<script>
$("#send").click(function(){
	var _title = $("input[name='title']").val();
	var _class = $("input[name='class']").val();
	if(_title == '')
	{
		alert("请输入项目名称");
	}
	else if(_class == '')
	{
		alert("请输入项目分类");
	}
	else
	{	
		$("#add_project_form").submit();
	}
})
$("#clear").click(function(){
	$("#add_project_form :input").not(":button, :submit, :reset, :hidden, :checkbox, :radio").val("");  
	$("#add_project_form :input").removeAttr("checked").remove("selected");  
})
</script>
			