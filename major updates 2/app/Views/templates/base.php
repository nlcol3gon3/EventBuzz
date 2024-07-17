<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EventBuzz</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CDN for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    .navbar {
      background-color: #e91e63;
    }
    .navbar-brand {
      color: #ffffff;
      font-weight: bold;
    }
    .navbar-nav .nav-link {
      color: #ffffff;
      font-weight: 500;
    }
    .navbar-nav .nav-link:hover {
      color: #ffffff;
	}
	.bg-pink {
      background-color: #e91e63;
	}
	.text-pink{
      color: #e91e63;
	}
	.thead-pink {
            background-color: #f8d7da;
            color: #d63384;
    }
	.btn-pink {
      background-color: #e91e63;
      color: #ffffff;
	}
	.btn-pink:hover {
	  background-color: #FF77FF;
	}

    .hero {
      background: url('/background.jpeg') no-repeat center center;
      background-size: cover;
      color: #ffffff;
      text-align: center;
      padding: 150px 0;
    }
    .hero h1 {
      font-size: 3.5em;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .hero p {
      font-size: 1.5em;
      margin-bottom: 30px;
    }
    .cta {
      background-color: #e91e63;
      color: #ffffff;
      text-align: center;
      padding: 100px 0;
    }
    .cta h2 {
      font-size: 2.5em;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .cta p {
      font-size: 1.2em;
      margin-bottom: 30px;
    }
    .footer {
      background-color: #e91e63;
      color: #ffffff;
      padding: 50px 0 10px 0;
      text-align: left;
    }
    .footer a {
      color: #ffffff;
text-decoration: none;
    }
    .footer a:hover {
      text-decoration: underline;
    }
    .social-icons {
      font-size: 1.5em;
      margin-top: 20px;
    }
    .social-icons a {
      color: #ffffff;
      margin: 0 10px;
    }
    .social-icons a:hover {
      color: #ffc107;
    }
  </style>
</head>
<body>
	<!-- Navigation Bar -->
	<?= view('templates/navbar'); ?>
    <!-- Content Section -->
    <div style="min-height:50vh;">
        <?= $content ?>
	</div>

    <!-- Footer Section -->
    <?= view('templates/footer'); ?>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
