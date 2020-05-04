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
  }

  $res = new Response();

  if (intval($_GET['id']) > 0) {
    $user = getUserWithHash($_GET['hash'])[0];
    if ($user->post !== $_GET['login']) {
      $res->errorText = "Хэш не определен!";
      $res->errorCode = "auth/hash_wrong";
      exit(json_encode($res));
    }

    if ($_GET['id'] !== $user->id and $user->rule !== 'admin') {
      $res->errorText = "Доступ запрещен!";
      $res->errorCode = "auth/rule_wrong";
      exit(json_encode($res));
    }
    
    $res->users = getUser('id', intval($_GET['id']))[0];
    exit(json_encode($res));
  }