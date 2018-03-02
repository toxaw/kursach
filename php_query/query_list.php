<?php
//-----------класс 1
class Schedule
{ 
	private static $dbh;
	public static function base_connect($host,$dbname,$login,$password)
    {
		try 
		{
	    	self::$dbh = new PDO("mysql:host={$host};dbname={$dbname}", $login, $password);
		}
		catch (PDOException $e) 
		{
	    	print "Error!: " . $e->getMessage() . "<br/>";
	    	die();
		}
	}
	//---------------------------------------------------------------------------------------------------------------------------------
	public static function countSch()
	{
		return self::$dbh->query("SELECT * FROM `main` WHERE start<=now() and end>now();")->fetchAll();
	}
	//---------------------------------------------------------------------------------------------------------------------------------
	public static function get_now($set, $name)
	{
		switch ($set) {
			case 'group':
				return self::get_nowDateForList(self::get_dayListForGroup('',$name));
				break;
			case 'worker':
				return self::get_nowDateForList(self::get_dayListForWorker('',$name));
				break;
			case 'auditory':
				return self::get_nowDateForList(self::get_dayListForAuditory('',$name));
				break;
			default:
				return 2;
				break;
		}	  
	}
    //---------------------------------------------------------------------------------------------------------------------------------
    public static function get_dayListForGroup($date,$group)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
     	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,nodes.numb_auditory,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          self::get_diffDateInDay($date,$res[0]['start'])." && groups.gname='{$group}' ORDER BY nodes.numb_two_week;")->fetchAll();  
       	if(count($res)==0)return 2;   
        return $res;
    }
    public static function get_dayListForWorker($date,$worker)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
     	$res = self::$dbh->query("SELECT discipline.name,groups.gname,time.start,time.end,nodes.numb_auditory,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          self::get_diffDateInDay($date,$res[0]['start'])." && workers.FIO='{$worker}' ORDER BY nodes.numb_two_week;")->fetchAll();  
       	if(count($res)==0)return 2;   
        return $res;
    }
    public static function get_dayListForAuditory($date,$auditory)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
     	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,groups.gname,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          self::get_diffDateInDay($date,$res[0]['start'])." && nodes.numb_auditory='{$auditory}' ORDER BY nodes.numb_two_week;")->fetchAll();  
       	if(count($res)==0)return 2;   
        return $res;
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    private static function get_diffDateInDay($now,$_date)
    {
    	$date =date_diff(new DateTime($now), new DateTime($_date))->days+1;
		return new DateTime($now) >= new DateTime($_date)?($date==7 || $date>=14)?0:($date<=7?$date:$date-1):0;
    }
    public static function get_diffTimeInTime($now,$_time)//метод нигде не используется, но пусть будет на всякий случай
    {
    	$time1 = $now==''?time():strtotime($now); 
		return gmdate('H:i:s',abs($time1 - strtotime($_time)));
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    private static function get_nowDateForList($res)
    {
    	$mass[]=array();
    	$nowdt =date("H:i:s");//сейчас дата/время
    	if(strtotime($res[0]['start'])>strtotime($nowdt))//если дата меньше начала первой пары ,то
    	{
    		$mass[0]='none';
    		$mass[1]='none';
    		$mass[2]=$res[0];
    	}
    	else if(strtotime($res[0]['end'])>=strtotime($nowdt) && strtotime($res[0]['start'])<=strtotime($nowdt))//если дата подходит под первую пару ,то
    	{
    		$mass[0]='none';
    		$mass[1]=$res[0];
    		$mass[2]=count($res)>1?$res[1]:'none';
    	}
    	else if(strtotime($res[count($res)-1]['end'])<strtotime($nowdt))//если дата больше конца последней пары ,то
    	{   		
    		$mass[0]=$res[count($res)-1];
    		$mass[1]='none';
    		$mass[2]='none';
    	}
    	else for($i=1;i<count($res);$i++) //если внутри рассписания кроме первой пары
    		{
    			if(strtotime($res[$i-1]['end'])<strtotime($nowdt) && strtotime($res[$i]['end'])>=strtotime($nowdt))
    			{
    				$mass[0]=$res[$i-1];
    				$mass[1]=$res[$i];
    				$mass[2]=$i==count($res)-1?'none':$res[$i+1];
    				break;
    			}
    		}
    	return $mass;
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    public static function get_groups()
    {
    	return self::$dbh->query("SELECT gname AS 'value' FROM groups;")->fetchAll();
    }
    public static function get_workers()
    {
    	return self::$dbh->query("SELECT FIO AS 'value' FROM workers;")->fetchAll();
    }
    public static function get_auditories()
    {
    	return self::$dbh->query("SELECT DISTINCT numb_auditory AS 'value' FROM nodes;")->fetchAll();
    }
}
//-----------класс 2
class BuilderFront
{
	private static $arr, $set;
	public static function set_array_write($arr, $set)//присвоение массива
    {
    	self::$arr=$arr;
    	self::$set=$set;
    }
	public static function array_write($index, $primary)//формировка массива
    {
    	$none = array('none','1','2','');
    	$primaryTpl = array('group' => array('name', 'FIO', 'time', 'numb_auditory'),
                     			'worker' => array('name', 'gname', 'time', 'numb_auditory'),
                     			'auditory' => array('name', 'FIO', 'time', 'gname') );
    	foreach($none as $value)if($arr==$value){echo '-'; return;}
    	foreach($none as $value)if($arr[$index][$primaryTpl[$set][$primary]]==$value){echo '-'; return;}
    	if($primaryTpl[$set][$primary]=='time')
    		echo strftime("%H:%M", strtotime($arr[$index]['start'])).' - '.strftime("%H:%M", strtotime($arr[$index]['end'])); return;
    	echo $arr[$index][$primaryTpl[$set][$primary]];
    }
 	public static function build_main()
    {
        
    }
    public static function create_dropList($set)//формирование списка выпадающего списка
    {
    	$res=array();
    	switch ($set) {
    		case 'group':
    			$res=Schedule:: get_groups();
    			break;
    		case 'worker':
    			$res=Schedule:: get_workers();
    			break;
    		case 'auditory':
    			$res=Schedule:: get_auditories();
    			break;
    		default:
    			$res='none';
    			return;
    			break;
    	}  	
        foreach ($res as $value) 
   			echo '<li><a href="#">'.$value['value'].'</a></li>';
    }
    public static function build_week()
    {    

    }
    private static function get_forDay($numb_day)/*1-6/7-12*/
    {	
    	$sch = Schedule::countSch();
    	$date = (new DateTime($sch[0]['start']))->modify("+".($numb_day>6?$numb_day:($numb_day-1))." day")->format('Y-m-d');
    	return get_formatDate($date);
    }
    public static function get_formatDate($date)
    {
    	$date=$date==''?time():strtotime($date);
    	setlocale(LC_ALL, 'rus_RUS');
		$day_week =  iconv('windows-1251','UTF-8', ucfirst(strftime("%A", $date)));
		$day =  strftime("%#d", $date);
		$month = preg_replace("/т\$/u","та",preg_replace("/[ьй]\$/u", "я",  iconv('windows-1251','UTF-8',lcfirst(strftime("%B", $date)))));
		$year =  strftime("%Y", $date)." год";		
		return array('day_week' => $day_week, 'date'=>$day." ".$month." ".$year);
    }
}
//----------------------некоторая реализация
Schedule::base_connect('127.0.0.1','schedule','root','');
//---------------------шаблон на 3 способа изображения рассписания
$dataTemplate = array('group' => array('по группе', 'Дисциплина', 'Преподаватель', 'Расписание', 'Аудитория','Группа:'),
                     'worker' => array('по преподавателю', 'Дисциплина', 'Группа', 'Расписание', 'Аудитория','Преподаватель:'),
                     'auditory' => array('по аудитории', 'Дисциплина', 'Преподаватель', 'Расписание', 'Группа','Аудитория:') );
?>