<?php
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
//intval将变量转成整数类型
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

include 'conn.php';

$result = array();
if ($id == 0){
	$rs = mysql_query("select count(*) from products where parentId=9");//查询选择products表中parentId=9的记录条数
    //Select  count(*) from 返回的是当前表中数据的条数，Select * from返回的是当前表中所有的数据
	$row = mysql_fetch_row($rs);//查询结果保存在数组$row中
	$result["total"] = $row[0];//$row数组中就一个值-->$row[0]即是数量---->赋给$resule["total"]数组:
	
	$rs = mysql_query("select * from products where parentId=9 limit $offset,$rows");
	$items = array();
	while($row = mysql_fetch_array($rs)){
		$row['state'] = has_child($row['id']) ? 'closed' : 'open';
		array_push($items, $row);
	}
	$result["rows"] = $items;
} else {
	$rs = mysql_query("select * from products where parentId=$id");
	while($row = mysql_fetch_array($rs)){
		$row['state'] = has_child($row['id']) ? 'closed' : 'open';
		$row['total'] = $row['price']*$row['quantity'];
		array_push($result, $row);
	}
}

echo json_encode($result);

function has_child($id){
	$rs = mysql_query("select count(*) from products where parentId=$id");
	$row = mysql_fetch_array($rs);
	return $row[0] > 0 ? true : false;
}

?>
