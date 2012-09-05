<?php
/******************************
'类名：HttpUpload
'名称：文件上传
'日期：2007-3-3
'作者：西楼冷月
'网址：www.xilou.net | www.chinaCMS.org
'描述：支持多文件上传
'版权：转载请注名出处，作者
******************************
'最后修改：2007-3-3
'修改次数：
'修改说明：
'目前版本：
'******************************/
/*
$_FILES['userfile']['name']
客户端机器文件的原名称。 
$_FILES['userfile']['type']
文件的 MIME 类型，需要浏览器提供该信息的支持，例如“image/gif”。 
$_FILES['userfile']['size']
已上传文件的大小，单位为字节。 
$_FILES['userfile']['tmp_name']
文件被上传后在服务端储存的临时文件名。 
$_FILES['userfile']['error']
*/
class HttpUpload{
    
	var $tmpName;//在服务器上的临时文件(路径+文件:C:\WINDOWS\TEMP\php3A.tmp)
	var $oriName;//原来的文件名
	var $oriSize;//文件大小
	var $oriError;//$_FILES['userfile']['error']错误
	var $N;//文件名称
    var $T;//文件的后缀名 如(jpg)
	var $F;//上传成功后的文件全称(=$N.".".$T)
	var $errorNum;//错误代码
	var $errorMsg;//错误信息(数组)

    /*构造函数*/
	function HttpUpload(){
		$this->errorNum=0;//0表示无错误

		//定义错误信息
		$this->errorMsg[0]="上传成功";
		$this->errorMsg[1]="上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值";
		$this->errorMsg[2]="上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值";
		$this->errorMsg[3]="文件只有部分被上传,可能是由于没有写入权限";
		$this->errorMsg[4]="还没选择要上传的文件,没有文件被上传";
		$this->errorMsg[5]="文件不是通过 HTTP POST 上传的";
		$this->errorMsg[6]="文件格式不正确";
		$this->errorMsg[7]="文件超出限定的大小";
		$this->errorMsg[8]="文件宽度超出限定的大小";
		$this->errorMsg[9]="文件高度超出限定的大小";
		$this->errorMsg[10]="已定义为多文件上传,单表单域名不是数组";
	}
	/*
	*方法：void getFile($_tmpname,$_oriname,$_orisize,$_orierror)
	*作用：取得上传文件信息
	*说明：$_tmpname=$_FILES['userfile']['tmp_name']
	       $_oriname=$_FILES['userfile']['name']
		   $_orisize=$_FILES['userfile']['size']
		   $_orierror=$_FILES['userfile']['error']
	*例子：
	*/
	function getFile($_tmpname,$_oriname,$_orisize,$_orierror){
		$this->tmpName=$_tmpname;
		$this->oriName=$_oriname;
		$this->orisize=$_orisize;
		$this->oriError=$_orierror;
		$t1=strrchr($this->oriName,".");//取得".jpg" 后缀
		$this->T=substr($t1,1);//取得"jpg"
		$this->N=substr($this->oriName,0,strlen($this->oriName)-strlen($t1));//取得文件名称
		
	}
	/*
	*方法：bool checkFile($_extension="",$_maxsize="",$_width="",$_height="")
	*作用：综合检查
	*说明：返回 true|false
		   $_extension="gif|jpg|doc" 或$_extension=array("gif","jpg","doc");
	*例子：checkFile("gif|jpg|rar|doc","","","600") 参数留空表示不检查这项
	*/
	function checkFile($_extension="",$_maxsize="",$_width="",$_height=""){
		if(!$this->isUploaded($this->tmpName,$this->oriError))return false;
		if(!$this->checkExtension($this->oriName,$_extension))return false;
		if(!$this->checkSize($this->oriSize,$_maxsize))return false;
		if(!$this->checkWH($this->tmpName,$_width,$_height))return false;
		if(!is_numeric($this->errorNum)){$this->errorNum=5;}//防止单文件变多文件上传
		return true;
	}
	/*
	*方法：bool isUploaded($_tmp_name,$_error)
	*作用：上传检查,判断文件是否是通过 HTTP POST 上传
	*说明：$_tmp_name=$_FILES['userfile']['tmp_name']      
	*例子：isUploaded($_tmp_name,$_error)
	*/
	function isUploaded($_tmp_name,$_error){
		if(!is_uploaded_file($_tmp_name)){
			$this->errorNum=5;
			//文件大小超过表单中 MAX_FILE_SIZE 选项指定的值也会导致is_uploaded_file返回false
			if($this->oriError<>0){$this->errorNum=$this->oriError;}
			if(!is_numeric($this->errorNum)){$this->errorNum=5;}//防止单文件变多文件上传
			return false;
		}
		return true;
	}
	/*
	*方法：bool checkExtension($_file,$_extension)
	*作用：扩展名检查
	*说明：$_file 要检查的文件,通常是$_FILES['userfile']['name']
	*      $_extension 允许的扩展名,可以为"gif|jpg|doc" 或$_extension=array("gif","jpg","doc");的形式
	*例子：
	*/
	function checkExtension($_file,$_extension){
		$f=(is_array($_extension))?$_extension:explode("|",$_extension);
		$fileExtension=strrchr($_file,".");//截取字符串，找不到则返回""
		$fileExtension=(!$fileExtension)?"":strtolower(substr($fileExtension,1));
		//in_array(),是区分大小写的
		if(!in_array($fileExtension,$f)){$this->errorNum=6;return false;}
		return true;
	}
	/*
	*方法：bool checkSize($_size,$_maxsize)
	*作用：大小判断 单位:字节
	*说明：$_size 上传的文件的大小,通常是$_FILES['userfile']['size']
	*      $_maxsize 限制的大小,单位:字节
	*例子：
	*/
	/*大小判断 单位:字节*/
	function checkSize($_size,$_maxsize){
		if($_size > $_maxsize){$this->errorNum=7;return false;}
		return true;
	}
	/*
	*方法：bool checkWH($_file,$_width="",$_height="")
	*作用：宽度和高度判断(用于图片)
	*说明：$_file 要判断宽度和高度的文件
	*      $_width 限制的宽度,如果为空则不限制
	       $_height 限制的高度,如果为空则不限制
	*例子：
	*/
	function checkWH($_file,$_width="",$_height=""){
		$fileinfo=getimagesize($_file);
		if($_width<>""){
			if($fileinfo[0]>$_width){$this->errorNum=8;return false;}
		}
		if($_height<>""){
			if($fileinfo[1]>$_height){$this->errorNum=9;return false;}
		}
		return true;
	}
	/*
	*方法：bool upLoad($_file_tmp_name,$_name,$_new_path_and_name="")
	*作用：文件转移到目标文件夹下
	*说明：
	*例子：upLoad(date("ymdhis"),"uploadfile/")
	*/
	function upLoad($_newname="",$_newpath=""){
		$this->N=($_newname<>"")?$_newname:$this->N;
		$this->F=$_newpath.$this->N.".".$this->T;
		
		if(@move_uploaded_file($this->tmpName,$this->F)){
			$this->errorNum=0;
			return true;
		}else{
			$this->errorNum=3;
			return false;
		}
	}
	/*显示错误信息*/
	function showInfo(){
		$info=$this->errorMsg[$this->errorNum];
		$cssstyle="style=\"";
        $cssstyle.="font:bold 12px 150%,'Arial';border:1px solid #CC3366;";
        $cssstyle.="width:50%;color:#990066;padding:2px;\"";
        $str="\n<ul ".$cssstyle."><li>".$info."</li></ul>\n";
        echo $str;
	}
	
	function getFileName(){
		return $this->N.".".$this->T;
	}
	/*显示错误信息*/
	function info(){
		$info=$this->errorMsg[$this->errorNum];
		return $info;
	}
}
/*使用例子
    $tmpname=$_FILES['userfile']['tmp_name'];
	$name=$_FILES['userfile']['name'];
	$size=$_FILES['userfile']['size'];
	$error=$_FILES['userfile']['error'];

	$v->getFile($tmpname,$name,$name,$error);
	if(!$v->checkFile("jpg|gif|jpeg",1024*10000,"","")){
		$v->showInfo();exit;
	}
	if(!$v->upload("","UploadFile/")){
		$v->showInfo();exit;
	}else{
		$v->showInfo();
		echo $v->info();
		echo "成功";
	}
*/
?>