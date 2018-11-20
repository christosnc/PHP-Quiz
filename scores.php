<html>
	<head>
    <link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<div class="top-bar">
	    <div class="top-bar-item"><a href="index.php">Play</a></div>
	    <div class="top-bar-item"><a href="help.php">Help</a></div>
	    <div class="top-bar-item"><a href="scores.php">Leaderboard</a></div>
	  </div>
		<div class="button-top" onclick="document.body.scrollTop = 0; document.documentElement.scrollTop = 0;"><img class="button-top-icon" src="up-arrow.svg"/></div>
	  <div class="bottom-bar">
	    <div class="bottom-text">christosnc &copy; 2018. All rights reserved.</div>
	  </div>
    <?php
    if(isset($_POST["addscore"])){
      //Add a new score to the leaderboard
      $nickname = $_POST["nickname"];
      $points = $_POST["points"];
      $error = 0;
      $file = @file_get_contents("scores.json");
      if($file === false) $error = 1;
      $file = json_encode($file);
      $data = json_decode($file, true);
      $data = json_decode($data,true);

      $inserted = 0;
      $position = 0;
      for($i = count($data) - 1; $i >= 0; $i--){
        if(intval($data[$i]["points"]) < intval($points)){
          $inserted = 1;
          $position = $i;
          $data[$i + 1] = $data[$i];
          $data[$i]["nickname"] = $nickname;
          $data[$i]["points"] = $points;
        } else break;
      }

      if($inserted == 0){
        $index = count($data);
        $position = $index;
        $data[$index]["nickname"] = $nickname;
        $data[$index]["points"] = $points;
      }

      if($error == 0) file_put_contents("scores.json", json_encode($data));

      $ret = "";
      if($error == 1){
        $ret .= "<div class='add-unsuccessful'></div>";
        $ret .= "<form method='POST' action='index.php'>";
      } else{
        $ret .= "<div class='add-successful'></div>";
        $ret .= "<form method='POST' action='scores.php'>";
      }
      $ret .= "<button style='display:none;' name='highlight'></button>";
      $ret .= "<input style='display:none;' name='index' value='" . $position . "'>";
      $ret .= "<script>setTimeout(function(){ document.querySelector('button').click() }, 1000);</script>";
      $ret .= "</form>";
      echo($ret);
    } else if(isset($_POST["highlight"])){
      //Highlight the newly added score
      $file = json_encode(file_get_contents("scores.json"));
      $data = json_decode($file, true);
      $data = json_decode($data,true);
      $index = $_POST["index"];

      $ret = "";
      $ret .= "<div class='page-title'>Leaderboard</div>";
      $ret .= "<form class='box'>";
      $ret .= "<div class='scores-top-row'>";
      $ret .= "<div class='scores-top-rank'>Rank</div>";
      $ret .= "<div class='scores-top-nickname'>Nickname</div>";
      $ret .= "<div class='scores-top-points'>Points</div>";
      $ret .= "</div>";
      for($i = 0; $i < count($data); $i++){
        if(intval($index) == $i) $ret .= "<div class='scores-row' style='background-color: #FD971F;'>";
        else{
          if($i % 2 == 0) $ret .= "<div class='scores-row'>";
          else $ret .= "<div class='scores-row' style='background-color: #3b3b3b;'>";
        }
        $ret .= "<div class='scores-rank'>" . ($i + 1) . "</div>";
        $ret .= "<div class='scores-nickname'>" . $data[$i]["nickname"] . "</div>";
        $ret .= "<div class='scores-points'>" . $data[$i]["points"] . "</div>";
        $ret .= "</div>";
      }
      $ret .= "</form>";
      echo($ret);
    } else{
      //Just display the leaderboard
      $file = json_encode(file_get_contents("scores.json"));
      $data = json_decode($file, true);
      $data = json_decode($data,true);

      $ret = "";
      $ret .= "<div class='page-title'>Leaderboard</div>";
      $ret .= "<form class='box'>";
      $ret .= "<div class='scores-top-row'>";
      $ret .= "<div class='scores-top-rank'>Rank</div>";
      $ret .= "<div class='scores-top-nickname'>Nickname</div>";
      $ret .= "<div class='scores-top-points'>Points</div>";
      $ret .= "</div>";
      for($i = 0; $i < count($data); $i++){
        if($i % 2 == 0) $ret .= "<div class='scores-row'>";
        else $ret .= "<div class='scores-row' style='background-color: #3b3b3b;'>";
        $ret .= "<div class='scores-rank'>" . ($i + 1) . "</div>";
        $ret .= "<div class='scores-nickname'>" . $data[$i]["nickname"] . "</div>";
        $ret .= "<div class='scores-points'>" . $data[$i]["points"] . "</div>";
        $ret .= "</div>";
      }
      $ret .= "</form>";
      echo($ret);
    }
    ?>
	</body>
</html>
