<?php 

//Set default values for variables
$die_type = '';
$roll_mode = 'normal';
$modifier = '0';
$result = null;
$finalresult = '';
$roll1 = null;
$roll2 = null;

//If a post request is made:
if ($_POST) {
  //capture the values
  $die_type = $_POST['die_type'] ?? '';
  $roll_mode = $_POST['roll_mode'] ?? '';
  $modifier = $_POST['mod'] ?? '';

  //The # of sides on the die is the substring of die_type starting at position 1 (to skip the 'd')
  $sides = substr($die_type, 1);

  //First dice roll =  a random number between 1 and the # of sides
  $roll1 = rand(1, $sides);

  //Do a second roll if rolling with advantage or disadvantage and determine the result.
  if ($roll_mode == "advantage") {
    $roll2 = rand(1, $sides);
    $result = max($roll1, $roll2);
  } elseif ($roll_mode == "disadvantage") {
    $roll2 = rand(1, $sides);
    $result = min($roll1, $roll2);
  } elseif ($roll_mode == "normal") {
    $result = $roll1;
    $roll2 = null; 
  }

  // Apply modifier (except for coin toss)
  if ($modifier && $die_type !== 'd2') {
    $result += $modifier; // Add modifier to result
  }

  // Ensure no result is lower than 1
  $result = max(1, $result); // The result, but never less than 1
  
  // Set final result so it can be displayed properly
  $finalresult = $result;

  // If the die type is a coin toss (d2), set the final result to 'Heads' or 'Tails'
  if ($die_type === 'd2') {
    $finalresult = $result === 1 ? 'Heads' : 'Tails';
    //if the result with d2 is exactly 1, show Heads, otherwise show Tails and make that the final result.
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DiceRoller</title>
  <meta name="author" content="Kelsey Richards">
  <meta name="description" content="A simple dice roller for tabletop RPGs">
  
  <!--Bootstrap for CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <!--Custom CSS-->
  <style>
     /* Style buttons on mouse hover */
    label.btn-outline-dark:hover  {
      box-shadow: 0 0 10px #e788ffff;
    }
  </style>

</head>

<body class="bg-light row">
  <div class="container bg-white col-10 col-md-9 col-lg-6 mx-auto">
    <!--Header Section-->
    <header class="text-center pt-4">
      <div class="d-flex flex-column-reverse flex-md-row justify-content-center d-md-inline-flex">
        <h1 class="display-3 px-2 mb-0" style="text-wrap:nowrap">Dice Roller</h1>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve" class="p-0 mx-auto" style="width:3rem">
          <path d="M454.6 111.2 280.6 6.8a48 48 0 0 0-49.2 0l-174 104.4q-8.1 4.9-13.6 12.2l208 124.7a8 8 0 0 0 8.5 0l207.9-124.7q-5.5-7.3-13.6-12.2m-296.9 19.1a36 36 0 0 1-39-.4c-10.7-7.9-10.4-20.5.5-28 11-7.7 28.5-7.5 39.1.4 10.6 7.8 10.4 20.4-.6 28m116.5.7a35 35 0 0 1-37.8-.4c-10.3-7.6-10-19.7.6-27.1a35 35 0 0 1 37.8.4c10.2 7.6 10 19.8-.6 27.1m117.7 1.7a36 36 0 0 1-39-.4c-10.7-7.9-10.4-20.5.5-28 11-7.7 28.5-7.5 39.1.4 10.6 7.8 10.4 20.4-.6 28M246.1 258.4l-208-124.9a48 48 0 0 0-3.9 18.6v208.1c0 16.8 8.8 32.3 23.2 41l174 104.4a48 48 0 0 0 18.8 6.4V265.5q-.2-4.7-4-7.1M75.8 369.7C63.8 363.2 54 348.1 54 336c0-12 9.8-16.5 21.8-10s21.9 21.7 21.9 33.8-9.8 16.5-21.9 10m0-121.8c-12-6.7-21.8-21.8-21.8-34s9.8-16.5 21.8-9.9S97.7 226 97.7 238s-9.8 16.5-21.9 10m122 188.3c-12.1-6.6-22-21.7-22-33.8s9.9-16.5 22-9.9 21.7 21.7 21.7 33.7-9.7 16.5-21.8 10m0-122c-12.1-6.5-22-21.6-22-33.6s9.9-16.5 22-10 21.7 21.7 21.7 33.8-9.7 16.5-21.8 9.9M474 133.5 265.9 258.4a8 8 0 0 0-4 7V512q9.8-1.2 18.7-6.4l174-104.4a48 48 0 0 0 23.2-41v-208q0-10-3.8-18.7M370.5 355.1c-19.3 10.5-35 3.4-35-15.9s15.7-43.4 35-54c19.3-10.5 34.9-3.3 34.9 16 0 19.2-15.6 43.4-35 54" />
        </svg>
      </div>
      <div class="p-3">
        <p>Digitally roll any tabletop role-playing game (TTRPG) die. Simply choose your die, pick how you want to roll, set your modifier, and hit "Roll!". May your digital dice always land in your favour.</p>
        <hr>
      </div>
    </header>

    <main class="ps-4">
      <!--Results Section-->
      <section class="<?php if (!$_POST) { echo "d-none"; }?> position-relative "> <!--If the there is no post request, add the bootstrap class "d-none" to hide this section -->
        <h2 class="display-6">Your Result</h2>
        <p class="w-100 text-center display-1 p-4 mb-2">
          <?php echo  htmlspecialchars($finalresult); ?>
        </p>
        
        <!--Result Details-->
        <p><?php
              if ($die_type === 'd2') {
                echo "";
            } else {
                echo "You rolled a <span class=\"fw-bold\">$die_type</span>";
                if (!empty($roll_mode)) {
                  echo " with the $roll_mode roll mode.";
                }
            }
          ?></p>

        <?php
          if ($die_type === 'd2') {
            //Coin toss details
            echo "<p>You flipped a coin and got <span class=\"fw-bold\">$finalresult</span>.</p>";

          } else {
            //More than one roll (advantage/disadvantage) details
            if (!empty($roll2)) {
              echo "<p>The results were <span class=\"fw-bold\">$roll1</span> and <span class=\"fw-bold\">$roll2</span>. This means the selected roll was <span class=\"fw-bold\">";
              if ($roll_mode == "advantage") {
                echo max($roll1, $roll2);
              } elseif ($roll_mode == "disadvantage") {
                echo min($roll1, $roll2);
              }
              echo "</span>.</p>";
            } else {
                //Single roll details
                echo "<p>The result was <span class=\"fw-bold\">$roll1</span>.</p>";
            }
            
            // If a modifier exists and is not 0, show the modifier and final result
            if (!empty($modifier) && $modifier != 0) {
              echo "<p>With a modifier of <span class=\"fw-bold\">$modifier</span>, the final result is <span class=\"fw-bold\">$finalresult</span>.</p>";
            }
          }
        ?>
          
        <!--Roll Again Button-->
        <label for="roll_button" class="align-self-center btn btn-success btn-sm btn-outline-success text-white">Roll Again
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve" class="p-0 mx-auto" style="width:1.5rem" fill="white" >
          <path d="M454.6 111.2 280.6 6.8a48 48 0 0 0-49.2 0l-174 104.4q-8.1 4.9-13.6 12.2l208 124.7a8 8 0 0 0 8.5 0l207.9-124.7q-5.5-7.3-13.6-12.2m-296.9 19.1a36 36 0 0 1-39-.4c-10.7-7.9-10.4-20.5.5-28 11-7.7 28.5-7.5 39.1.4 10.6 7.8 10.4 20.4-.6 28m116.5.7a35 35 0 0 1-37.8-.4c-10.3-7.6-10-19.7.6-27.1a35 35 0 0 1 37.8.4c10.2 7.6 10 19.8-.6 27.1m117.7 1.7a36 36 0 0 1-39-.4c-10.7-7.9-10.4-20.5.5-28 11-7.7 28.5-7.5 39.1.4 10.6 7.8 10.4 20.4-.6 28M246.1 258.4l-208-124.9a48 48 0 0 0-3.9 18.6v208.1c0 16.8 8.8 32.3 23.2 41l174 104.4a48 48 0 0 0 18.8 6.4V265.5q-.2-4.7-4-7.1M75.8 369.7C63.8 363.2 54 348.1 54 336c0-12 9.8-16.5 21.8-10s21.9 21.7 21.9 33.8-9.8 16.5-21.9 10m0-121.8c-12-6.7-21.8-21.8-21.8-34s9.8-16.5 21.8-9.9S97.7 226 97.7 238s-9.8 16.5-21.9 10m122 188.3c-12.1-6.6-22-21.7-22-33.8s9.9-16.5 22-9.9 21.7 21.7 21.7 33.7-9.7 16.5-21.8 10m0-122c-12.1-6.5-22-21.6-22-33.6s9.9-16.5 22-10 21.7 21.7 21.7 33.8-9.7 16.5-21.8 9.9M474 133.5 265.9 258.4a8 8 0 0 0-4 7V512q9.8-1.2 18.7-6.4l174-104.4a48 48 0 0 0 23.2-41v-208q0-10-3.8-18.7M370.5 355.1c-19.3 10.5-35 3.4-35-15.9s15.7-43.4 35-54c19.3-10.5 34.9-3.3 34.9 16 0 19.2-15.6 43.4-35 54" />
          </svg>
        </label>
        
        <hr class="pb-4">
      </section>

      <!--Main Form-->
      <form class="py-2" method="post" action="index.php" name="dice_roller_form">
        <h2 class="display-6">Configure Your Roll</h2>

        <!--Die Type-->
        <fieldset class="">
          <legend>Die Type <?php if (empty($die_type)) { echo "<span class=\"fs-6 text-danger\">*required</span>"; } ?></legend>
              <div class="d-flex flex-column flex-md-row gap-3 w-100" role="group">
                <!--Coin Toss-->
                <input type="radio" class="btn-check" name="die_type" id="cointoss" value="d2" required
                <?php if (($die_type ?? '') === 'd2') echo 'checked'; ?>>
                <label class="btn btn-outline-dark text-start" for="cointoss">Coin Toss (d2)</label>

                <!--Six-Sided-->
                <input type="radio" class="btn-check" name="die_type" id="sixsided" value="d6" required
                <?php if (($die_type ?? '') === 'd6') echo 'checked'; ?>>
                <label class="btn btn-outline-dark text-start" for="sixsided">Six-Sided (d6)</label>

                <!--Twenty-Sided-->
                <input type="radio" class="btn-check" name="die_type" id="twentysided" value="d20" required
                <?php if (($die_type ?? '') === 'd20') echo 'checked'; ?>>
                <label class="btn btn-outline-dark text-start" for="twentysided">Twenty-Sided (d20)</label>
              </div>
        </fieldset>

        <!--Roll Mode-->
        <fieldset class="py-5">
          <legend>Roll Mode</legend>
            <div class="d-flex flex-column gap-3 w-100" role="group">
              <!--Normal-->
              <input type="radio" class="btn-check" name="roll_mode" value="normal" id="roll_normal"
              <?php if (($roll_mode ?? '') === 'normal') echo 'checked'; ?>>
              <label class="btn btn-outline-dark text-start" for="roll_normal"><span class="fw-bolder">Normal</span><br><span class="text-secondary">Roll Once.</span></label>

              <!--Advantage-->
              <input type="radio" class="btn-check" name="roll_mode" value="advantage" id="roll_advantage"
              <?php if (($roll_mode ?? '') === 'advantage') echo 'checked'; ?>>
              <label class="btn btn-outline-dark text-start" for="roll_advantage"><span class="fw-bolder">Advantage</span><br><span class="text-secondary">Roll twice and take the <span class="fw-bold">higher</span> result</span></label>

              <!--Disadvantage-->
              <input type="radio" class="btn-check" name="roll_mode" value="disadvantage" id="roll_disadvantage"
              <?php if (($roll_mode ?? '') === 'disadvantage') echo 'checked'; ?>>
              <label class="btn btn-outline-dark text-start" for="roll_disadvantage"><span class="fw-bolder">Disadvantage</span><br><span class="text-secondary">Roll twice and take the <span class="fw-bold">lower</span> result</span></label>
            </div>
        </fieldset>

        <!--Modifier-->
        <fieldset class="pb-5">
          <legend>Modifier</legend>
          <p>Apply a numeric bonus or penalty (from -5 up to +5) to adjust your final roll total. <br><span class="text-secondary">Coin flips will not have a modifier.</span></p>

          <input type="radio" class="btn-check" name="mod" id="neg_5" value="-5"
          <?php if (($modifier ?? '') === '-5') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="neg_5">-5</label>

          <input type="radio" class="btn-check" name="mod" id="neg_3" value="-3"
          <?php if (($modifier ?? '') === '-3') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="neg_3">-3</label>

          <input type="radio" class="btn-check" name="mod" id="neg_1" value="-1"
          <?php if (($modifier ?? '') === '-1') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="neg_1">-1</label>

          <input type="radio" class="btn-check" name="mod" id="zero" value="0"
          <?php if (($modifier ?? '') === '0') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="zero">0</label>

          <input type="radio" class="btn-check" name="mod" id="plus_1" value="+1"
          <?php if (($modifier ?? '') === '+1') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="plus_1">+1</label>

          <input type="radio" class="btn-check" name="mod" id="plus_3" value="+3"
          <?php if (($modifier ?? '') === '+3') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="plus_3">+3</label>

          <input type="radio" class="btn-check" name="mod" id="plus_5" value="+5"
          <?php if (($modifier ?? '') === '+5') echo 'checked'; ?>>
          <label class="btn btn-outline-dark" for="plus_5">+5</label>
        </fieldset>

        <!--Submit Button-->
        <div class="d-flex justify-content-center mb-2">
          <button type="submit" value="" class="align-self-center btn btn-success btn-lg btn-outline-success text-white" id="roll_button">Roll!
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve" class="p-0 mx-auto" style="width:1.5rem" fill="white" >
              <path d="M454.6 111.2 280.6 6.8a48 48 0 0 0-49.2 0l-174 104.4q-8.1 4.9-13.6 12.2l208 124.7a8 8 0 0 0 8.5 0l207.9-124.7q-5.5-7.3-13.6-12.2m-296.9 19.1a36 36 0 0 1-39-.4c-10.7-7.9-10.4-20.5.5-28 11-7.7 28.5-7.5 39.1.4 10.6 7.8 10.4 20.4-.6 28m116.5.7a35 35 0 0 1-37.8-.4c-10.3-7.6-10-19.7.6-27.1a35 35 0 0 1 37.8.4c10.2 7.6 10 19.8-.6 27.1m117.7 1.7a36 36 0 0 1-39-.4c-10.7-7.9-10.4-20.5.5-28 11-7.7 28.5-7.5 39.1.4 10.6 7.8 10.4 20.4-.6 28M246.1 258.4l-208-124.9a48 48 0 0 0-3.9 18.6v208.1c0 16.8 8.8 32.3 23.2 41l174 104.4a48 48 0 0 0 18.8 6.4V265.5q-.2-4.7-4-7.1M75.8 369.7C63.8 363.2 54 348.1 54 336c0-12 9.8-16.5 21.8-10s21.9 21.7 21.9 33.8-9.8 16.5-21.9 10m0-121.8c-12-6.7-21.8-21.8-21.8-34s9.8-16.5 21.8-9.9S97.7 226 97.7 238s-9.8 16.5-21.9 10m122 188.3c-12.1-6.6-22-21.7-22-33.8s9.9-16.5 22-9.9 21.7 21.7 21.7 33.7-9.7 16.5-21.8 10m0-122c-12.1-6.5-22-21.6-22-33.6s9.9-16.5 22-10 21.7 21.7 21.7 33.8-9.7 16.5-21.8 9.9M474 133.5 265.9 258.4a8 8 0 0 0-4 7V512q9.8-1.2 18.7-6.4l174-104.4a48 48 0 0 0 23.2-41v-208q0-10-3.8-18.7M370.5 355.1c-19.3 10.5-35 3.4-35-15.9s15.7-43.4 35-54c19.3-10.5 34.9-3.3 34.9 16 0 19.2-15.6 43.4-35 54" />
            </svg>
          </button>
        </div>

      </form>
    </main>

    <footer>
      <hr>
      <p class="fs-5 text-secondary text-center pt-3">Disclaimer</p>
      <p class="fs-6 text-secondary text-center pb-4 mb-4">This website was created as an academic exercise</p>
    </footer>

  </div>
</body>

</html>