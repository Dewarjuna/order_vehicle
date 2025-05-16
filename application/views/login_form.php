<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Pemesanan Kendaraan</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/vendors/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('assets/vendors/nprogress/nprogress.css'); ?>" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url('assets/vendors/animate.css/animate.min.css'); ?>" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="<?php echo base_url('assets/vendors/sweetalert2/dist/sweetalert2.min.css'); ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('assets/build/css/custom.min.css'); ?>" rel="stylesheet">
    <style>
      body.login {
        background: linear-gradient(120deg, #f8fafc, #c0cfe2 90%);
      }
      h1 {
        font-weight: 600;
      }
      .login_content {
        box-shadow: 0 4px 24px rgba(0,0,0,.04), 0 1.5px 10px rgba(100,130,180,.16);
        border-radius: 12px;
        padding: 30px 35px 35px 35px;
        background: #fff;
      }
      .login_content .btn.submit {
        background: #2a3f54;
        color: #fff;
        font-weight: 500;
        transition: background .2s;
      }
      .login_content .btn.submit:hover {
        background: #203346;
      }
      .reset_pass,
      .change_link {
        color: #888;
        font-size: 90%;
      }
      .change_link a {
        pointer-events: none;
        color: #bbb;
        text-decoration: line-through;
        cursor: not-allowed;
      }
      .fa-car {
        color: #2a3f54;
      }
    </style>
  </head>
  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="<?php echo site_url('auth/login'); ?>" method="post">
              <h1>Login</h1>
              <div>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required value="<?php echo set_value('username'); ?>" autofocus />
              </div>
              <div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
              </div>
              <div>
                <button class="btn btn-default submit" type="submit">Log in</button>
                <a class="reset_pass" href="#">Lost your password?</a>
              </div>
              <div class="clearfix"></div>
              <div class="separator">
                <p class="change_link">
                  <!-- Registration has been disabled
                  New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                  -->
                  New to site?
                  <span style="color: #bbb; text-decoration: line-through; cursor: not-allowed;">Create Account</span>
                </p>
                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><i class="fa fa-car"></i> Login Pemesanan Kendaraan</h1>
                  <p style="color:#888;font-size:90%;">Copyright &copy; <?php echo date('Y'); ?>. All rights reserved.</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <!-- 
        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form>
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <a class="btn btn-default submit" href="index.html">Submit</a>
              </div>
              <div class="clearfix"></div>
              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>
                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><i class="fa fa-paw"></i> Login Pemesanan Kendaraan</h1>
                  <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>
        -->
      </div>
    </div>
    <!-- SweetAlert2 JS -->
    <script src="<?php echo base_url('assets/vendors/sweetalert2/dist/sweetalert2.all.min.js'); ?>"></script>
    <script>
      // Show SweetAlert2 for session expired
      <?php if ($this->session->flashdata('session_expired')): ?>
        Swal.fire({
          icon: 'warning',
          title: 'Session Expired',
          text: '<?php echo addslashes($this->session->flashdata('session_expired')); ?>'
        });
      <?php endif; ?>

      // Show SweetAlert2 for CodeIgniter validation errors
      <?php if(validation_errors()): 
        $validation = trim(preg_replace('/\s+/', ' ', strip_tags(validation_errors())));
      ?>
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          html: '<?php echo addslashes($validation); ?>'
        });
      <?php endif; ?>

      // Show SweetAlert2 for custom error
      <?php if (isset($error)): ?>
        Swal.fire({
          icon: 'error',
          title: 'Login Failed',
          text: '<?php echo addslashes($error); ?>'
        });
      <?php endif; ?>
    </script>
  </body>
</html>