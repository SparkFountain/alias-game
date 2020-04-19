<?php
  // Allow from any origin
  if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
    // whitelist of safe domains
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
  }

  $method = $_SERVER['REQUEST_METHOD'];

  // Access-Control headers are received during OPTIONS requests
  if ($method == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
      header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
      header('Access-Control-Allow-Headers: '.$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]);
    }
  }

  /* RESPONSE HEADER */
  header('Content-Type: application/json; charset=utf-8');

  /* DATABASE CONNETION */
  $servername = 'localhost';
  $username = 'web23388256';
  $password = 'RZ0SdlIv2mutKjwejcRe';
  $dbname = 'usr_web23388256_4';

  /* RESPONSE STATUS CODES */
  define('STATUS_SUCCESS', 'success');
  define('STATUS_ERROR', 'error');
  define('STATUS_FAIL', 'fail');

  $db = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
  }
  $db->set_charset('utf8');

  switch($_SERVER['REQUEST_URI']) {
    case '/create-session':
      $sql = 'INSERT INTO session (`name`, `horizontal`, `vertical`, `theme`, `teamOneName`, `teamOneColor`, `teamTwoName`, `teamTwoColor`) ';
      $sql .= 'VALUES (';
      $sql .= '\'' . $_POST['name'] . '\', ';
      $sql .= $_POST['horizontal'] . ', ';
      $sql .= $_POST['vertical'] . ', ';
      $sql .= '\'' . $_POST['theme'] . '\', ';
      $sql .= '\'' . $_POST['teamOneName'] . '\', ';
      $sql .= '\'' . $_POST['teamOneColor'] . '\', ';
      $sql .= '\'' . $_POST['teamTwoName'] . '\', ';
      $sql .= '\'' . $_POST['teamTwoColor'] . '\'';
      $sql .= ');';

      // die($sql);

      $result = $db->query($sql);
      if (!$db->error) {
        echo json_encode(array('status' => STATUS_SUCCESS));
      } else {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
      }
      break;
  }
?>