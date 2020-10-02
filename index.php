<?php require_once('request.php');?>
<?php echo $post; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Телефонный справочник</title>
  </head>
  <body>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap" rel="stylesheet">
    <style media="screen">
    .wrapper {
      padding: 20px 0;
    }
      table{
        width: 100%;
        font-size: 16px;
        color: #333;
        font-family: 'OpenSans', sans-serif;
      }
      td, th{
        padding: 5px 20px;
      }
      td:last-of-type{
        text-align: center;
      }
      body tr:nth-child(odd) {
        background-color: #cbe2ff;
      }
      caption{
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 20px;
      }

      span.highlight {
        background-color: yellow; cursor: pointer;
      }

      span.splink {
        color: #0A5794; cursor: pointer;
      }
    </style>

    <div class="wrapper">
      <input id="spterm" type="text" name="spterm" placeholder="Кого нужно найти?"><br />
      <div id="spresult">&nbsp;</div>
<div id="content">
      <table>
          <caption>Телефонный справочник</caption>
          <tr>
             <th>ФИО</th>
             <th>Должность</th>
             <th>Телефон</th>
          </tr>
  <?php for ($i = 0; $i < count($resp['users']); $i++){
          if($resp['users'][$i]['telephony']['extension']{0} != '5' & $resp['users'][$i]['telephony']['extension']{0} != '7'){ ?>
    <tr><td><?php echo $resp['users'][$i]['general']['name'] ?></td><td><?php echo $resp['users'][$i]['general']['position'] ?></td><td><?php echo $resp['users'][$i]['telephony']['extension'] ?></td>
  <?php }
      } ?>

 </table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/search.js"></script>
<script type="text/javascript">

$(document).ready(function(){
 var minlen = 2; // минимальная длина слова
 var paddingtop = 30; // отступ сверху при прокрутке
 var scrollspeed = 200; // время прокрутки
 var keyint = 1000; // интервал между нажатиями клавиш
 var term = '';
 var n = 0;
 var time_keyup = 0;
 var time_search = 0;

 $('body').delegate('#spgo', 'click', function(){
  $('body,html').animate({scrollTop: $('span.highlight:first').offset().top-paddingtop}, scrollspeed); // переход к первому фрагменту
 });

 function dosearch() {
  term = $('#spterm').val();
  $('span.highlight').each(function(){ //удаляем старую подсветку
   $(this).after($(this).html()).remove();
  });
  var t = '';
  $('div#content').each(function(){ // в селекторе задаем область поиска
   $(this).html($(this).html().replace(new RegExp(term, 'ig'), '<span class="highlight">$&</span>')); // выделяем найденные фрагменты
   n = $('span.highlight').length; // количество найденных фрагментов
   console.log('n = '+n);
   if (n==0)
    $('#spresult').html('Ничего не найдено');
   else
    $('#spresult').html('Результатов: '+n+'. <span class="splink" id="spgo">Перейти</span>');
   if (n>1) // если больше одного фрагмента, то добавляем переход между ними
   {
    var i = 0;
    $('span.highlight').each(function(i){
     $(this).attr('n', i++); // нумеруем фрагменты, более простого способа искать следующий элемент не нашел
    });
    $('span.highlight').not(':last').attr({title: 'Нажмите, чтобы перейти к следующему фрагменту'}).click(function(){ // всем фрагментам, кроме последнего, добавляем подсказку
     $('body,html').animate({scrollTop: $('span.highlight:gt('+$(this).attr('n')+'):first').offset().top-paddingtop}, scrollspeed); // переход к следующему фрагменту
    });
    $('span.highlight:last').attr({title: 'Нажмите, чтобы вернуться к форме поиска'}).click(function(){
     $('body,html').animate({scrollTop: jQuery('#spterm').offset().top-paddingtop}, scrollspeed); // переход к форме поиска
    });
   }
  });
 }

 $('#spterm').keyup(function(){
  var d1 = new Date();
  time_keyup = d1.getTime();
  if ($('#spterm').val()!=term) // проверяем, изменилась ли строка
   if ($('#spterm').val().length>=minlen) { // проверяем длину строки
    setTimeout(function(){ // ждем следующего нажатия
     var d2 = new Date();
     time_search = d2.getTime();
     if (time_search-time_keyup>=keyint) // проверяем интервал между нажатиями
      dosearch(); // если все в порядке, приступаем к поиску
    }, keyint);
   }
   else
    $('#spresult').html('&nbsp'); // если строка короткая, убираем текст из DIVа с результатом
 });

 if (window.location.hash!="") // бонус
 {
  var t = window.location.hash.substr(1, 50); // вырезаем текст
  $('#spterm').val(t).keyup(); // вставляем его в форму поиска
  $('#spgo').click(); // переходим к первому фрагменту
 }
});
</script>
    </div>
  </body>
</html>
