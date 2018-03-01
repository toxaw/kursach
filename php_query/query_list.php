<?php
//-----------класс 1
//петод подключения к инициировани подключения к бд
// получить рассписание недель ,чей день сегодня входит(m1 по группе, m2 по преподавателю,m3 по аудитории) и указываем,верхняя или нижняя неделя
// получить прошлую, сейчас и будущую пару ,чей день и время сегодня входит(m1 по группе, m2 по преподавателю,m3 по аудитории)
//6 методов
class Schedule
{ 
	private static $dbh;
	public static function base_connect($host,$dbname,$login,$password)
    {
		try {
	    self::$dbh = new PDO("mysql:host={$host};dbname={$dbname}", $login, $password);
		} catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
		}
	}
	private static function countSch()
	{
		return self::$dbh->query("SELECT * FROM `main` WHERE start<=now() and end>now();")->fetchAll();;
	}
    public static function get_week_forGroup($week,$group)
    {
    	$res = self::countSch();
        if(count($res)==0)	return 1;
        $mass = array();  
        $mass[] = array('start'=>$res[0]['start'],'end'=>$res[0]['end'],'name'=>$group,'sort'=>'qroup');     
      	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,nodes.numb_auditory,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week".
          ($week?">":"<=")."6 && groups.gname='{$group}';")->fetchAll();  
       	return array_merge($mass, $res);
    }
    public static function get_week_forWorker($week,$worker)
    {
        $res = self::countSch();
        if(count($res)==0)	return 1;
        $mass = array();  
        $mass[] = array('start'=>$res[0]['start'],'end'=>$res[0]['end'],'name'=>$worker,'sort'=>'worker');     
      	$res = self::$dbh->query("SELECT discipline.name,groups.gname,time.start,time.end,nodes.numb_auditory,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week".
          ($week?">":"<=")."6 && workers.FIO='{$worker}';")->fetchAll();  
       	return array_merge($mass, $res);   
    }
    public static function get_week_forAuditory($week,$auditory)
    {
        $res = self::countSch();
        if(count($res)==0)	return 1;
        $mass = array();  
        $mass[] = array('start'=>$res[0]['start'],'end'=>$res[0]['end'],'name'=>$auditory,'sort'=>'auditory');     
      	$res = self::$dbh->query("SELECT discipline.name,groups.gname,time.start,time.end,workers.FIO,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week".
          ($week?">":"<=")."6 && nodes.numb_auditory='{$auditory}';")->fetchAll();  
       	return array_merge($mass, $res);   
    }
    public static function get_now_forGroup($group)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
    	$mass = array();  
        $mass[] = array('name'=>$group,'sort'=>'group');
     	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,nodes.numb_auditory,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          get_diffDateInDay('',$res[0]['start'])." && groups.gname='{$group}' ;")->fetchAll();  
       	if(count($res)==0)return 2;   
        return ;    
    }
    public static function get_now_forWorker($worker)
    {
        return ;    
    }
    public static function get_now_forAuditory($auditory)
    {
        return ;    
    }
    private static function get_diffDateInDay($now,$_date)
    {
    	$date =date_diff(new DateTime($now), new DateTime($_date))->days+1;
		return new DateTime($now) >= new DateTime($_date)?($date==7 || $date>=14)?0:($date<=7?$date:$date-1):0;
    }
    private static function get_diffTimeInTime($now,$_time)
    {
    	$time1 = $now==''?time():strtotime($now); 
		return gmdate('H:i:s',abs($time1 - strtotime($_time)));
    }
    private static function get_nowDate($res)
    {
    	$mass[]=array();
    		
    	return ;
    }
}
//-----------класс 2
// вывести на первую страницу
// вывести на вторую страницу
//2 метода
class BuilderFront
{
 	public static function build_Main()
    {
        
    }
    public static function build_Week()
    {
           
    }
    //public static funtion get_forDay($day/*1-6/7-12*/)
}
//всего будет 8 методов
//----------------------некоторая реализация
Schedule::base_connect('127.0.0.1','schedule','root','');
//print_r(Schedule::get_week_forGroup(false,'ПОКС-31'));
//print_r(Schedule::get_week_forWorker(false,'Пухов С.В.'));
//print_r(Schedule::get_week_forAuditory(false,'114'));
//echo Schedule:: get_diffDateInDay('','2018-02-25');
//echo Schedule:: get_diffTimeInTime('','12:35');
?>