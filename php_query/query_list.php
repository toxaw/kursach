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
		return self::$dbh->query("SELECT * FROM `main` WHERE CURDATE()>=start and CURDATE()<=end;")->fetchAll();
	}
	//---------------------------------------------------------------------------------------------------------------------------------
	public static function get_now($set, $name)
	{
		switch ($set) {
			case 'group':
				return self::get_nowDateForList(self::get_dayListForGroup('',$name, true));
				break;
			case 'worker':
				return self::get_nowDateForList(self::get_dayListForWorker('',$name, true));
				break;
			case 'auditory':
				return self::get_nowDateForList(self::get_dayListForAuditory('',$name, true));
				break;
			default:
				return 2;
				break;
		}	  
	}
	public static function get_list($set, $name, $date)
	{
	   switch ($set) {
            case 'group':
                return self::get_dayListForGroup($date ,$name , false);
                break;
            case 'worker':
                return self::get_dayListForWorker($date ,$name , false);
                break;
            case 'auditory':
                return self::get_dayListForAuditory($date ,$name , false);
                break;
            default:
                return 2;
                break;
        }	
	}
    //---------------------------------------------------------------------------------------------------------------------------------
    public static function get_dayListForGroup($date,$group,$method)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
        $date=$method?self::get_diffDateInDay($date,$res[0]['start'],true):$date;
     	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,nodes.numb_auditory, nodes.id_time
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
         $date." && groups.gname='{$group}' ORDER BY nodes.id_time;")->fetchAll();    
        return self::set_joinList($res);
    }
    public static function get_dayListForWorker($date,$worker,$method)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
        $date=$method?self::get_diffDateInDay($date,$res[0]['start'],true):$date;
     	$res = self::$dbh->query("SELECT discipline.name,groups.gname,time.start,time.end,nodes.numb_auditory, nodes.id_time
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          $date." && workers.FIO='{$worker}' ORDER BY nodes.id_time;")->fetchAll();     
        return self::set_joinList($res);
    }
    public static function get_dayListForAuditory($date,$auditory,$method)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
        $date=$method?self::get_diffDateInDay($date,$res[0]['start'],true):$date;
     	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,groups.gname, nodes.id_time
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          $date." && nodes.numb_auditory='{$auditory}' ORDER BY nodes.id_time;")->fetchAll();    
        return self::set_joinList($res);
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    private static function get_diffDateInDay($now,$_date,$method)
    {
    	$date =date_diff(new DateTime($now), new DateTime($_date))->days+1;
		return $method?(new DateTime($now) >= new DateTime($_date)?($date==7 || $date>=14)?0:($date<=7?$date:$date-1):0):$date;
    }
    public static function get_diffTimeInTime($now,$_time)//метод нигде не используется, но пусть будет на всякий случай
    {
    	$time1 = $now==''?time():strtotime($now); 
		return gmdate('H:i:s',abs($time1 - strtotime($_time)));
    }
    public static function get_nowNumbWeek()
    {
        $res = self::countSch();
        if(count($res)==0)  return 1;
        return self::get_diffDateInDay('',$res[0]['start'],false);
    }
    public static function get_nowWeek()
    {
    	return self::get_nowNumbWeek()<=7?"down":"up";
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    private static function get_nowDateForList($res)
    {
        if(count($res)==0) return 2;
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
    	else if(strtotime($res[count($res)-1]['end'])<=strtotime($nowdt))//если дата больше конца последней пары ,то
    	{   		
    		$mass[0]=$res[count($res)-1];
    		$mass[1]='none';
    		$mass[2]='none';
    	}
    	else for($i=1;i<count($res);$i++) //если внутри рассписания кроме первой пары
    		{
    			if(strtotime($res[$i-1]['end'])<=strtotime($nowdt) && strtotime($res[$i]['end'])>strtotime($nowdt))
    			{
    				$mass[0]=$res[$i-1];
    				$mass[1]=$res[$i];
    				$mass[2]=$i==count($res)-1?'none':$res[$i+1];
    				break;
    			}
    		}
    	return $mass;
    }
    private static function set_joinList($inArr)//функция объединения в данный момент пар
    {
        if($inArr==1) return 1;
        $outArr= array();
        for ($i=0; $i < count($inArr); $i++)
        { 
            $outArr[] = $inArr[$i];
            for ($j=$i+1; $j < count($inArr); $j++) 
            { 
                if($inArr[$i]['id_time']!=$inArr[$j]['id_time']){$i=$j-1; break;}
                $keys = array_keys($inArr[$i]);              
                for ($k=0; $k < count($keys); $k++) //Слияние пар совподающих по времени, исключая совпадений в полях
                    $outArr[(count($outArr)-1)][$keys[$k]] .= ($inArr[$i][$keys[$k]]!=$inArr[$j][$keys[$k]])?(", ".$inArr[$j][$keys[$k]]):"";                           
            }
        }
        return $outArr;
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
    	return self::$dbh->query("SELECT DISTINCT numb_auditory AS 'value' FROM nodes WHERE numb_auditory!='';")->fetchAll();
    }
}
//-----------класс 2
class BuilderFront
{
	private static $arr, $set;
    //---------------------шаблон на 3 способа изображения рассписания
    public static $dataTemplate = array('group' => array('по группе', 'Дисциплина', 'Преподаватель', 'Расписание', 'Аудитория','Группа:','группы'),
                                        'worker' => array('по преподавателю', 'Дисциплина', 'Группа', 'Расписание', 'Аудитория','Преподаватель:','преподавателя'),
                                        'auditory' => array('по аудитории', 'Дисциплина', 'Преподаватель', 'Расписание', 'Группа','Аудитория:','аудитории') );
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
        foreach($none as $value)
        {
            if(self::$arr==$value) return '-';
            if(self::$arr[$index]==$value) return '-';   	 
        }
        if($primaryTpl[self::$set][$primary]=='time')
            return strftime("%H:%M", strtotime(self::$arr[$index]['start'])).' - '.strftime("%H:%M", strtotime(self::$arr[$index]['end']));
        foreach($none as $value)
            if(self::$arr[$index][$primaryTpl[self::$set][$primary]]==$value) return '-';
    	return self::$arr[$index][$primaryTpl[self::$set][$primary]];
    }
    public static function create_dropList($set)//формирование выпадающего списка
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
    public static function build_weekList($set,$name,$week)
    {   
        $text; 
        for ($i=($week=="down"?1:7); $i <= ($week=="down"?6:12); $i++) //$i - номер дня недели 1-6/7-12
        { 
            self::set_array_write(Schedule::get_list($set,$name,$i),$set);
            if(self::$arr==1) return;
            $ans = self::get_forDay($i);
            $_now = Schedule::get_nowNumbWeek();
            $_now = ($_now==7 || $_now>14)?0:$_now;
            $now = ($_now<=7?$_now:$_now-1)==$i;
            $table=self::build_weekTable($now);
            if($table=="") continue;
            $text=$text."<div class='row sh-vs'>
              <div class='col-md-6'>
                <h4 class='col-md-offset-5'>".$ans['day_week']."</h4>
              </div>
              <div class='col-md-6'>
                <h4 class='col-md-offset-4'>".$ans['date']."</h4>
              </div>
            </div>
            <div class='row'>
              <div class='top-buffer col-md-8 col-md-offset-2'>
              <table class='table table-bordered table'>
                <col width='260'>
                <col width='120'>
                <col width='60'>
                <col width='10'>
                <thead>
                  <tr".($now?" class='info'":"").">
                    <th>".self::$dataTemplate[$set][1]."</th>
                    <th>".self::$dataTemplate[$set][2]."</th>
                    <th>".self::$dataTemplate[$set][3]."</th>
                    <th>".self::$dataTemplate[$set][4]."</th>
                  </tr>
                </thead>
                <tbody>
                  ".$table."
                </tbody>
              </table>
              </div>
            </div>";
        }      
        return $text!=""?$text:"<h3 class='text-center'>Расписание отсутствует</h3>";
    }
    public static function build_weekTable($now)
    {
        $text;
        if(self::$arr==2) return '';   
        for ($i=0; $i <count(self::$arr); $i++) { 
            $text=$text."<tr".($now?" class='info'":"").">
                         <td>".self::array_write($i, 0)."</td>
                         <td>".self::array_write($i, 1)."</td>
                         <td>".self::array_write($i, 2)."</td>
                         <td>".self::array_write($i, 3)."</td>
                         </tr>";
                   }
        return $text;            
    }
    private static function get_forDay($numb_day)/*1-6/7-12*/
    {	
        $sch = Schedule::countSch();
    	$date = (new DateTime($sch[0]['start']))->modify("+".($numb_day>6?$numb_day:($numb_day-1))." day")->format('Y-m-d');
    	return self::get_formatDate($date);
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
?>