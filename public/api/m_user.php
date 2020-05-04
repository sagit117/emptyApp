<?php	
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  // setHash 				  - установить хэш в сессию 
  // issetSession 	  - проверяет наличие записи в сессии пользователя 
  // getSession 		  - получить значение из сессии 
  // setSession 		  - установить значение в сессию пользователя
  // getHashUser 		  - получить из БД значение хэш пользователя по ИД и агенту
  // getUser 				  - получить данные пользователя/или нескольких по полю(ключу)
  // updateUserHash	  - обновить запись хэш в БД
  // generateCode		  - генерация кода
  // updateUser 		  - изменить поле в таблице пользователь по условию
  // createUser 		  - создать пользователя
  // getUserWithHash  - получить пользователя по хэш

  require 's_connect.php';

  class User {
    public $id = 0;
    public $pass = "";
    public $post = "";
    public $name = "";
    public $surname = "";
    public $patronymic = "";
    public $datereg = "";
    public $rule = "";
    public $last_connect = "";
  }

  function getUser($key, $value) {
    // получить данные пользователя по полю
    global $link;
    $key = mysqli_real_escape_string($link, $key);
    $value = mysqli_real_escape_string($link, $value);
    $result = mysqli_query($link, "SELECT * FROM `user` WHERE `$key`='$value'");
    $arr = array();

    while ($user = mysqli_fetch_assoc($result)) {
      $usr = new User();
      $usr->id = $user['id'];
      $usr->pass = $user['password'];
      $usr->post = $user['post'];
      $usr->name = $user['name'];
      $usr->surname = $user['surname'];
      $usr->patronymic = $user['patronymic'];
      $usr->datereg = $user['datereg'];
      $usr->rule = $user['rule'];
      $usr->last_connect = $user['last_connect'];

      array_push($arr, $usr); // собираем всех найденных пользователей в массив
    }
    return $arr;
  }

  function updateUserHash($id_user) {
    // обновить запись хэш в БД
    global $link;
    $id_user = intval($id_user);
    $hash = md5(generateCode(10));
    $agent = mysqli_real_escape_string($link, $_SERVER["HTTP_USER_AGENT"]);

    $result = mysqli_query($link, "SELECT `id` FROM `hash_user` WHERE `id_user`='$id_user' AND `agent`='$agent' LIMIT 1");
    $id = intval(mysqli_fetch_row($result)[0]);

    if ($id === 0) { // если пользователь не найден
      mysqli_query($link, "INSERT INTO `hash_user` (`id_user`, `agent`, `hash`) 
                            VALUES ('$id_user', '$agent', '$hash')") or die(mysqli_error($link));
    } else {
      mysqli_query($link, "UPDATE `hash_user` SET `hash`='$hash' WHERE `id`='$id'") or die(mysqli_error($link));
    }

    return $hash;
  }

  /*function getSession($name) {
    // получить значение из сессии
    session_start();
    $str = $_SESSION[$name];
    session_write_close();

    return $str;
  }

  function setSession($name, $value) {
    // установить значение в сессию пользователя
    session_start();
    $_SESSION[$name] = $value;
    session_write_close();
  }

  function issetSession($name) {
    // проверяет наличие записи в сессии пользователя
    session_start();
     $str = isset($_SESSION[$name]); 
     session_write_close();

     return $str; // возвращает значение true / false
  }

  function setHash() { 
    // установка хэш в сессию пользователя
    $hash = md5(generateCode(10)); 	// генерация хэш кода
    setSession('hash', $hash);			// установка хэш кода в сессию
    return $hash;
  }*/

  function generateCode($length = 6) { // Генератор кода
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789-_";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
      $code .= $chars[mt_rand(0, $clen)];
      }
    return $code;
  }

  function updateUser($field, $value, $conditionField, $conditionValue) { // изменить поле в таблице пользователь по условию
    global $link;
    $field = mysqli_real_escape_string($link, $field);
    $value = mysqli_real_escape_string($link, $value);
    $conditionField = mysqli_real_escape_string($link, $conditionField);
    $conditionValue = mysqli_real_escape_string($link, $conditionValue);

    mysqli_query($link, "UPDATE `user` SET `$field`='$value' WHERE `$conditionField`='$conditionValue'") or die(mysqli_error($link));
    return true;
  }

  function createUser($user, $pass) {
    // создать пользователя
    global $link;
    $user = mysqli_real_escape_string($link, $user);
    $pass = md5(md5($pass));
    $datereg = time();

    mysqli_query($link, "INSERT INTO `user` (`post`, `password`, `datereg`) VALUES ('$user', '$pass', '$datereg')") or die(mysqli_error($link));
    return mysqli_insert_id($link);
  }

  function getUserWithHash($hash) { // получить пользователя по хэш
    global $link;
    $hash = mysqli_real_escape_string($link, $hash);
    $agent = mysqli_real_escape_string($link, $_SERVER["HTTP_USER_AGENT"]);

    $result = mysqli_query($link, 
      "SELECT * FROM `user` 
      LEFT JOIN `hash_user` 
      ON user.id=hash_user.id_user 
      WHERE hash_user.agent='$agent' AND hash_user.hash='$hash' ");
    
    $arr = array();

    while ($user = mysqli_fetch_assoc($result)) {
      $usr = new User();
      $usr->id = $user['id_user'];
      //$usr->pass = $user['password'];
      $usr->post = $user['post'];
      $usr->name = $user['name'];
      $usr->surname = $user['surname'];
      $usr->patronymic = $user['patronymic'];
      $usr->datereg = $user['datereg'];
      $usr->rule = $user['rule'];
      $usr->last_connect = $user['last_connect'];

      array_push($arr, $usr); // собираем всех найденных пользователей в массив
    }

    return $arr;
  }

  /*function getHashUser($id_user) {
    // получить значение хэш из БД по ИД пользователя и агенту
    global $link;
    $id_user = intval($id_user);
    $agent = mysqli_real_escape_string($link, $_SERVER["HTTP_USER_AGENT"]);
    $result = mysqli_query($link, "SELECT `hash` FROM `hash_user` WHERE `id_user`='$id_user' AND `agent`='$agent' LIMIT 1");

    return mysqli_fetch_row($result)[0]; // вернуть первый найденый
  }*/
  
?>