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
      $result .= '<td><a class="form__edit" href="?edit=' . $elem['id'] . '">редактировать</a></td>';
      $result .= '</tr>';
    }
    return $result;
}
// Функция для проверки и очистки данных отправленных POST запросом
function clear_text($input_text)
{
  global $mysqli;
  $input_text = strip_tags($input_text);
  $input_text = htmlspecialchars($input_text);
  $input_text = mysqli_escape_string($mysqli,$input_text);
  return $input_text;
}
// Изменения в базе данных
if (isset($_POST['button_edit'])) {
  $update = $mysqli->query("UPDATE cars SET brand='".clear_text($_POST['brand'])."',model='".clear_text($_POST['model'])."',price=".intval($_POST['price']).",id_status=(SELECT status.id FROM status WHERE status.status='".clear_text($_POST['status'])."'),mileage=".intval($_POST['mileage'])." WHERE cars.id=".intval($_POST['id'])."");
  if ($update) {
    header("Location: " . "/test/CRUD");
    exit;
  }
  if (!$update) {
    die('Ошибка : ('. $mysqli->error .') '. $mysqli->errno);
  }
}
// Добавление в БД
if (isset($_POST['button_add'])) {
  $insert = $mysqli->query("INSERT INTO cars SET brand='".clear_text($_POST['brand'])."',model='".clear_text($_POST['model'])."',price=".intval($_POST['price']).",id_status=(SELECT status.id FROM status WHERE status.status='".clear_text($_POST['status'])."'),mileage=".intval($_POST['mileage'])."");
  if ($insert) {
    header("Location: " . "/test/CRUD");
    exit;
  }
  if (!$insert) {
    die('Ошибка : ('. $mysqli->error .') '. $mysqli->errno);
  }
}

// Удаление записи из БД 
if (isset($_GET['del'])) {
  $id_del = intval($_GET['del']);
  $result_del = $mysqli->query("DELETE FROM cars WHERE id=".$id_del."");
  if ($result_del === null) {
    header("Location: " . "/test/CRUD");
    exit;
  }
}


// Запрос данный для редактировани
if (isset($_GET['edit'])) {
  $id_edit = intval($_GET['edit']);
  $result_edit = $mysqli->query("SELECT cars.id as id,brand,model,price, status.status as status,mileage FROM cars INNER JOIN status ON status.id=id_status WHERE cars.id=".$id_edit."");
  $result_edit = $result_edit->fetch_assoc();

  if ($result_edit === null) {
    header("Location: " . "/test/CRUD");
    exit;
  }
  d($result_edit);
}
// Список статусов на складе
$list_status = $mysqli->query("SELECT * FROM status");
// Вывод всего списка машин
$query = $mysqli->query("SELECT cars.id as id,brand,model,price, status.status as status,mileage FROM cars INNER JOIN status ON status.id=cars.id_status");
if (!$query) {
    die('Ошибка : ('. $mysqli->error .') '. $mysqli->errno);
}
// d(get_tr($query));
// for ($data = []; $count = $query->fetch_assoc(); $data[] = $count);
// d($data);
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
    <div class="wrapper">
      <h1 class="header__title">Kodix</h1>
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
          <? if (!isset($result_edit)) :?>
            <form class="form" action="" method="POST">
              <tr>
                <td><input class="form__input" type="text" name="brand" placeholder="Бренд" maxlength="15" required></td>
                <td><input class="form__input" type="text" name="model" placeholder="Модель" maxlength="15" required></td>
                <td><input class="form__input" type="number" name="price" placeholder="Цена с НДС" maxl="10000000" required></td>
                <!-- <td><input class="form__input" type="text" name="status" placeholder="Статус" maxlength="10" required></td> -->
                <td>
                  <select class="form__input" name="status" required>
                    <option selected disabled>Статус</option>
                    <? while ($status = $list_status->fetch_assoc()) :?>
                    <option><?=$status['status']?></option>
                    <?endwhile?>
                  </select>
                </td>
                <td><input class="form__input" type="number" name="mileage" placeholder="Пробег" maxl="1000000" required></td>
                <td colspan="2"><button class="form__button" name="button_add" type="submit">Добавить</button></td>
              </tr>
            </form>
          <?else:?>
            <form class="form" action="" method="POST">
              <tr>
                <td>
                  <input type="hidden" name="id" value="<?=$result_edit['id']?>" required>
                  <input class="form__input" type="text" name="brand" value="<?=$result_edit['brand']?>"  maxlength="15" required>
                </td>
                <td><input class="form__input" type="text" name="model" value="<?=$result_edit['model']?>" maxlength="15" required></td>
                <td><input class="form__input" type="number" name="price" value="<?=$result_edit['price']?>" maxl="10000000" required></td>
                <!-- <td><input class="form__input" type="text" name="status" value="<?=$result_edit['status']?>" maxlength="10" required></td> -->
                <td>
                  <select class="form__input" name="status" value="<?=$result_edit['status']?>" required>
                    <? while ($status = $list_status->fetch_assoc()) :?>
                    <option <?=$status['status']===$result_edit['status']?'selected':''?>><?=$status['status']?></option>
                    <?endwhile?>
                  </select>
                </td>
                <td><input class="form__input" type="number" name="mileage" value="<?=$result_edit['mileage']?>" maxl="1000000" required></td>
                <td colspan="2"><button class="form__button" name="button_edit" type="submit">Редактировать</button></td>
              </tr>
            </form>
          <?endif?>
        </tfoot>      
        <tbody>
        <? if (!isset($result_edit)) :?>
        <?=get_tr($query);?>
        <?endif?>
        </tbody>
      </table>
    </div>
  </body>
</html>