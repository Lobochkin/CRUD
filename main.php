<?php
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
  $insert = $mysqli->query("INSERT INTO cars SET brand='".clear_text($_POST['brand'])."',model='".clear_text($_POST['model'])."',price=".intval($_POST['price']).",id_status=".intval($_POST['status']).",mileage=".intval($_POST['mileage'])."");
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
}
// Список статусов на складе
$list_status = $mysqli->query("SELECT * FROM status");
// Вывод всего списка машин
$query = $mysqli->query("SELECT cars.id as id,brand,model,price, status.status as status,mileage FROM cars INNER JOIN status ON status.id=cars.id_status");
if (!$query) {
    die('Ошибка : ('. $mysqli->error .') '. $mysqli->errno);
}
