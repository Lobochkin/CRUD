<?php
include_once('db_connect.php');
include_once('main.php');
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
                <td><input class="form__input" type="text" name="brand" placeholder="Бренд" maxlength="30" required></td>
                <td><input class="form__input" type="text" name="model" placeholder="Модель" maxlength="30" required></td>
                <td><input class="form__input" type="number" name="price" placeholder="Цена с НДС" maxl="10000000" required></td>
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
                  <input class="form__input" type="text" name="brand" value="<?=$result_edit['brand']?>"  maxlength="30" required>
                </td>
                <td><input class="form__input" type="text" name="model" value="<?=$result_edit['model']?>" maxlength="30" required></td>
                <td><input class="form__input" type="number" name="price" value="<?=$result_edit['price']?>" maxl="10000000" required></td>
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