<?php
include 'boot.php';

$query = "SELECT * FROM daily_raw ORDER BY id";

$sth_total = $dbh->prepare($query);

$sth_total->execute();

$fp = fopen('file.csv', 'w');

while ($row = $sth_total->fetch(PDO::FETCH_ASSOC))
{

$row['begin_date'] = date('m/d/Y', strtotime($row['begin_date']));
$row['end_date'] = date('m/d/Y', strtotime($row['end_date']));
unset($row['id']);
	fputcsv($fp, $row, "\t");
}


fclose($fp);