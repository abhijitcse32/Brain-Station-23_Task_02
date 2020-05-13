<!DOCTYPE html>
<html>
<head>
	
</head>

<body class="hold-transition sidebar-mini layout-fixed">

	
	<h1 style="text-align:center">Task: 02</h1>
	
	<?php
	$sql="
	SELECT m.ParentcategoryId, n.Name, SUM(m.nof_item) nof_item
FROM(
SELECT ParentcategoryId, nof_item
FROM(
SELECT c.ParentcategoryId, count(b.id) nof_item
FROM item_category_relations a, item b, catetory_relations c
WHERE a.ItemNumber=b.Number
AND a.categoryId=c.categoryId
GROUP BY c.ParentcategoryId
    ) d
    WHERE ParentcategoryId NOT IN (select categoryId FROM catetory_relations)
    UNION
    SELECT e.ParentcategoryId, SUM(d.nof_item) nof_item
FROM(
SELECT c.ParentcategoryId, count(b.id) nof_item
FROM item_category_relations a, item b, catetory_relations c
WHERE a.ItemNumber=b.Number
AND a.categoryId=c.categoryId
GROUP BY c.ParentcategoryId
    ) d, catetory_relations e
    WHERE d.ParentcategoryId=e.categoryId
	AND e.ParentcategoryId NOT IN (select categoryId FROM catetory_relations)
    GROUP BY e.ParentcategoryId
	UNION
	SELECT g.ParentcategoryId, SUM(nof_item) nof_item
FROM(
SELECT e.ParentcategoryId, SUM(d.nof_item) nof_item
FROM(
SELECT c.ParentcategoryId, count(b.id) nof_item
FROM item_category_relations a, item b, catetory_relations c
WHERE a.ItemNumber=b.Number
AND a.categoryId=c.categoryId
GROUP BY c.ParentcategoryId
    ) d, catetory_relations e
    WHERE d.ParentcategoryId=e.categoryId
    GROUP BY e.ParentcategoryId
    ) f, catetory_relations g
    WHERE f.ParentcategoryId=g.categoryId
    GROUP BY g.ParentcategoryId
    ) m, category n
    WHERE m.ParentcategoryId=n.Id
    GROUP BY m.ParentcategoryId, n.Name
	";
	$oResult=$oBasic->SqlQuery($sql);
	$sum=0;
	for($i=0;$i<$oResult->num_rows;$i++)
	{
		$sum=$sum+$oResult->rows[$i]['nof_item'];
	}
	?>
	<h2>Clothing (<?php echo $sum;?>)</h2>
	<?php
	for($i=0;$i<$oResult->num_rows;$i++)
	{
	?>
	
	<h3><?php echo '&nbsp;-'.$oResult->rows[$i]['Name'].' ('.$oResult->rows[$i]['nof_item'].')';?></h3>
	
	<?php
	$sqltwo="SELECT a.categoryId, c.ParentcategoryId, d.Name Name2, count(b.id) nof_item2
	FROM item_category_relations a, item b, catetory_relations c, category d
	WHERE a.ItemNumber=b.Number
	AND a.categoryId=c.categoryId
	AND c.ParentcategoryId='".$oResult->rows[$i]['ParentcategoryId']."'
	AND a.categoryId=d.Id
	AND c.categoryId=d.Id
	GROUP BY a.categoryId, c.ParentcategoryId, d.Name";
	$oResulttwo=$oBasic->SqlQuery($sqltwo);
	for($i1=0;$i1<$oResulttwo->num_rows;$i1++)
	{
	?>
         <h4><?php echo '&nbsp;&nbsp;-'.$oResulttwo->rows[$i1]['Name2'].' ('.$oResulttwo->rows[$i1]['nof_item2'].')';?></h4>
         
		 <?php
	$sqlthree="SELECT f.ParentcategoryId, e.Name, SUM(e.nof_item) nof_item
FROM(
SELECT c.ParentcategoryId, d.Name, count(b.id) nof_item
	FROM item_category_relations a, item b, catetory_relations c, category d
	WHERE a.ItemNumber=b.Number
	AND a.categoryId=c.categoryId
	AND a.categoryId=d.Id
	AND c.categoryId=d.Id
	GROUP BY c.ParentcategoryId, d.Name
    ) e, catetory_relations f
    WHERE e.ParentcategoryId=f.categoryId
    AND f.ParentcategoryId NOT IN (select categoryId FROM catetory_relations)
	AND f.ParentcategoryId='".$oResult->rows[$i]['ParentcategoryId']."'
    GROUP BY f.ParentcategoryId, e.Name";
	$oResultthree=$oBasic->SqlQuery($sqlthree);
	for($i2=0;$i2<$oResultthree->num_rows;$i2++)
	{	 
	?>
	<h4><?php echo '&nbsp;&nbsp;&nbsp;-'.$oResultthree->rows[$i2]['Name'].' ('.$oResultthree->rows[$i2]['nof_item'].')';?></h4>
	<?php } ?>	 
    <?php } ?>
	
	<?php
	}
	?>
	
</body>
</html>
