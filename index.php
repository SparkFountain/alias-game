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

      $activeTeam = rand(0, 1) == 1;
      createTeam($_POST['name'], $_POST['teamOneName'], $_POST['teamOneColor'], $activeTeam ? true : false);
      createTeam($_POST['name'], $_POST['teamTwoName'], $_POST['teamTwoColor'], $activeTeam ? false : true);

      if(!playerInTeam($_POST['name'], $_POST['teamOneName'], $_POST['creator'])) {
        createPlayer($_POST['creator'], $_POST['name'], $_POST['teamOneName']);
      }

      createSessionColors($_POST['name'], $_POST['horizontal'], $_POST['vertical'], $_POST['teamOneColor'], $_POST['teamTwoColor'], $activeTeam);

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

      $cards = getSessionCards($_POST['name']);

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
    case '/join-session':
      if(!playerInTeam($_POST['session'], $_POST['team'], $_POST['participant'])) {
        createPlayer($_POST['participant'], $_POST['session'], $_POST['team']);
      }

      $sessionData = fetchSession($_POST['session']);
      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionData));
      break;
    case '/fetch-session':
      $sessionData = fetchSession($_GET['session']);
      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $sessionData));
      break;
    case '/select-card':
      selectCard($_POST['session'], $_POST['x'], $_POST['y']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-cards':
      $cards = getSessionCards($_GET['session']);

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
    case '/request-description':
      requestDescription($_POST['session'], $_POST['team'], $_POST['word'], $_POST['amount']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/accept-description':
      acceptDescription($_POST['session']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/deny-description':
      denyDescription($_POST['session']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-terms':
      $terms = fetchTerms($_GET['session']);
      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $terms));
      break;
    case '/request-active-player':
      requestActivePlayer($_POST['session'], $_POST['team'], $_POST['player']);
      break;
    case '/exchange-term':
      exchangeTerm($_POST['session'], $_POST['x'], $_POST['y']);
    case '/add-history-event':
      addHistoryEvent($_POST['session'], $_POST['team'], $_POST['description'], $_POST['amount'], $_POST['teamA'], $_POST['teamB'], $_POST['neutral'], $_POST['black']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/fetch-history':
      $history = fetchHistory($_GET['session']);
      echo json_encode(array('status' => STATUS_SUCCESS, 'data' => $history));
      break;
    case '/start-session':
      startSession($_POST['session']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/reset-session':
      resetSession($_POST['session']);
      echo json_encode(array('status' => STATUS_SUCCESS));
      break;
    case '/next-round':
      nextRound($_POST['session']);
      echo json_encode(array('status' => STATUS_SUCCESS));
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
  function createSessionColors($session, $horizontal, $vertical, $teamAColor, $teamBColor, $activeTeam) {
    $db = $GLOBALS['db'];

    switch ($horizontal * $vertical) {
      case 9:
        $teamA = $activeTeam ? 4 : 3;
        $teamB = 7 - $teamA;
        $neutral = 1;
        $black = 1;
        break;
      case 12:
        $teamA = $activeTeam ? 5 : 4;
        $teamB = 9 - $teamA;
        $neutral = 2;
        $black = 1;
        break;
      case 16:
        $teamA = $activeTeam ? 6 : 5;
        $teamB = 11 - $teamA;
        $neutral = 4;
        $black = 1;
        break;
      case 20:
        $teamA = $activeTeam ? 7 : 6;
        $teamB = 13 - $teamA;
        $neutral = 6;
        $black = 1;
        break;
      case 25:
        $teamA = $activeTeam ? 9 : 8;
        $teamB = 17 -  $teamA;
        $neutral = 7;
        $black = 1;
        break;
      case 30:
        $teamA = $activeTeam ? 10 : 9;
        $teamB = 19 - $teamA;
        $neutral = 9;
        $black = 2;
        break;
      case 36:
        $teamA = $activeTeam ? 12 : 11;
        $teamB = 23 - $teamA;
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
  function createTeam($session, $name, $color, $active) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `team` (`session`, `name`, `color`, `active`) VALUES ('$session', '$name', '$color', '$active')";
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

    $sql = "SELECT `name`, `creator`, `horizontal`, `vertical`, `theme`, `started` FROM `session` WHERE `name` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    while($row = $result->fetch_assoc()) {
      $sessions = array(
        'name' => $row['name'],
        'creator' => $row['creator'],
        'horizontal' => $row['horizontal'],
        'vertical' => $row['vertical'],
        'theme' => $row['theme'],
        'started' => $row['started']
      );
    }

    return $sessions;
  }

  /**
   * Get all teams (2) that belong to a session.
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
        'active' => $row['active'] ? true : false
      ));
    }

    return $teams;
  }

  /**
   * Get all colors that belong to a session.
   */
  function getSessionColors($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `x`, `y`, `color`, `uncovered` FROM `session-colors` WHERE `session` = '$session' ORDER BY y ASC, x ASC";
    $result = $db->query($sql);
    checkForDatabaseError();

    $colors = array();
    while($row = $result->fetch_assoc()) {
      array_push($colors, array(
        'x' => $row['x'],
        'y' => $row['y'],
        'color' => $row['color'],
        'uncovered' => $row['uncovered']
      ));
    }

    return $colors;
  }

  /**
   * Get all cards of a session.
   */
  function getSessionCards($session) {
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
        'color' => $colors[$rowIndex]['color'],
        'uncovered' => $colors[$rowIndex]['uncovered'] ? true : false
      ));
      $rowIndex++;
    }

    return $cards;
  }

  function selectCard($session, $x, $y) {
    $db = $GLOBALS['db'];

    $sql = "UPDATE `session-colors` SET `uncovered`=1 WHERE `session`='$session' AND `x`='$x' AND `y`='$y'";
    $db->query($sql);
    checkForDatabaseError();
  }

  function requestDescription($session, $team, $word, $amount) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `description` (`session`, `team`, `word`, `amount`, `accepted`) VALUES ('$session', '$team', '$word', $amount, -1)";
    $db->query($sql);
    checkForDatabaseError();
  }

  function getDescription($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `word`, `amount`, `accepted` FROM `description` WHERE `session`='$session' ORDER BY `id` DESC LIMIT 1";
    $result = $db->query($sql);
    checkForDatabaseError();

    if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        return array(
          'word' => $row['word'],
          'amount' => $row['amount'],
          'accepted' => $row['accepted']
        );
      }
    } else {
      return array(
        'word' => '',
        'amount' => 0,
        'accepted' => -1
      );
    }
  }

  function acceptDescription($session) {
    $db = $GLOBALS['db'];

    $sql = "UPDATE `description` SET `accepted`=1 WHERE `session`='$session'";
    $result = $db->query($sql);
    checkForDatabaseError();
  }

  function denyDescription($session) {
    $db = $GLOBALS['db'];

    $sql = "UPDATE `description` SET `accepted`=0 WHERE `session`='$session'";
    $result = $db->query($sql);
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

  function fetchHistory($session) {
    $db = $GLOBALS['db'];

    $sql = "SELECT `team`, `description`, `amount`, `teamA`, `teamB`, `neutral`, `black` FROM `history` WHERE `session` = '$session'";
    $result = $db->query($sql);
    checkForDatabaseError();

    $history = array();
    while($row = $result->fetch_assoc()) {
      array_push($history, array(
        'team' => $row['team'],
        'description' => $row['description'],
        'amount' => $row['amount'],
        'teamA' => $row['teamA'],
        'teamB' => $row['teamB'],
        'neutral' => $row['neutral'],
        'black' => $row['black']
      ));
    }

    return $history;
  }

  function addHistoryEvent($session, $team, $description, $amount, $teamA, $teamB, $neutral, $black) {
    $db = $GLOBALS['db'];

    $sql = "INSERT INTO `history` (`session`, `team`, `description`, `amount`, `teamA`, `teamB`, `neutral`, `black`) VALUES ('$session', '$team', '$description', $amount, $teamA, $teamB, $neutral, $black)";
    $db->query($sql);
    checkForDatabaseError();
  }

  function fetchSession($sessionName) {
    $db = $GLOBALS['db'];

    $session = getSession($sessionName);
    $cards = getSessionCards($sessionName);
    $remainingCards = array();
    foreach($cards as $card) {
      if(!array_key_exists($card['color'], $remainingCards)) {
        $remainingCards[$card['color']] = 0;
      }
      if(!$card['uncovered']) {
        $remainingCards[$card['color']]++;
      }
    }

    $teams = getSessionTeams($sessionName);
    $finalTeams = array();
    foreach($teams as $team) {
      $players = getTeamPlayers($session['name'], $team['name']);
      array_push($finalTeams, array(
        'name' => $team['name'],
        'color' => $team['color'],
        'active' => $team['active'],
        'players' => $players,
        'remainingCards' => $remainingCards[$team['color']]
      ));
    }

    $description = getDescription($sessionName);
    $finalDescription = array(
      'term' => $description['word'],
      'amount' => $description['amount'],
      'accepted' => $description['accepted'] == 1 ? true : false,
      'denied' => $description['accepted'] == 0 ? true : false
    );

    $sessionData = array(
      'name' => $sessionName,
      'creator' => $session['creator'],
      'horizontal' => $session['horizontal'],
      'vertical' => $session['vertical'],
      'theme' => $session['theme'],
      'teams' => $finalTeams,
      'cards' => $cards,
      'started' => $session['started'] ? true : false,
      'description' => $finalDescription
    );

    return $sessionData;
  }

  function startSession($session) {
    $db = $GLOBALS['db'];

    $sql = "UPDATE `session` SET `started`=1 WHERE `name`='$session'";
    $db->query($sql);
    checkForDatabaseError();
  }

  function requestActivePlayer($session, $team, $player) {
    $db = $GLOBALS['db'];

    $sql = "UPDATE `player` SET `active`=1 WHERE `session`='$session' AND `team`='$team' AND `name`='$player'";
    $db->query($sql);
    checkForDatabaseError();
  }

  function resetSession($session) {
    $db = $GLOBALS['db'];

    // check if any player already reset the session
    $sql = "SELECT `id` FROM `player` WHERE `session`='$session' AND `active`=1";
    $result = $db->query($sql);
    checkForDatabaseError();

    if($result->num_rows === 2) {
      // replace terms and reset started
      $sql = "SELECT `horizontal`, `vertical`, `terms` FROM `session` WHERE `name`='$session'";
      $result = $db->query($sql);
      checkForDatabaseError();

      while($row = $result->fetch_assoc()) {
        $terms = json_decode($row['terms']);
        $horizontal = $row['horizontal'];
        $vertical = $row['vertical'];
      }

      for($i=0; $i<$horizontal*$vertical; $i++) {
        array_shift($terms);
      }

      $sql = "UPDATE `session` SET `terms`='".json_encode($terms)."', `started`=0 WHERE `name`='$session'";
      $db->query($sql);
      checkForDatabaseError();

      // delete old session colors
      $sql = "DELETE FROM `session-colors` WHERE `session`='$session'";
      $db->query($sql);
      checkForDatabaseError();

      // create new teams with session colors
      $sql = "SELECT `name`, `color` FROM `team` WHERE `session`='$session'";
      $result = $db->query($sql);
      checkForDatabaseError();

      $teams = array();
      while($row = $result->fetch_assoc()) {
        array_push($teams, array(
          'name' => $row['name'],
          'color' => $row['color'],
        ));
      }

      // first, delete old team rows
      $sql = "DELETE FROM `team` WHERE `session`='$session'";
      $db->query($sql);
      checkForDatabaseError();

      // now, re-create them
      $activeTeam = rand(0, 1) == 1;
      createTeam($session, $teams[0]['name'], $teams[0]['color'], $activeTeam ? true : false);
      createTeam($session, $teams[1]['name'], $teams[1]['color'], $activeTeam ? false : true);

      createSessionColors($session, $horizontal, $vertical, $teams[0]['color'], $teams[1]['color'], $activeTeam);

      // unset active players
      $sql = "UPDATE `player` SET `active`=0 WHERE `session`='$session'";
      $db->query($sql);
      checkForDatabaseError();
    }
  }

  function nextRound($session) {
    $db = $GLOBALS['db'];

    $sql = "DELETE FROM `description` WHERE 1";
    $db->query($sql);
    checkForDatabaseError();

    $sql = "UPDATE `team` SET `active`=-1 WHERE `active`='1' AND `session`='$session'";
    $db->query($sql);
    checkForDatabaseError();

    $sql = "UPDATE `team` SET `active`=1 WHERE `active`=0 AND `session`='$session'";
    $db->query($sql);
    checkForDatabaseError();

    $sql = "UPDATE `team` SET `active`=0 WHERE `active`=-1 AND `session`='$session'";
    $db->query($sql);
    checkForDatabaseError();
  }
?>