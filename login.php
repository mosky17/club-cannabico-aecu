<?php
session_start();

require_once(dirname(__FILE__) . '/config.php');

?>

<script type="text/javascript">
    var GLOBAL_domain = "<?php echo $GLOBALS['domain']; ?>";
    var GLOBAL_short_name = "<?php echo $GLOBALS['short_name']; ?>";
    var GLOBAL_name = "<?php echo $GLOBALS['name']; ?>";
</script>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title><?php echo $GLOBALS['name']; ?></title>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="scripts1.0.10/toolbox.js"></script>
    <script src="scripts1.0.10/login.js"></script>

    <!-- Le styles -->
    <link href="styles1.0.9/bootstrap3.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="styles1.0.9/main.css" rel="stylesheet">
    <style type="text/css">
        /* Override some defaults */
        body {
            background-color: #333;
            padding-top: 40px;
        }

        .container {
            width: 300px;
        }

        input {
            margin: 5px 0;
        }

        /* The white background content wrapper */
        .container > .content {
            background-color: #fff;
            padding: 0 20px;
            margin: 0 -20px;
            -webkit-border-radius: 10px 10px 10px 10px;
            -moz-border-radius: 10px 10px 10px 10px;
            border-radius: 10px 10px 10px 10px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
        }

        .login-form {
            text-align: center;
            padding: 20px 20px 5px;
        }

        .login-form input[type=text],
        .login-form input[type=password] {
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 8px;
            width: 100%;
            font-size: 15px;
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
    <img class="logo_login" src="logo_aecu_white.png">
    <div class="content">
        <div class="row">
            <div class="login-form">
                <div id="feedbackContainer"></div>
                <form id="loginForm" action="<?php echo $GLOBALS['domain']; ?>" method="POST">
                    <fieldset>
                        <div class="clearfix">
                            <input id="loginEmail" name="username" type="text" placeholder="Email">
                        </div>
                        <div class="clearfix">
                            <input id="loginPassword" name="password" type="password" placeholder="Password">
                        </div>
                        <input type="submit" id="loginBtnIngresar" name="submit" class="btn btn-primary"
                               value="Ingresar" onClick="Login.Login();">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="login-name"><?php echo $GLOBALS['name']; ?></div>
</div>
<!-- /container -->
</body>
</html>
