<?php
include 'boot.php';

$query = "SELECT * FROM daily_raw where begin_date >= '2012-11-20' and begin_date < '2012-12-03' ORDER BY id";

$sth_total = $dbh->prepare($query);

$sth_total->execute();

$fp = fopen('export.csv', 'w');

$header = array('Provider','Provider Country','SKU','Developer','Title','Version','Product', 'Type', 'Identifier', 'Units', 'Developer Proceeds', 'Begin Date', 'End Date', 'Customer Currency', 'Country Code', 'Currency of Proceeds', 'Apple Identifier', 'Customer Price', 'Promo Code', 'Parent Identifier', 'Subscription', 'Period', 'Category');

fputcsv($fp, $header, "\t");

while ($row = $sth_total->fetch(PDO::FETCH_ASSOC))
{

$row['begin_date'] = date('m/d/Y', strtotime($row['begin_date']));
$row['end_date'] = date('m/d/Y', strtotime($row['end_date']));
$row['product_type_identifier'] = (int)$row['product_type_identifier'];
$row['units'] = (int)$row['units'];
$row['developer_proceeds'] = (int)$row['developer_proceeds'];
$row['customer_price'] = (int)$row['customer_price'];

unset($row['id']);
	fputcsv($fp, $row, "\t");
}


fclose($fp);