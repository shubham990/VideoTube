<?php require_once("includes/config.php"); ?>
<?php require_once("includes/classes/FormSanitizer.php"); ?>
<?php require_once("includes/classes/Account.php"); ?>
<?php require_once("includes/classes/Constants.php"); ?>

<?php
$account = new Account($con);
if (isset($_POST["submitButton"])) {

  $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
  $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

  $wasSuccessful = $account->login($username, $password);
  if ($wasSuccessful) {
    $_SESSION["userLoggedIn"] = $username;
    header("Location: index.php");
  }
}



function getInputValue($name)
{
  if (isset($_POST[$name])) {
    echo $_POST[$name];
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VideoTube</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <!-- JS, Popper.js, and jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</head>

<body>
  <div class="signInContainer">
    <div class="column">
      <div class="header">
        <img src="assets/images/icons/VideoTubeLogo.png" alt="site logo" title="logo">
        <h3>Sign In</h3>
        <span>to continue to VideoTube</span>
      </div>
      <div class="loginForm">
        <form action="signIn.php" method="POST">
          <?php echo $account->getError(Constants::$loginFailed); ?>
          <input type="text" name="username" value="<?php getInputValue('username'); ?>" placeholder="Username" autocomplete="off" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="submit" name="submitButton" value="SUBMIT">
        </form>
      </div>
      <a href="signUp.php" class="signInMessage">Need an account? Sign UP here!</a>
    </div>

  </div>
</body>

</html>