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
      </div>
    </div>
    <!-- SweetAlert2 JS -->
    <script src="<?php echo base_url('assets/vendors/sweetalert2/dist/sweetalert2.all.min.js'); ?>"></script>
    <script>
      // Enhanced error feedback using SweetAlert2 instead of browser alerts
      // Provides better UX with consistent styling and clear error categorization

      // Session timeout notification - helps users understand why they need to login again
      <?php if ($this->session->flashdata('session_expired')): ?>
        Swal.fire({
          icon: 'warning',
          title: 'Session Expired',
          text: '<?php echo addslashes($this->session->flashdata('session_expired')); ?>'
        });
      <?php endif; ?>

      // Form validation errors - consolidates multiple errors into one clean message
      <?php if(validation_errors()): 
        $validation = trim(preg_replace('/\s+/', ' ', strip_tags(validation_errors())));
      ?>
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          html: '<?php echo addslashes($validation); ?>'
        });
      <?php endif; ?>

      // Authentication errors - clearly distinguishes auth failures from validation issues
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