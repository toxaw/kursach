<?php
include("php_query/query_list.php");
$_POST['set']=$_POST['set']==''?'group':$_POST['set'];
if($_POST['name']!='')BuilderFront::set_array_write(Schedule::get_now($_POST['set'], $_POST['name']),$_POST['set']);
?>
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
    <h1 class="text-center">Рассписание предметов <?php echo BuilderFront::$dataTemplate[$_POST['set']][0];?></h1>
    <div class="row text-center">
      <h3 class="col-md inl"> <?php echo BuilderFront::$dataTemplate[$_POST['set']][5];?></h3>
    <div class="dropdown col-md inl">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <p style="display:inline"><?php echo $_POST['name']==''?'Выбрать':$_POST['name']; ?></p>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
  <?php BuilderFront::create_dropList($_POST['set']); ?>
  </ul>
</div>
<form class='inl' method="post">
  <input type="hidden" name="set" value="<?php echo $_POST['set']; ?>">
  <input type="hidden" id="sbmn" name="name" value="<?php echo $_POST['name']; ?>">
<button type="submit" class="btn btn-default col-md inl">Показать</button>
<button type="submit" formaction="./full_list.php" class="btn btn-default col-md inl">Рассписание на неделю</button>
</form>
</div>
<div class="row">
  <div class="top-buffer col-md-8 col-md-offset-2">
  <table class="table table-bordered">
  <thead>
    <tr>
      <th><?php echo BuilderFront::$dataTemplate[$_POST['set']][1];?></th>
      <th><?php echo BuilderFront::$dataTemplate[$_POST['set']][2];?></th>
      <th><?php echo BuilderFront::$dataTemplate[$_POST['set']][3];?></th>
      <th><?php echo BuilderFront::$dataTemplate[$_POST['set']][4];?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo BuilderFront::array_write(0,0);?></td>
      <td><?php echo BuilderFront::array_write(0,1);?></td>
      <td><?php echo BuilderFront::array_write(0,2);?></td>
      <td><?php echo BuilderFront::array_write(0,3);?></td>
    </tr>
    <tr class="info">
      <td><?php echo BuilderFront::array_write(1,0);?></td>
      <td><?php echo BuilderFront::array_write(1,1);?></td>
      <td><?php echo BuilderFront::array_write(1,2);?></td>
      <td><?php echo BuilderFront::array_write(1,3);?></td>
    </tr>
    <tr>
      <td><?php echo BuilderFront::array_write(2,0);?></td>
      <td><?php echo BuilderFront::array_write(2,1);?></td>
      <td><?php echo BuilderFront::array_write(2,2);?></td>
      <td><?php echo BuilderFront::array_write(2,3);?></td>
    </tr>
  </tbody>
</table>
</div>
</div>
 <div class="row text-center">
 <form method="post">
<button type="submit" name='set' value='group' class="btn btn-<?php echo $_POST['set']=='group'?'primary':'default'; ?> col-md inl">По группам</button>
<button type="submit" name='set' value='worker' class="btn btn-<?php echo $_POST['set']=='worker'?'primary':'default'; ?> col-md inl">По преподавателям</button>
<button type="submit" name='set' value='auditory' class="btn btn-<?php echo $_POST['set']=='auditory'?'primary':'default'; ?> col-md inl">По аудиториям</button>
</form>
</div>
<div classs="row">
  <div class="text-right">
    <h3>Сейчас:<small> <?php  $ans = BuilderFront::get_formatDate(''); echo $ans['day_week'].' '.$ans['date'].' '; ?><div class='clock' style="display:inline">00:00:00</div>
    </small></h3>
    </div>
</div>
</div>
  <script src="./js/jquery.min.js"></script>
 <script src="./js/bootstrap.js"></script>
  </body>
  <script>
  function update() {
    var date = new Date();
    var hours = date.getHours();
    if (hours < 10) hours = '0' + hours;
    var minutes = date.getMinutes();
    if (minutes < 10) minutes = '0' + minutes;
    var seconds = date.getSeconds();
    if (seconds < 10) seconds = '0' + seconds;
    $('.clock').text(hours+":"+minutes+":"+seconds);
    if(seconds==0) location.reload();
}
    timerId = setInterval(update, 1000);
    update();
    $('.dropdown-menu li a').click(function(){
      $('.dropdown p').text($(this).text());
      $('#sbmn').val($(this).text());
    });
  </script> 
</html>