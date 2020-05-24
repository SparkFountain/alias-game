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
  switch(strtok($_SERVER["REQUEST_URI"], '?')) {
    case '/create-session':
      createSession($_POST['creator'], $_POST['name'], $_POST['horizontal'], $_POST['vertical'], $_POST['theme']);

      createTeam($_POST['name'], $_POST['teamOneName'], $_POST['teamOneColor']);
      createTeam($_POST['name'], $_POST['teamTwoName'], $_POST['teamTwoColor']);

      if(!playerInTeam($_POST['name'], $_POST['teamOneName'], $_POST['creator'])) {
        createPlayer($_POST['creator'], $_POST['name'], $_POST['teamOneName']);
      }

      createSessionColors($_POST['name'], $_POST['horizontal'], $_POST['vertical'], $_POST['teamOneColor'], $_POST['teamTwoColor']);

      $session = getSession($_POST['name']);
      $teams = getSessionTeams($_POST['name']);
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

      $cards = getSessionTerms($_POST['name']);

      $sessionData = array(
        'name' => $_POST['name'],
        'creator' => $session['creator'],
        'horizontal' => $session['horizontal'],
        'vertical' => $session['vertical'],
        'theme' => $session['theme'],
        'teams' => $finalTeams,
        'cards' => $cards
      );

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionData));
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
    case '/get-session-colors':
      $sessionColors = getSessionColors($_GET['session']);
      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionColors));
      break;
    case '/join-session':
      if(!playerInTeam($_POST['session'], $_POST['team'], $_POST['participant'])) {
        createPlayer($_POST['participant'], $_POST['session'], $_POST['team']);
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

      $cards = getSessionTerms($_POST['session']);

      $sessionData = array(
        'name' => $_POST['session'],
        'creator' => $session['creator'],
        'horizontal' => $session['horizontal'],
        'vertical' => $session['vertical'],
        'theme' => $session['theme'],
        'teams' => $finalTeams,
        'cards' => $cards
      );

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionData));
      break;
    case '/select-card':
      selectCard($_POST['session'], $_POST['x'], $_POST['y']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-cards':
      $cards = getSessionTerms($_GET['session']);

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $cards));
      break;
    case '/fetch-teams':
      $teams = getSessionTeams($_GET['session']);
      $finalTeams = array();
      foreach($teams as $team) {
        $players = getTeamPlayers($_GET['session'], $team['name']);
        array_push($finalTeams, array(
          'name' => $team['name'],
          'color' => $team['color'],
          'active' => $team['active'],
          'players' => $players
        ));
      }

      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $finalTeams));
      break;
    case '/request-term':
      requestTerm($_POST['session'], $_POST['word'], $_POST['amount']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-terms':
      $terms = fetchTerms($_GET['session']);
      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $terms));
      break;
    case '/request-active-player':
      requestActivePlayer();
      break;
    case '/exchange-term':
      exchangeTerm($_POST['session'], $_POST['x'], $_POST['y']);

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

    $sql = "SELECT id FROM `player` WHERE `name`='$user'";
    $result = $db->query($sql);
    checkForDatabaseError();

    return $result->num_rows > 0;
  }

  function playerInTeam($session, $team, $player) {
    $db = $GLOBALS['db'];

    $sql = "SELECT id FROM `player` WHERE `session`='$session' AND `team`='$team' AND `name`='$player'";
    $result = $db->query($sql);
    checkForDatabaseError();

    return $result->num_rows > 0;
  }

  /**
   * Creates a new session in the database.
   */
  function createSession($creator, $name, $horizontal, $vertical, $theme) {
    $db = $GLOBALS['db'];

    // create an array of indexes
    $sql = "SELECT id FROM `term` WHERE `category`='$theme'";
    $result = $db->query($sql);
    checkForDatabaseError();
    $indexes = array();
    while($row = $result->fetch_assoc()) {
      array_push($indexes, $row['id']);
    }
    shuffle($indexes);

    $sql = "INSERT INTO `session` (`creator`, `name`, `horizontal`, `vertical`, `theme`, `terms`) VALUES ('$creator', '$name', $horizontal, $vertical, '$theme', '" . json_encode($indexes) . "')";
    $db->query($sql);
    checkForDatabaseError();
  }

  /**
   * Creates random colors for a session.
   */
  function createSessionColors($session, $horizontal, $vertical, $teamAColor, $teamBColor) {
    $db = $GLOBALS['db'];

    switch ($horizontal * $vertical) {
      case 9:
        $r = rand(3, 4);
        $teamA = $r;
        $teamB = 7 - $r;
        $neutral = 1;
        $black = 1;
        break;
      case 12:
        $r = rand(4, 5);
        $teamA = $r;
        $teamB = 9 - $r;
        $neutral = 2;
        $black = 1;
        break;
      case 16:
        $r = rand(5, 6);
        $teamA = $r;
        $teamB = 11 - $r;
        $neutral = 4;
        $black = 1;
        break;
      case 20:
        $r = rand(6, 7);
        $teamA = $r;
        $teamB = 13 - $r;
        $neutral = 6;
        $black = 1;
        break;
      case 25:
        $r = rand(8, 9);
        $teamA = $r;
        $teamB = 17 - $r;
        $neutral = 7;
        $black = 1;
        break;
      case 30:
        $r = rand(9, 10);
        $teamA = $r;
        $teamB = 19 - $r;
        $neutral = 9;
        $black = 2;
        break;
      case 36:
        $r = rand(11, 12);
        $teamA = $r;
        $teamB = 23 - $r;
        $neutral = 10;
        $black = 3;
        break;
    }

    $colors = array();
    for ($i = 0; $i < $teamA; $i++) {
      array_push($colors, $teamAColor);
    }
    for ($i = 0; $i < $teamB; $i++) {
      array_push($colors, $teamBColor);
    }
    for ($i = 0; $i < $neutral; $i++) {
      array_push($colors, '#ffcc06');
    }
    for ($i = 0; $i < $black; $i++) {
      array_push($colors, '#222222');
    }
    shuffle($colors);

    $sessionColorsSql = "INSERT INTO `session-colors` (`session`, `x`, `y`, `color`) VALUES ";
    for($y = 0; $y < $vertical; $y++) {
      for($x = 0; $x < $horizontal; $x++) {
        $currentColor = $colors[$y*$horizontal + $x];
        $sessionColorsSql .= "('$session', $x, $y, '$currentColor')";
        if(($x+1) * ($y+1) < ($horizontal*$vertical)-1) {
          $sessionColorsSql .= ", ";
        }
      }
    }

    $db->query($sessionColorsSql);
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
  function createPlayer($name, $session, $team) {
    $db = $GLOBALS['db'];

    $active = $active ? 'true' : 'false';
    $sql = "INSERT INTO `player` (`name`, `session`, `team`) VALUES ('$name', '$session', '$team')";

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

    $sql = "SELECT `name`, `creator`, `horizontal`, `vertical`, `theme` FROM `session` WHERE `name` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    while($row = $result->fetch_assoc()) {
      $sessions = array(
        'name' => $row['name'],
        'creator' => $row['creator'],
        'horizontal' => $row['horizontal'],
        'vertical' => $row['vertical'],
        'theme' => $row['theme']
      );
    }

    return $sessions;
  }

  /**
   * Get all teams (2) that belong to a certain session.
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
   * Get all colors that belong to a certain session.
   */
  function getSessionColors($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `x`, `y`, `color` FROM `session-colors` WHERE `session` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    $colors = array();
    while($row = $result->fetch_assoc()) {
      array_push($colors, array(
        'x' => $row['x'],
        'y' => $row['y'],
        'color' => $row['color']
      ));
    }

    return $colors;
  }

  /**
   * Get all terms of a certain session.
   */
  function getSessionTerms($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `horizontal`, `vertical`, `terms` FROM `session` WHERE `name`='$session'";
    $result = $db->query($sql);
    checkForDatabaseError();
    while($row = $result->fetch_assoc()) {
      $horizontal = $row['horizontal'];
      $vertical = $row['vertical'];
      $termIndexes = json_decode($row['terms']);
    }

    $colors = getSessionColors($session);
    $cardAmount = $horizontal * $vertical;
    
    $sql = "SELECT `id`, `word` FROM `term` WHERE `id` IN (";
    for ($i=0; $i<$cardAmount; $i++) {
      $sql .= $termIndexes[$i];
      if($i<$cardAmount-1) {
        $sql .= ',';
      }
    }
    $sql .= ')';
    $result = $db->query($sql);
    checkForDatabaseError();

    $terms = array();
    while($row = $result->fetch_assoc()) {
      $terms[$row['id']] = $row['word'];
    }

    // now sort the terms
    $cards = array();
    $rowIndex = 0;
    for($j=0; $j<$cardAmount; $j++) {
      array_push($cards, array(
        'x' => $rowIndex % $horizontal,
        'y' => intdiv($rowIndex, $horizontal),
        'word' => $terms[$termIndexes[$j]],
        'color' => $colors[$rowIndex]['color']
      ));
      $rowIndex++;
    }

    return $cards;
  }

  function selectCard($session, $x, $y) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `color` FROM `session-colors` WHERE `session`='$session' AND `x`='$x' AND `y`='$y'";
    $result = $db->query($sql);
    checkForDatabaseError();
    while($row = $result->fetch_assoc()) {
      $color = $row['color'];
    }

    $updateColorSql = "UPDATE `session-terms` SET `color`='$color' WHERE `session`='$session' AND `x`='$x' AND `y`='$y'";
    $db->query($updateColorSql);
    checkForDatabaseError();
  }

  function requestTerm($session, $word, $amount) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `term` (`session`, `word`, `amount`, `accepted`) VALUES ('$session', '$word', $amount, false)";
    $db->query($sql);
    checkForDatabaseError();
  }

  function fetchTerms($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `id`, `word`, `amount` FROM `term` WHERE `session` = '$session' ORDER BY `id` ASC";
    $result = $db->query($sql);
    checkForDatabaseError();

    $terms = array();
    while($row = $result->fetch_assoc()) {
      array_push($terms, array(
        'id' => $row['id'],
        'word' => $row['word'],
        'amount' => $row['amount']
      ));
    }

    return $terms;
  }

  function exchangeTerm($session, $x, $y) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `horizontal`, `vertical`, `terms` FROM `session` WHERE `name` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    while($row = $result->fetch_assoc()) {
      $horizontal = $row['horizontal'];
      $vertical = $row['vertical'];
      $terms = json_decode($row['terms']);
    }

    $exchangeIndex = $y * $horizontal + $x;
    $exchangeTerm = $terms[$exchangeIndex];
    unset($terms[$exchangeIndex]);
    array_push($terms, $exchangeTerm);
    $terms = array_values($terms);

    $sql = "UPDATE `session` SET `terms`='".json_encode($terms)."' WHERE `name`='$session'";
    $db->query($sql);
    checkForDatabaseError();
  }
?>