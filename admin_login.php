
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link   href="assets/css/bootstrap.min.css" rel="stylesheet">
  <script src="assets/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container" style="margin-top:30px">
    <div class="col-md-12">
      <div class="login-panel panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Sign In</h3>
        </div>
        <div class="panel-body">
          <form role="form" action="admin_process.php" method="POST">
            <fieldset>
              <div class="form-group">
                <input class="form-control" placeholder="username" name="username" type="text" autofocus="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Password" name="password" type="password" value="">
              </div>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me">Remember Me
                </label>
              </div>
              <!-- Change this to a button or input when using this as a form -->
              <button type="submit" class="btn btn-sm btn-success" name="submit">Login</a>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
