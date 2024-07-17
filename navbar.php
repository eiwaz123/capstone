<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/hofin/styles/css/navbar.css">
<link rel="icon" type="image/png" sizes="32x32"
  href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/hofin/styles/images/favicon.png">

<?php
require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/functions.php');
?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" /> -->

<nav class="navbar" style="user-select: none;">
  <?php
  $current_page = basename($_SERVER['PHP_SELF']);
  ?>
  <?php
  // $current_page = basename($_SERVER['PHP_SELF']);
  if ($current_page == "index.php" || $current_page == "" || $current_page == "face_login.php" || $current_page == "register.php") {
    ?>
    <a class="btn disabled">&#8801;</a>
    <?php
  } else {
    ?>
    <a href="#sidenav" class="btn open">&#8801;</a>
    <?php
  }
  ?>
  <div class="sidenav" id="sidenav">
    <ul>
      <li class="center user">
        <?php
        $id = (isset($_SESSION['user_id']));
        $pic = getpic($id);
        // echo $pic;
        
        if (!empty($pic) && file_exists($_SERVER['DOCUMENT_ROOT'] . $pic)) {
          ?>
          <img draggable="false" src="/hofin/<?php echo $pic; ?>/0.jpg" alt="User" />
          <?php
        } else if ((!isset($pic)) || $pic != false) {
          ?>
            <img draggable="false" src="/hofin/face/noprofile.jpg" alt="User" />
          <?php
        } else {
        }
        if (isset($_SESSION['fullname'])) {
          echo '<p>' . $_SESSION['fullname'] . '<p>';
        }
        ?>
      </li>

      <?php
      if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 'yes') {
        ?>
        <li class="divider"></li>
        <li class="title">Administration</li>
        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>">
          <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/admin/dashboard.php" draggable="false"><i
              class="fa-solid fa-gauge"></i> Dashboard</a>
        </li>
        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/admin/members_info.php" draggable="false"><i class="fa-solid fa-address-book"></i> Members
            Info</a></li>


        <li class="title">Payment Management</li>
        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/admin/payment_arrangement.php" draggable="false"><i class="fa-solid fa-address-book"></i> Payment Arrangement</a></li>
        
        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/admin/payment_verification.php" draggable="false"><i class="fa-solid fa-address-book"></i> Payment verification</a></li>
         <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/admin/payment_history.php" draggable="false"><i class="fa-solid fa-address-book"></i> Payment History</a></li>

<!-- 
        <li class="item {% if request.path == '/admin/payment_arrangement' %}active{% endif %}"><a
            href="/admin/payment_arrangement" draggable="false"><i class="fa-solid fa-note-sticky"></i> Payment
            Arrangement</a></li> -->

        <!-- <li class="item {% if request.path == '/members/payment_history' %}active{% endif %}"><a
            href="/admin/payment_verification" draggable="false"><i class="fa-solid fa-money-check-dollar"></i> Payment
            Verification</a></li> -->
        <!-- <li class="item {% if request.path == '/admin/payment_history' %}active{% endif %}"><a
            href="/admin/payment_history" draggable="false"><i class="fa-solid fa-cash-register"></i> Payment History</a>
        </li> -->

        <li class="title">Face Recognition</li>
        <li class="item {% if request.path == '/faceregister' %}active{% endif %}"><a href="/faceregister"
            draggable="false"><i class="fa-solid fa-face-smile"></i> Face Register</a></li>

      <?php }

      if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 'no') {
        ?>
        <li class="divider"></li>
        <li class="title">User Management</li>
        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/member/home.php" draggable="false"><i class="fa-solid fa-address-book"></i> Members Info</a></li>

<!-- 
        <li class="item {% if request.path == '/members/home' %}active{% endif %}"><a href="/members/home"
            draggable="false"><i class="fa-solid fa-circle-info"></i> Members Info</a></li> -->

        <li class="title">Payment Management</li>
        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/member/payment.php" draggable="false"><i class="fa-solid fa-address-book"></i> Members Payment</a></li>

        <li class="item <?php if ($current_page == 'index.php') {
          echo 'active';
        } else {
          echo "";
        } ?>"><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/member/payment_history.php" draggable="false"><i class="fa-solid fa-address-book"></i> Payment History</a></li>

        <!-- <li class="item {% if request.path == '/members/payment_history' %}active{% endif %}"><a
            href="/members/payment_history" draggable="false"><i class="fa-solid fa-money-check-dollar"></i> Payment
            History</a></li> -->


        <li class="title">Face Recognition</li>
        <li class="item {% if request.path == '/faceregister' %}active{% endif %}"><a href="/faceregister"
            draggable="false"><i class="fa-solid fa-face-smile"></i> Face Register</a></li>
      <?php } ?>

      <div class="flex">
        <div class="flex-half">
          <li><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/admin/account.php" draggable="false"><i
                class="fa-solid fa-user-tie"></i> Profile</a></li>
        </div>
        <div class="flex-half">
          <li><a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/logout.php" draggable="false"><i
                class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </div>
      </div>
    </ul>
  </div>
  <a href="#!" class="close sidenav-overlay" draggable="false"></a>

  <a class="navbar-brand" href="/hofin" draggable="false">
    <img draggable="false" src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/hofin/styles/images/logo-s.png" alt="logo-s"
      class="navbar-img" />
    HOA Finance
  </a>
  <div class="username">
    <h3>
      <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 'yes') { ?>
        <i class="fa-solid fa-user-tie"></i>
      <?php }
      if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 'no') { ?>
        <i class="fa-solid fa-user"></i>
      <?php } ?>
      <?php if (isset($_SESSION['usertype'])) {
        echo $_SESSION['usertype'];
      } ?>
    </h3>

  </div>
</nav>
<!-- DASH BOARD -->
<?php 
            $info = getinfo();
            $unverified_count = count($info['unverified_members']);
            $incomplete_count = count($info['incomplete_members']);
            $completed_count = count($info['completed_members']);
            $approve_count = count($info['approve_members']);
            $delete_count = count($info['deleted_members']);

            $payment_approval = count($info['payment_approval']);
            $approve_payment = count($info['history']);
            $not_yet_paid = count($info['notyet_paid']);
          
            $money_collected = $info['money_collected']
            
        ?>


<?php
// Include the function file

?>

<!-- EDIT MEMBERS INFO PHP CODE -->



<!-- END OF MEMBERS INFO -->
<!-- Prevent the user from being able to navigate back to a specific page using the browser's back button -->
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>