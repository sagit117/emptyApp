<?php
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  require_once 'm_user.php';

  class Response {
    public $errorCode = '';
    public $errorText = '';
  }

  $res = new Response;
  $id = intval($_POST['id']);

  if ($id > 0) {
    $user = getUserWithHash($_POST['hash'])[0];
    if ($user->post !== $_POST['login']) {
      $res->errorText = "Хэш не определен!";
      $res->errorCode = "auth/hash_wrong";
      exit(json_encode($res));
    }

    if ($_POST['id'] !== $user->id and $user->rule !== 'admin') {
      $res->errorText = "Доступ запрещен!";
      $res->errorCode = "auth/rule_wrong";
      exit(json_encode($res));
    }

    if (isset($_POST['name'])) $name = updateUser("name", $_POST['name'], "id", $id);
    if (isset($_POST['surname'])) $surname = updateUser("surname", $_POST['surname'], "id", $id);
    if (isset($_POST['patronymic'])) $patronymic = updateUser("patronymic", $_POST['patronymic'], "id", $id);
    exit(json_encode($res));
  } 
?>