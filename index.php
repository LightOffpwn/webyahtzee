<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>yahtzee</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css" />
  <meta name="author" content="Romain Hebert" />
  <meta name="description" content="Jeu du Yahtzee" />
  <script >"use strict";
    function hoverin(a, x) {
      document.getElementById(a + "score").placeholder = x;
    }
    function hoverout(a) {
      document.getElementById(a + "score").removeAttribute("placeholder");
    }
  </script>
</head>
<body>
<?php
  session_start();
  if (isset($_POST["end"])) {
    session_unset();
    session_destroy();
  }
  //Inscription-Score---------------------------------------------------------------------------------------------------
  $nom = array("1", "2", "3", "4", "5", "6", "brelan", "carre", "full", "petite", "grande", "yahtzee", "chance");
  for ($k = 0; $k <= 12; ++$k) {
    if (isset($_POST[$nom[$k]])) {
      $_SESSION[$nom[$k] . "checked"] = true;
    }
  }
  $totalbscore = 0;
  $totalhscore = 0;
  $totalchecked = 0;
  //Lancer--------------------------------------------------------------------------------------------------------------
  if (isset($_POST["roll"])) {
    for ($k = 1; $k <= 5; ++$k) {
      if (!$_POST["keep" . $k]) {
        $_SESSION["die" . $k] = random_int(1, 6);
      }
    }
    $_SESSION["tries"] += 1;
  } else {
    for ($k = 1; $k <= 5; ++$k) {
      $_SESSION["die" . $k] = 1;
    }
    $_SESSION["tries"] = 0;
  }
  //Calcules-Scores-----------------------------------------------------------------------------------------------------
  $dice = $_SESSION["die1"] .",". $_SESSION["die2"] .",". $_SESSION["die3"] .",". $_SESSION["die4"] .","
      . $_SESSION["die5"];
  $explodeddice = explode(",", $dice);
  sort($explodeddice);
  $dice = implode($explodeddice);
  $diceocc = substr_count($dice, '1') . substr_count($dice, '2') . substr_count($dice, '3') . substr_count($dice, '4')
      . substr_count($dice, '5') . substr_count($dice, '6');
  $dicesum = $_SESSION["die1"] + $_SESSION["die2"] + $_SESSION["die3"] + $_SESSION["die4"] + $_SESSION["die5"];
  $isyahtzee = (substr_count($diceocc, "5") == 1);
  for ($k = 0; $k <= 12; ++$k) {
    if (!isset($_SESSION[$nom[$k] . "checked"])) {
      if ($k >= 0 && $k <= 5) {
        $_SESSION[$nom[$k] . "score"] = ($k + 1) * $diceocc[$k];
      }
      if ($nom[$k] == "brelan") {
        if (substr_count($diceocc, '3') == 1 || substr_count($diceocc, '4') == 1 || $isyahtzee) {
          $_SESSION[$nom[$k] . "score"] = $dicesum;
        } else {
          $_SESSION[$nom[$k] . "score"] = 0;
        }
      }
      if ($nom[$k] == "carre") {
        if (substr_count($diceocc, '4') == 1 || $isyahtzee) {
          $_SESSION[$nom[$k] . "score"] = $dicesum;
        } else {
          $_SESSION[$nom[$k] . "score"] = 0;
        }
      }
      if ($nom[$k] == "full") {
        if (substr_count($diceocc, '3') == 1 && substr_count($diceocc, '2') == 1
            || ($isyahtzee && isset($_SESSION["yahtzeechecked"]))) {
          $_SESSION[$nom[$k] . "score"] = 25;
        } else {
          $_SESSION[$nom[$k] . "score"] = 0;
        }
      }
      if ($nom[$k] == "petite") {
        $nodoublesdice = implode(array_unique($explodeddice));
        if (strstr($nodoublesdice, "1234") || strstr($nodoublesdice, "2345")
            || strstr($nodoublesdice, "3456") || ($isyahtzee && isset($_SESSION["yahtzeechecked"]))) {
          $_SESSION[$nom[$k] . "score"] = 30;
        } else {
          $_SESSION[$nom[$k] . "score"] = 0;
        }
      }
      if ($nom[$k] == "grande") {
        if ($dice == "12345" || $dice == "23456" || ($isyahtzee && isset($_SESSION["yahtzeechecked"]))) {
          $_SESSION[$nom[$k] . "score"] = 40;
        } else {
          $_SESSION[$nom[$k] . "score"] = 0;
        }
      }
      if ($nom[$k] == "yahtzee") {
        if ($isyahtzee) {
          $_SESSION[$nom[$k] . "score"] = 50;
        } else {
          $_SESSION[$nom[$k] . "score"] = 0;
        }
      }
      if ($nom[$k] == "chance") {
        $_SESSION[$nom[$k] . "score"] = $dicesum;
      }
    } else {
    //Totaux------------------------------------------------------------------------------------------------------------
      if ($k >= 0 && $k <= 5) {
        $totalhscore += $_SESSION[$nom[$k] . "score"];
        ++$totalchecked;
      } else {
        $totalbscore += $_SESSION[$nom[$k] . "score"];
        ++$totalchecked;
      }
    }
  }
  $totalscore = $totalbscore + $totalhscore;
  $gameover = ($totalchecked == 13);
  //--------------------------------------------------------------------------------------------------------------------
?>
<form action="index.php" method="post">
  <fieldset>
    <legend>Section haute</legend>
    <input class="button" type="submit" name="1" value="As"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["1checked"])) echo "disabled";?>
        onmouseover="hoverin('1', <?php echo $_SESSION["1score"]; ?>)" onmouseout="hoverout('1')" />
    <input id="1score" class="textbox" type="text"
        <?php if(isset($_SESSION["1checked"])) echo " value=\"" . $_SESSION["1score"] . "\"";?> readonly>
    <br />

    <input class="button" type="submit" name="2" value="Deux"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["2checked"])) echo "disabled";?>
        onmouseover="hoverin('2', <?php echo $_SESSION["2score"]; ?>)" onmouseout="hoverout('2')" />
    <input id="2score" class="textbox" type="text"
        <?php if(isset($_SESSION["2checked"])) echo " value=\"" . $_SESSION["2score"] . "\"";?> readonly>
    <br />

    <input class="button" type="submit" name="3" value="Trois"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["3checked"])) echo "disabled";?>
        onmouseover="hoverin('3', <?php echo $_SESSION["3score"]; ?>)" onmouseout="hoverout('3')" />
    <input id="3score" class="textbox" type="text"
        <?php if(isset($_SESSION["3checked"])) echo " value=\"" . $_SESSION["3score"] . "\"";?> readonly>
    <br />

    <input class="button" type="submit" name="4" value="Quatre"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["4checked"])) echo "disabled";?>
        onmouseover="hoverin('4', <?php echo $_SESSION["4score"]; ?>)" onmouseout="hoverout('4')" />
    <input id="4score" class="textbox" type="text"
        <?php if(isset($_SESSION["4checked"])) echo " value=\"" . $_SESSION["4score"] . "\"";?> readonly>
    <br />

    <input class="button" type="submit" name="5" value="Cinq"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["5checked"])) echo "disabled";?>
        onmouseover="hoverin('5', <?php echo $_SESSION["5score"]; ?>)" onmouseout="hoverout('5')" />
    <input id="5score" class="textbox" type="text"
        <?php if(isset($_SESSION["5checked"])) echo " value=\"" . $_SESSION["5score"] . "\"";?> readonly>
    <br />

    <input class="button" type="submit" name="6" value="Six"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["6checked"])) echo "disabled";?>
        onmouseover="hoverin('6', <?php echo $_SESSION["6score"]; ?>)" onmouseout="hoverout('6')" />
    <input id="6score" class="textbox" type="text"
        <?php if(isset($_SESSION["6checked"])) echo " value=\"" . $_SESSION["6score"] . "\"";?> readonly />
    <br />

    <p>Total section haute: <input id="totalhscore" class="textbox" type="text"
        <?php echo " value=\"" . $totalhscore . "\"";?> readonly /></p>
  </fieldset>
  <fieldset>
    <legend>Section basse</legend>
    <input class="button" type="submit" name="brelan" value="Brelan"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["brelanchecked"])) echo "disabled";?>
        onmouseover="hoverin('brelan', <?php echo $_SESSION["brelanscore"]; ?>)" onmouseout="hoverout('brelan')" />
    <input id="brelanscore" class="textbox" type="text"
        <?php if(isset($_SESSION["brelanchecked"])) echo " value=\"" . $_SESSION["brelanscore"] . "\"";?> readonly />
    <br />

    <input class="button" type="submit" name="carre" value="Carré"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["carrechecked"])) echo "disabled";?>
        onmouseover="hoverin('carre', <?php echo $_SESSION["carrescore"]; ?>)" onmouseout="hoverout('carre')" />
    <input id="carrescore" class="textbox" type="text"
        <?php if(isset($_SESSION["carrechecked"])) echo " value=\"" . $_SESSION["carrescore"] . "\"";?> readonly />
    <br />

    <input class="button" type="submit" name="full" value="Full"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["fullchecked"])) echo "disabled";?>
        onmouseover="hoverin('full', <?php echo $_SESSION["fullscore"]; ?>)" onmouseout="hoverout('full')" />
    <input id="fullscore" class="textbox" type="text"
        <?php if(isset($_SESSION["fullchecked"])) echo " value=\"" . $_SESSION["fullscore"] . "\"";?> readonly />
    <br />

    <input class="button" type="submit" name="petite" value="Petite suite"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["petitechecked"])) echo "disabled";?>
        onmouseover="hoverin('petite', <?php echo $_SESSION["petitescore"]; ?>)" onmouseout="hoverout('petite')" />
    <input id="petitescore" class="textbox" type="text"
        <?php if(isset($_SESSION["petitechecked"])) echo " value=\"" . $_SESSION["petitescore"] . "\"";?> readonly />
    <br />

    <input class="button" type="submit" name="grande" value="Grande suite"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["grandechecked"])) echo "disabled";?>
        onmouseover="hoverin('grande', <?php echo $_SESSION["grandescore"]; ?>)" onmouseout="hoverout('grande')" />
    <input id="grandescore" class="textbox" type="text"
        <?php if(isset($_SESSION["grandechecked"])) echo " value=\"" . $_SESSION["grandescore"] . "\"";?> readonly />
    <br />

    <input class="button" type="submit" name="yahtzee" value="Yahtzee"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["yahtzeechecked"])) echo "disabled";?>
        onmouseover="hoverin('yahtzee', <?php echo $_SESSION["yahtzeescore"]; ?>)" onmouseout="hoverout('yahtzee')" />
    <input id="yahtzeescore" class="textbox" type="text"
        <?php if(isset($_SESSION["yahtzeechecked"])) echo  "value=\"" . $_SESSION["yahtzeescore"] . "\"";?> readonly />
    <br />

    <input class="button" type="submit" name="chance" value="Chance"
        <?php if (!isset($_POST["roll"]) || isset($_SESSION["chancechecked"])) echo "disabled";?>
        onmouseover="hoverin('chance', <?php echo $_SESSION["chancescore"]; ?>)" onmouseout="hoverout('chance')" />
    <input id="chancescore" class="textbox" type="text"
    <?php if(isset($_SESSION["chancechecked"])) echo " value=\"" . $_SESSION["chancescore"] . "\"";?> readonly />
    <br />

    <p>Total section basse: <input id="totalbscore" class="textbox" type="text"
        <?php echo " value=\"" . $totalbscore . "\"";?> readonly /></p>
    <p>Total: <input id="totalscore" class="textbox" type="text"
        <?php echo " value=\"" . $totalscore . "\"";?> readonly /></p>
  </fieldset>
</form>

<?php
  //Affichage-dé--------------------------------------------------------------------------------------------------------
  if (!$gameover) {
    $remaining = 3 - $_SESSION["tries"];
    echo "<p>Lancer(s) restant(s) : " . $remaining . ".</p>";
    echo "<br /><form action=\"index.php\" method=\"post\">";
    echo "<table><tr>";
    for ($k = 1; $k <= 5; ++$k) {
      echo "<td><img src=\"dice/" . $_SESSION["die" . $k] . ".png\" alt=\"" . $_SESSION["die" . $k] . "\"/></td>";
    }
    if ($_SESSION["tries"] < 3) {
      echo "<td><input type=\"submit\" name=\"roll\" value=\"Lancer!\" /></td></tr>";
      if (isset($_POST["roll"])) {
        echo "<tr>";
        for ($k = 1; $k <= 5; ++$k) {
          echo "<td><input type=\"checkbox\" name=\"keep" . $k . "\" ";
          if ($_POST["keep" . $k]) {
            echo "checked";
          }
          echo "></td>";
        }
        echo "</tr>";
      }
    }
    echo "</table></form>";
  //--------------------------------------------------------------------------------------------------------------------
  } else {
    echo "<h1>Partie Terminée!</h1>";
  }
?>
<br />
<form action="index.php" method="post">
  <input type="submit" name="end" value="Recommencer">
</form>
</body>
</html>
