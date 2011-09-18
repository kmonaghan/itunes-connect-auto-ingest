<?php
include '../boot.php';

$sql = 'SELECT DISTINCT apple_identifier, title FROM daily_raw ORDER BY title';

foreach ($dbh->query($sql) as $row) {
	$apps[$row['apple_identifier']] = $row['title'];
}

$params = array();
$data = array();

$productType = (isset($_GET['product_type_identifier']) && $_GET['product_type_identifier']) ? (int)$_GET['product_type_identifier'] : 1;

$params[] = $productType;

$downloadType = ($productType == 1) ? 'downloads' : 'updates';

if (isset($_GET['from']))
{
	$parts = explode('/', $_GET['from']);
	$startTime = strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
}
else 
{
	$startTime = strtotime('32 days ago');
}

$startDate = date('Y-m-d', $startTime);

if (isset($_GET['to']))
{
	$parts = explode('/', $_GET['to']);
	$endTime = strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]);
}
else 
{
	$endTime = strtotime('yesterday');
}

$endDate = date('Y-m-d', $endTime);

$title = "App $downloadType between $startDate and $endDate";

$params[] = $startDate;
$params[] = $endDate;

$query = '';
$where = ' product_type_identifier = ? AND (begin_date >= ? AND begin_date <= ?) ';

if (isset($_GET['apple_identifier']) && isset($apps[$_GET['apple_identifier']]))
{
	$where .= ' AND apple_identifier = ?';

	$params[] = $_GET['apple_identifier'];
	
	$apps = array($_GET['apple_identifier'] => $apps[$_GET['apple_identifier']]);
}

//We need an entry for each app for each day or the graphs will look patchy
$currentTime = $startTime;
while($currentTime <= $endTime)
{
	$currentDate = date('Y-m-d', $currentTime);
	
	foreach($apps as $appleIdentifier => $value)
	{
		$data[$currentDate][$appleIdentifier] = array('units' => 0);	
	}
	
	$currentTime = strtotime('+1 day', $currentTime);
}
$where = ($where) ? ' WHERE ' . $where : '';

$group = ' GROUP BY apple_identifier, begin_date';

$order = ' ORDER BY begin_date';


$query = "SELECT sum(units) as units, apple_identifier, begin_date FROM daily_raw " . $where . $group . $order;

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
	$data[$row['begin_date']][$row['apple_identifier']] = array('units' => $row['units']);
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

echo json_encode(array('apps' => $apps, 'data' => $data, 'total' => count($data), 'title' => $title, 'start_date' => date('d/m/Y',$startTime), 'end_date' => date('d/m/Y',$endTime)));