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

  // ROUTING
  switch($_SERVER['REQUEST_URI']) {
    case '/create-session':
      createSession($_POST['creator'], $_POST['name'], $_POST['horizontal'], $_POST['vertical'], $_POST['theme'], $_POST['seed']);

      createTeam($_POST['name'], $_POST['teamOneName'], $_POST['teamOneColor']);
      createTeam($_POST['name'], $_POST['teamTwoName'], $_POST['teamTwoColor']);

      if(!userExists($_POST['creator'])) {
        createPlayer($_POST['creator'], $_POST['activeUser'], $_POST['name'], $_POST['teamOneName']);
      }

      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/get-sessions':
      $sessions = getAllSessions();
      $finalSessions = array();
      foreach ($sessions as $session) {
        $teams = getSessionTeams($session['name']);
        $finalTeams = array();
        foreach ($teams as $team) {
          $players = getTeamPlayers($session['name'], $team['name']);

          array_push($finalTeams, array(
            'name' => $team['name'],
            'color' => $team['creator'],
            'players' => $players
          ));
        }

        array_push($finalSessions, array(
          'name' => $session['name'],
          'creator' => $session['creator'],
          'teams' => $finalTeams
        ));
      }

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $finalSessions));
      break;
    case '/join-session':
      if(!userExists($_POST['participant'])) {
        createPlayer($_POST['participant'], $_POST['active'], $_POST['session'], $_POST['team']);
      }

      $session = getSession($_POST['session']);
      $teams = getSessionTeams($_POST['session']);
      $finalTeams = array();
      foreach($teams as $team) {
        $players = getTeamPlayers($session['name'], $team['name']);
        array_push($finalTeams, array(
          'name' => $team['name'],
          'color' => $team['color'],
          'active' => $team['active'],
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
        'teams' => $finalTeams
      );

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionData));
      break;
    case '/select-card':
      selectCard($_POST['session'], $_POST['x'], $_POST['y']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-session-state':
      // 
      break;
  }
  // END OF ROUTING

  /**
   * Checks if a database error occurred.
   */
  function checkForDatabaseError() {
    $db = $GLOBALS['db'];

    if ($db->error) {
      echo json_encode(array('status' => STATUS_FAIL, 'data' => $db->error));
      die();
    }
  }

  /**
   * Check if a user already exists in the database.
   */
  function userExists($user) {
    $db = $GLOBALS['db'];

    $sql = "SELECT id FROM `player` WHERE `name` = '$user'";
    $result = $db->query($sql);
    checkForDatabaseError();

    return $result->num_rows > 0;
  }

  /**
   * Creates a new session in the database.
   */
  function createSession($creator, $name, $horizontal, $vertical, $theme, $seed) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `session` (`creator`, `name`, `horizontal`, `vertical`, `theme`, `seed`) VALUES ('$creator', '$name', $horizontal, $vertical, '$theme', '$seed')";
    $db->query($sql);
    checkForDatabaseError();
  }

  /**
   * Creates a new team in the database.
   */
  function createTeam($session, $name, $color) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `team` (`session`, `name`, `color`) VALUES ('$session', '$name', '$color')";
    $db->query($sql);
    checkForDatabaseError();
  }

  /**
   * Creates a new user in the database.
   */
  function createPlayer($name, $active, $session, $team) {
    $db = $GLOBALS['db'];

    $active = $active ? 'true' : 'false';
    $sql = "INSERT INTO `player` (`name`, `active`, `session`, `team`) VALUES ('$name', $active, '$session', '$team')";

    $db->query($sql);
    checkForDatabaseError();
  }

  /**
   * Get all players of a certain team.
   */
  function getTeamPlayers($session, $team) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `name`, `active`, `selectedX`, `selectedY` FROM `player` WHERE `session` = '$session' AND `team` = '$team'";
    $result = $db->query($sql);
    checkForDatabaseError();

    $players = array();
    while($row = $result->fetch_assoc()) {
      array_push($players, array(
        'name' => $row['name'],
        'active' => $row['active'] ? true : false,
        'selectedX' => $row['selectedX'],
        'selectedY' => $row['selectedY']
      ));
    }

    return $players;
  }

  /**
   * Gets all sessions from the database that could be joined.
   */
  function getAllSessions() {
    $db = $GLOBALS['db'];

    $sql = 'SELECT `name`, `creator` FROM `session`';
    $result = $db->query($sql);
    checkForDatabaseError();

    $sessions = array();
    while($row = $result->fetch_assoc()) {
      array_push($sessions, array(
        'name' => $row['name'],
        'creator' => $row['creator']
      ));
    }

    return $sessions;
  }

  /**
   * Get a certain session by name from the database.
   */
  function getSession($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `name`, `creator`, `horizontal`, `vertical`, `theme`, `seed` FROM `session` WHERE `name` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    while($row = $result->fetch_assoc()) {
      $sessions = array(
        'name' => $row['name'],
        'creator' => $row['creator'],
        'horizontal' => $row['horizontal'],
        'vertical' => $row['vertical'],
        'theme' => $row['theme'],
        'seed' => $row['seed']
      );
    }

    return $sessions;
  }

  /**
   * Get all teams (2) that belong a certain session.
   */
  function getSessionTeams($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `name`, `color`, `active` FROM `team` WHERE `session` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    $teams = array();
    while($row = $result->fetch_assoc()) {
      array_push($teams, array(
        'name' => $row['name'],
        'color' => $row['color'],
        'active' => $row['active'] ? 'true' : 'false'
      ));
    }

    return $teams;
  }

  /**
   * Get all cards that have already been uncovered in a certain session.
   */
  function getSessionCards($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `x`, `y` FROM `card` WHERE `session` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    $cards = array();
    while($row = $result->fetch_assoc()) {
      array_push($cards, array(
        'x' => $row['x'],
        'y' => $row['y']
      ));
    }

    return $cards;
  }

  function selectCard($session, $x, $y) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `card` (`session`, `x`, `y`) VALUES ('$session', $x, $y)";
    $db->query($sql);
    checkForDatabaseError();
  }
?>