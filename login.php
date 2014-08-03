<?php
session_start();

require_once(dirname(__FILE__).'/config.php');

?>

<script type="text/javascript">
    var GLOBAL_domain = "<?php echo $GLOBALS['domain']; ?>";
    var GLOBAL_short_name = "<?php echo $GLOBALS['short_name']; ?>";
    var GLOBAL_name = "<?php echo $GLOBALS['name']; ?>";
</script>

<!DOCTYPE html>
  <head>
    <meta charset="utf-8">
    <title>Club Social de Cannabis #1</title>
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="scripts/toolbox.js"></script>
	<script src="scripts/login.js"></script>

    <!-- Le styles -->
    <link href="styles/bootstrap3.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
    <style type="text/css">
      /* Override some defaults */
      body {
        background-color: #eee;
          padding-top: 40px;
      }
      .container {
        width: 300px;
      }
      input{
          margin: 5px 0;
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px;
        -webkit-border-radius: 10px 10px 10px 10px;
           -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

	  .login-form {
		margin-left: 65px;
	  }

	  legend {
		margin-right: -50px;
		font-weight: bold;
	  	color: #404040;
	  }

    </style>

</head>
<body>
  <div class="container">
    <div class="content">
      <div class="row">
        <div class="login-form">
          <h2>Ingresar</h2>
	  <div id="feedbackContainer"></div>
          <form id="loginForm" action="<?php echo $GLOBALS['domain']; ?>" method="POST">
            <fieldset>
              <div class="clearfix">
                <input id="loginEmail" name="username" type="text" placeholder="Email">
              </div>
              <div class="clearfix">
                <input id="loginPassword" name="password" type="password" placeholder="Password">
              </div>
              <input type="submit" id="loginBtnIngresar" name="submit" class="btn btn-primary" value="Ingresar" onClick="Login.Login();">
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div> <!-- /container -->
</body>
</html>
