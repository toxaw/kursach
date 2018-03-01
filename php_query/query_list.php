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
		return self::$dbh->query("SELECT * FROM `main` WHERE start<=now() and end>now();")->fetchAll();
	}
   /* public static function get_week_forGroup($week,$group)
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
    }*/
    public static function get_now_forGroup($group)
    {
    	    
    }
    public static function get_dayListForGroup($date,$group)
    {
    	$res = self::countSch();
    	if(count($res)==0)	return 1;
     	$res = self::$dbh->query("SELECT discipline.name,workers.FIO,time.start,time.end,nodes.numb_auditory,nodes.numb_two_week
         FROM nodes JOIN discipline ON nodes.id_discipline=discipline.id JOIN workers ON workers.id=nodes.id_worker JOIN groups
          ON groups.id=nodes.id_group JOIN time ON time.id=nodes.id_time WHERE nodes.id_main={$res[0]['id']} && nodes.numb_two_week=".
          get_diffDateInDay($date,$res[0]['start'])." && groups.gname='{$group}' ORDER BY nodes.numb_two_week;")->fetchAll();  
       	if(count($res)==0)return 2;   
        return $res;
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
    public static function get_diffTimeInTime($now,$_time)
    {
    	$time1 = $now==''?time():strtotime($now); 
		return gmdate('H:i:s',abs($time1 - strtotime($_time)));
    }
    private static function get_nowDateForList($res)
    {
    	$mass[]=array();
    	$nowdt =date("H:i:s");//сейчас дата/время
    	if(strtotime($res[0]['start'])>strtotime($nowdt))//если дата меньше  начала первой пары ,то
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
?>