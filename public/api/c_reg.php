<?php
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  require 'm_user.php';

  class Response {
    public $errorCode = '';
    public $errorText = '';
    public $id = 0;
  }

  if (isset($_POST['email'])) {
    $res = new Response;
    $login = $_POST['email'];

    if (!filter_var($login, FILTER_VALIDATE_EMAIL) or $login == '') {
      $res->errorText = "E-mail адрес указан не верно!";
      $res->errorCode = "reg/email_wrong";
      exit(json_encode($res));
    }

    $user = getUser("post", $_POST['email']);
		if (count($user) > 0) {
      $res->errorText = "E-mail адрес уже используется!";
      $res->errorCode = "reg/email_isset";
      exit(json_encode($res));
    }

    $res->id = createUser($login, $_POST['pass']);
    
    exit(json_encode($res));
  }
?>