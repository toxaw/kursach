<?php
$dbh = new PDO("mysql:host=127.0.0.1;dbname=schedule",'root', '');
// запросить по определенному id_main+
$res = $dbh->query("SELECT * FROM `nodes` WHERE id_main=1;")->fetchAll();
// форычем пройти массив где будет insert to с новым id_main+
foreach($res as $value)
	{
	//	$dbh->query("INSERT INTO `nodes`(`id_main`, `id_discipline`, `id_worker`, `numb_auditory`, `numb_two_week`, `id_time`, `id_group`)
	//	 VALUES ('2','".$value['id_discipline']."','".$value['id_worker']."','".$value['numb_auditory']."','".$value['numb_two_week']."','".$value['id_time']."','".$value['id_group']."');");
	}
	echo "est";
// проверить че получилось+
// убрать в новом старое и довавить,удалить, изменить новое а в старом довавить,удалить, изменить старое+
// проверить че получилось+
?>