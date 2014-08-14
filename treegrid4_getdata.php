<?php
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

include 'conn.php';

$result = array();
if ($id == 0){
	$rs = mysql_query("select count(*) from products where parentId=0");
	$row = mysql_fetch_row($rs);
	$result["total"] = $row[0];
	
	$rs = mysql_query("select * from products where parentId=0 limit $offset,$rows");
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
