<?php
include_once('db_connect.php');

// for ($data = []; $count = $query->fetch_assoc(); $data[] = $count);
// d($data);

function d($var) 
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function get_tr($query)
{
    $result = '';
    while ($elem = $query->fetch_assoc()) {
      $result .= '<tr>';
      $result .= '<td>' . $elem['brand'] . '</td>';
      $result .= '<td>' . $elem['model'] . '</td>';
      $result .= '<td>' . $elem['price'] . '</td>';
      $result .= '<td>' . $elem['status'] . '</td>';
      $result .= '<td>' . $elem['mileage'] . '</td>';
      $result .= '<td><a class="form__delete" href="?del=' . $elem['id'] . '">удалить</a></td>';
      $result .= '<td><a class="form__edit" href="?red=' . $elem['id'] . '">редактировать</a></td>';
      $result .= '</tr>';
    }
    return $result;
}
function clear_text($input_text)
{
  global $mysqli;
  $input_text = strip_tags($input_text);
  $input_text = htmlspecialchars($input_text);
  $input_text = mysqli_escape_string($mysqli,$input_text);
  return $input_text;
}
$query = $mysqli->query("SELECT * FROM ".$db_table."");
if (!$query) {
    die('Ошибка : ('. $mysqli->error .') '. $mysqli->errno);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width,initial-scale=1"> 
    <title>Тестовое задание для Kodix</title>
    <link rel="stylesheet" type="text/css" href="style.css" >
  </head>
  <body>
    <table class="table">
      <thead>
        <tr>
          <td>Бренд</td>
          <td>Модель</td>
          <td>Цена с ЭНДС</td>
          <td>Статус</td>
          <td>Пробег</td>
          <td>Удалить</td>
          <td>Редактировать</td>
        </tr>
      </thead>
      <tfoot>
        <form class="form" action="" method="POST">
        <tr>
          
            <td><input class="form__input" type="text" name="brand" placeholder="Бренд" maxlength="15" required></td>
            <td><input class="form__input" type="text" name="model" placeholder="Модель" maxlength="15" required></td>
            <td><input class="form__input" type="number" name="price" placeholder="Цена с НДС" maxl="10000000" required></td>
            <td><input class="form__input" type="text" name="status" placeholder="Статус" maxlength="10" required></td>
            <td><input class="form__input" type="number" name="mileage" placeholder="Пробег" maxl="1000000" required></td>
            <td colspan="2"><button class="form__button" type="submit">Добавить</button></td>

        </tr>
        </form>
      </tfoot>      
      <tbody>
        <?=get_tr($query);?>
      </tbody>
    </table>
  </body>
</html>