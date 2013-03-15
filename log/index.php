<style type="text/css" media="screen">
	table { background-color: #BBB;
		font-size:12px; }
	th { background-color: #EEE; }
	td { background-color: #FFF; }
</style>

<?php

$filename = "log.csv";

if ( $_GET['action'] == 'clear' )
{
				$result = 0;
				if (($handle = fopen($filename, "w")) !== FALSE) 
				{
								$result = 1;
								$array = array('ip','time','action','type','session','vendor');
								fputcsv($handle , $array);
								fclose($handle);
				} else {
								die("Log table not writable - chmod please");
				}
				$msg = ($result == 1) ? 'cleared' : 'failed';	

				echo 'Log : ' . $msg;
				echo '<br><br><a href="index.php?action=list">  Continue</a>';
} else {
				echo '<br><a href="index.php?action=clear">Clear Log</a>';
				
				echo '<br><br><table border="0" cellspacing="1" cellpadding="3">';

				if (is_writable($filename))
				{
								if (($handle = fopen($filename, "r")) !== FALSE) 
								{
									$row = 0;
									
									while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
									{
										if ($row == 0) 
										{
														$num = count($data);
														echo '<tr>';
														 $row++;
							
														for ($c=0; $c < $num; $c++) 
														{
												   				echo '<th>' . $data[$c] . '</th>';
							        						}
										        			echo '</tr>';
										} else {
											        		$max_id = 0;	
														$max_code = 0;	
														$num = count($data);
														echo '<tr>';
														$row++;
														 	
														for ($c=0; $c < $num; $c++) 
														{
												   				echo '<td>' . $data[$c] . '</td>';
							        						}
										        			echo '</tr>';
										}
									}
									echo '</table>';
									fclose($handle);
								}
				} else {
								die('Please CHMOD log file to be writable');				
				}
}
?>