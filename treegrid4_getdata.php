<?php
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;//前台发送的请求参数
//是否发送来page参数 ,如有则把page转为整数,无则给默认1
//intval将变量转成整数类型
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;//前台发送后台的请求参数-请求几行
$offset = ($page-1)*$rows;//
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;//

include 'conn.php';//连接主机/数据库

$result = array();
if ($id == 0){
	$rs = mysql_query("select count(*) from products where parentId<9");//查询选择products表中parentId=9的记录条数
    //Select  count(*) from 返回的是当前表中数据的条数，Select * from返回的是当前表中所有的数据
	$row = mysql_fetch_row($rs);//查询结果保存在数组$row中-->符合条件的行数量是数组,,数组中第一个值即是总数量
	$result["total"] = $row[0];//$row数组中就一个值-->$row[0]即是数量---->赋给$resule["total"]数组:
	
	$rs = mysql_query("select * from products where parentId<9 limit $offset,$rows");//从行$offset开始检索 ,检索$row行
	$items = array();
	while($row = mysql_fetch_array($rs)){//循环出保存在$rs中检索出的行
		$row['state'] = has_child($row['id']) ? 'closed' : 'open';
		array_push($items, $row);//把$row插入$items数组,返回新数组长度
	}
	$result["rows"] = $items;//把查询到的条数赋值给$result数组的rows键
} else {
	$rs = mysql_query("select * from products where parentId=$id");
	while($row = mysql_fetch_array($rs)){
		$row['state'] = has_child($row['id']) ? 'closed' : 'open';
		$row['total'] = $row['price']*$row['quantity'];
		array_push($result, $row);
	}
}

echo json_encode($result);//把关联数组以json键值对的形式返回

function has_child($id){
	$rs = mysql_query("select count(*) from products where parentId=$id");
	$row = mysql_fetch_array($rs);
	return $row[0] > 0 ? true : false;
}

?>
