<?php
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require 's_connect.php'; // Подключение к БД
    
  // table user
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `user` ("
    . "`id`             INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
    . "`post`           VARCHAR(64) NOT NULL,"
    . "`password`       VARCHAR(32),"
    . "`name`           VARCHAR(32),"
    . "`surname`        VARCHAR(32),"
    . "`patronymic`     VARCHAR(32),"
    . "`datereg`        VARCHAR(32),"
    . "`rule`           VARCHAR(32),"
    . "`last_connect`   VARCHAR(32))") or die('Ошибка запроса `user`! '.mysqli_error($link));

  // table user_hash
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `hash_user` ("
    . "`id`         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
    . "`id_user`    INT(11) NOT NULL,"
    . "`agent`      TEXT,"
    . "`hash`       VARCHAR(32))") or die('Ошибка запроса `user_hash`! '.mysqli_error($link));

  
?>