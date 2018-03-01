<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>Индикация рассписания</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="./js/jquery.min.js"></script>
    <!-- Последняя компиляция и сжатый CSS -->  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<!-- Дополнение к теме -->  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" href="./css/bootstrap-theme.min.css">
<!-- Последняя компиляция и сжатый JavaScript -->  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js"></script>
<link rel="stylesheet" href="./css/style.css">

  </head>
  <body>
    <div="container">
    <h1 class="text-center">Рассписание предметов по группе</h1>
    <div class="row text-center">
      <h3 class="col-md inl">Группа:</h3>
    <div class="dropdown col-md inl">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    МТ-11
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#">МТ-12</a></li>
    <li><a href="#">МТ-13</a></li>
    <li><a href="#">ПОКС-11</a></li>
    <li><a href="#">ПОКС-12</a></li>
  </ul>
</div>
<button class="btn btn-default col-md inl">Показать</button>
<button class="btn btn-default col-md inl">Рассписание на неделю</button>
</div>
<div class="row">
  <div class="top-buffer col-md-8 col-md-offset-2">
  <table class="table table-bordered">
  <thead>
    <tr>
      <th>Дисциплина</th>
      <th>Преподаватель</th>
      <th>Рассписание</th>
      <th>Аудитория</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
    <tr class="info">
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
    <tr>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
  </tbody>
</table>
</div>
</div>
 <div class="row text-center">
<button type="button" class="btn btn-primary col-md inl">По группам</button>
<button type="button" class="btn btn-default col-md inl">По преподавателям</button>
<button type="button" class="btn btn-default col-md inl">По аудиториям</button>
</div>
<div classs="row">
  <div class="text-right">
    <h3>Сейчас:<small> 10:30:42 10.02.18</small></h3>
    </div>
</div>
</div>
  </body>
</html>