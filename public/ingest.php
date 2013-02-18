<?php
include 'boot.php';

$ch = curl_init();
$row = $dbh->query("SELECT begin_date FROM daily_raw ORDER BY begin_date DESC LIMIT 1")->fetch();
if ($row)
{
	$starttime = strtotime('+1 day', strtotime($row['begin_date']));
}
else
{
	$starttime = strtotime('-14 days');
}

$today = time();

while ($starttime < $today)
{
	process($starttime);

	$starttime = strtotime('+1 day', $starttime);
}

curl_close ($ch);

function process($time)
{
	$date = date('Ymd', $time);

	echo 'Processing ' . $date . PHP_EOL;

	global $dbh, $ch, $accounts;

	$sth = $dbh->prepare ("INSERT INTO `daily_raw` (`provider`, `provider_country`, `sku`, `developer`, `title`, `version`, `product_type_identifier`, `units`, `developer_proceeds`, `begin_date`, `end_date`, `customer_currency`, `country_code`, `currency_proceeds`, `apple_identifier`, `customer_price`, `promo_code`, `parent_identifier`, `subscription`, `period`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");	
			
	foreach($accounts as $account)
	{
		$fields_string = "USERNAME=" . urlencode($account['username']);
		$fields_string .= "&PASSWORD=" . urlencode($account['password']);
		$fields_string .= "&VNDNUMBER=" . $account['vndnumber'];

		$fields_string .= "&TYPEOFREPORT=Sales";
		$fields_string .= "&DATETYPE=Daily";
		$fields_string .= "&REPORTTYPE=Summary";
		$fields_string .= "&REPORTDATE=$date";
	
		$filename = "{$date}-{$account['vndnumber']}";
	
		$fp = fopen("$filename.gz", 'w');

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, 'https://reportingitc.apple.com/autoingestion.tft');
		curl_setopt($ch,CURLOPT_POST, 7);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

		curl_setopt($ch, CURLOPT_FILE, $fp);
		
		//execute post
		$contents = curl_exec ($ch);
		
		if ($contents  === false)
		{
    			echo 'Curl error: ' . curl_error($ch);
		}
	
		fclose($fp);

		if (filesize("$filename.gz"))
		{
			exec("gunzip $filename.gz");
			

			if (($handle = fopen("$filename", "r")) !== FALSE)
			{
				//throw away first line
				fgetcsv($handle, 1000, ",");

				while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE)
				{
					$count = 1;
					foreach($data as $value)
					{
						if (($count == 10) || ($count == 11))
						{
							$parts = explode('/', $value);
							$value = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
						}
						 
						$sth->bindValue($count, $value);
						$count++;
					}

					if ($sth->execute())
					{
						echo '.';
					}
					else
					{
						echo 'Error executing' . PHP_EOL;
					}
				}
				fclose($handle);
			} 
			else
			{
				echo "Could not open $filename for reading" . PHP_EOL;
			}	
			unlink("$filename");
		}
		else
		{
			echo 'File is of size 0' . PHP_EOL;
			unlink("$filename.gz");
		}
	}
	
	echo 'Done' . PHP_EOL;
}
