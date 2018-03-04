<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Индикация рассписания</title>
     <link href="./css/bootstrap.css" rel="stylesheet">
    <!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script-->
        <!-- script src="./js/jquery.min.js"></script -->
    <!-- Последняя компиляция и сжатый CSS -->  
<!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"-->
<!--link rel="stylesheet" href="./css/bootstrap.min.css" -->
<!-- Дополнение к теме -->  
<!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"-->
<!--link rel="stylesheet" href="./css/bootstrap-theme.min.css" -->
<!-- Последняя компиляция и сжатый JavaScript -->  
<!-- script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script -->
<!--script src="./js/bootstrap.min.js"></script -->
<link rel="stylesheet" href="./css/style.css">

  </head>
  <body>
<div="container">
<?php
if($_POST['set'] =="" || $_POST['name']=="") {echo "<h2 class='text-center'>Ошибка, расписание не выбрано</h2>"; return;}
include("php_query/query_list.php");
if(count(Schedule::countSch())==0) {echo "<h3 class='text-center'>Расписание отсутствует</h3>"; return;}
$_POST['week']=$_POST['week']==''?Schedule::get_nowWeek():$_POST['week'];
?>
      <div class="row top-buffer">
        <div class="col-md-5">
          <h1 class="col-md-offset-5"><?php echo $_POST['week']=='down'?'Нижняя':'Верхняя';?> неделя</h1>
        </div>
        <div class="col-md-6 top-buffer">
          <div class="col-md-5 col-md-offset-6">
            <form method="post">
               <input type="hidden" name="set" value="<?php echo $_POST['set']; ?>">
               <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
            <button type="submit" name ="week" value="<?php echo $_POST['week']=='down'?'up':'down'; ?>" class="btn btn-default">Перейти на <?php echo $_POST['week']=='down'?'верхнюю':'нижнюю';?> неделю</button></div>
            </form>
        </div>
      </div>
      <div class="row">
        <h3 class="text-center">Рассписание для <?php echo BuilderFront::$dataTemplate[$_POST['set']][6]." ".$_POST['name'] ;?></h3>
      </div>
  <div class="row  top-buffer">
    <?php echo BuilderFront::build_weekList($_POST['set'],$_POST['name'],$_POST['week']); ?>
  </div>
</div>
 <script src="./js/jquery.min.js"></script>
 <script src="./js/bootstrap.js"></script>
</body>
</html>