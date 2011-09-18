<?php
include '../boot.php';

$sql = 'SELECT DISTINCT apple_identifier, title FROM daily_raw ORDER BY title';

foreach ($dbh->query($sql) as $row) {
	$apps[$row['apple_identifier']] = $row['title'];
}

$params = array();
$data = array();

//By default, we're only going to look back 2 weeks, not including today
$startTime = strtotime('32 days ago');
$startDate = date('Y-m-d', $startTime);
$endTime = strtotime('yesterday');
$endDate = date('Y-m-d', $endTime);

$params[] = $startDate;
$params[] = $endDate;

$query = '';
$where = ' (begin_date >= ? AND begin_date < ?) ';

if (isset($_GET['apple_identifier']))
{
	$where .= ' AND apple_identifier = ?';

	$params[] = $_GET['apple_identifier'];
}

//By default we will pull out the new downloads
$where .= ' AND product_type_identifier = ?';

$params[] = (isset($_GET['download_type'])) ? $_GET['download_type'] : 1;


$where = ($where) ? ' WHERE ' . $where : '';

$group = ' GROUP BY country_code';

$query = "SELECT sum(units) as units, country_code FROM daily_raw " . $where . $group;

//echo $query;
//print_r($params);

$sth_total = $dbh->prepare($query);

if (count($params))
{
	$count = 1;
	foreach ($params as $value)
	{
		$sth_total->bindValue($count, $value);
		$count++;
	}
}

$sth_total->execute();

while ($row = $sth_total->fetch(PDO::FETCH_ASSOC))
{
	$data[] = $row;
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

echo json_encode(array('apps' => $apps, 'data' => $data));