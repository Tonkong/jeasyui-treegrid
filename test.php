<?php
include 'conn.php';
$result = mysql_query("SELECT * FROM products");
while($row = mysql_fetch_array($result))
    //关联数组,键值对
    //$row为数组,数组内保存一行的数据,数据为每列对应的值
    //array("字段name"=>"name的值","字段年龄"=>"年龄大小值",......)
    //数组中字段名作为键,
{
    echo $row['name'];
    echo "<br />";
}
/*
 * 如果直接中$row -->  while($row)↓
直接用$row的条件，$row只是得到的第一行的数据，所以while会无限循环，
而$row=mysql_fetch_array($query)作为条件，
mysql_fetch_array()会自动指下一条数据，所以while循环的时候，mysql_fetch_array()指向下一条数据，$row的内容也会随之改变
$row=mysql_fetch_array($query);
-----------------------------
将执行结果(数组)保存到变量$row中.
while($row=mysql_fetch_array($query))
这个是对结果集进行循环遍历..  这个函数会自动指向下一条数据. 在这个循环中也就是判断$row是不是为空.
*/
?>
