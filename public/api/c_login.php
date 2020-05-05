<?php
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require_once 'm_user.php';
  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  class Response {
    public $errorCode = '';
    public $errorText = '';
    public $users = '';
    public $hash = '';
  }

  $res = new Response;
    
  if (isset($_POST['login'])) {
    // логин по post
    $pass = md5(md5($_POST['pass']));
    $user = getUser("post", $_POST['login']); // возвращает массив пользователей
    
    if (count($user) > 0) {
      if ($user[0]->pass === $pass) {
        $res->hash = updateUserHash($user[0]->id);
        $res->users = $user[0];
        updateUser('last_connect', time(), 'id', $user[0]->id);
        exit(json_encode($res));
      } else {
        $res->errorCode = 'auth/pass_wrong';
        $res->errorText = 'Логин или пароль не совпадают!';
        exit(json_encode($res));
      }
    } else {
      $res->errorCode = 'auth/login_wrong';
      $res->errorText = 'Пользователь не найден!';
      exit(json_encode($res));
    }
  }

  if (isset($_GET['hash'])) {
    // логин по хэш
    $user = getUserWithHash($_GET['hash'])[0];
    if ($user->post === $_GET['login']) {
      $res->users = $user;
      exit(json_encode($res));
    } else {
      $res->errorText = "Хэш не определен!";
      $res->errorCode = "auth/hash_wrong";
      exit(json_encode($res));
    }
    
  }

?>