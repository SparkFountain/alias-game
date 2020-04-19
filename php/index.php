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
      $sql = 'INSERT INTO `session` (`creator`, `name`, `horizontal`, `vertical`, `theme`, `seed`) ';
      $sql .= 'VALUES (';
      $sql .= '\'' . $_POST['creator'] . '\', ';
      $sql .= '\'' . $_POST['name'] . '\', ';
      $sql .= $_POST['horizontal'] . ', ';
      $sql .= $_POST['vertical'] . ', ';
      $sql .= '\'' . $_POST['theme'] . '\',';
      $sql .= '\'' . $_POST['seed'] . '\'';
      $sql .= ');';
      $result = $db->query($sql);
      if ($db->error) {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
        die();
      }

      $sql = 'INSERT INTO `team` (`session`, `name`, `color`, `remainingCards`) ';
      $sql .= 'VALUES (';
      $sql .= '\'' . $_POST['name'] . '\', ';
      $sql .= '\'' . $_POST['teamOneName'] . '\', ';
      $sql .= '\'' . $_POST['teamOneColor'] . '\', ';
      $sql .= '0';
      $sql .= ');';
      $result = $db->query($sql);
      if ($db->error) {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
        die();
      }

      $sql = 'INSERT INTO `team` (`session`, `name`, `color`, `remainingCards`) ';
      $sql .= 'VALUES (';
      $sql .= '\'' . $_POST['name'] . '\', ';
      $sql .= '\'' . $_POST['teamTwoName'] . '\', ';
      $sql .= '\'' . $_POST['teamTwoColor'] . '\', ';
      $sql .= '0';
      $sql .= ');';
      $result = $db->query($sql);
      if ($db->error) {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
        die();
      }

      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/get-sessions':
      $sql = 'SELECT `name`, `creator` FROM `session`';

      $result = $db->query($sql);
      if (!$db->error) {
        $sessions = array();
        while($row = $result->fetch_assoc()) {
          $teams = array();

          $sqlTeams = 'SELECT `name`, `color`, `remainingCards` FROM `team` WHERE `session` = \'' . $row['name'] . '\'';
          $teamResult = $db->query($sqlTeams);
          if ($db->error) {
            echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
            die();
          }

          while($teamRow = $teamResult->fetch_assoc()) {
            array_push($teams, $teamRow['name']);
          }

          array_push($sessions, array(
            'name' => $row['name'],
            'creator' => $row['creator'],
            'teams' => $teams
          ));
        }

        echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessions));
      } else {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
      }
      break;
    case '/join-session':
      if(!userExists($_POST['participant'])) {
        $sql = 'INSERT INTO player (`name`, `session`, `team`) ';
        $sql .= 'VALUES (';
        $sql .= '\'' . $_POST['participant'] . '\', ';
        $sql .= '\'' . $_POST['session'] . '\', ';
        $sql .= '\'' . $_POST['team'] . '\'';
        $sql .= ');';

        $result = $db->query($sql);
        if ($db->error) {
          echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
        }
      }

      $sqlSession = 'SELECT `name`, `creator`, `horizontal`, `vertical`, `theme`, `seed` FROM `session` WHERE `name` = \'' . $_POST['session'] . '\'';
      $sessionResult = $db->query($sqlSession);
      if ($db->error) {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
      }

      $session = array();
      while($sessionRow = $sessionResult->fetch_assoc()) {
        $session['name'] = $sessionRow['name'];
        $session['creator'] = $sessionRow['creator'];
        $session['horizontal'] = $sessionRow['horizontal'];
        $session['vertical'] = $sessionRow['vertical'];
        $session['theme'] = $sessionRow['theme'];
        $session['seed'] = $sessionRow['seed'];
      }

      $teams = array();
      $sqlTeams = 'SELECT `name`, `color`, `remainingCards` FROM `team` WHERE `session` = \'' . $session['name'] . '\'';
      $teamResult = $db->query($sqlTeams);
      if ($db->error) {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
        die();
      }

      while($teamRow = $teamResult->fetch_assoc()) {
        $players = array();
        $sqlPlayers = 'SELECT `name` FROM `player` WHERE `session` = \'' . $session['name'] . '\' AND `team` = \'' . $teamRow['name'] . '\'';
        $playerResult = $db->query($sqlPlayers);
        if ($db->error) {
          echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
          die();
        }

        while($playerRow = $playerResult->fetch_assoc()) {
          array_push($players, $playerRow['name']);
        }

        array_push($teams, array(
          'name' => $teamRow['name'],
          'color' => $teamRow['color'],
          'remainingCards' => $teamRow['remainingCards'],
          'players' => $players
        ));
      }

      $sessionData = array(
        'name' => $_POST['session'],
        'creator' => $session['creator'],
        'horizontal' => $session['horizontal'],
        'vertical' => $session['vertical'],
        'theme' => $session['theme'],
        'seed' => $session['seed'],
        'teams' => $teams
      );

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionData));
      break;
    case '/select-card':
      $sql = 'UPDATE player SET ';
      $sql .= '`selectedX` = ' . $_POST['x'] . ', ';
      $sql .= '`selectedY` = ' . $_POST['y'];
      $sql .= ' WHERE `name` = \'' . $_POST['participant'] . '\'';
      $result = $db->query($sql);
      if ($db->error) {
        echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
        die();
      }

      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-session-state':
      // 
      break;
  }

  function userExists($user) {
    $db = $GLOBALS['db'];

    $sqlCheckUser = 'SELECT id FROM `player` WHERE `name` = \'' . $user . '\'';
    $checkUserResult = $db->query($sqlCheckUser);
    if ($db->error) {
      echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
    }

    return $checkUserResult->num_rows > 0;
  }
?>