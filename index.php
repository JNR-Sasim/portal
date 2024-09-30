<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <title>Landing Page| Sasim </title>
  </head>
  <body>
    <nav>
      <div class="nav__header">
        <div class="nav__logo">
          <a href="#">Sasim</a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="#" id="ticket-link">Ticket</a></li>
        <li><a href="https://sasimit.co.za/contact-us.html">Contact</a></li>
        <li><a href="sign-in.php">Sign in</a></li>
      </ul>
      <div class="nav__btns">
        <button class="btn"><i class=""></i></button>
        <button class="btn"><i class=""></i></button>
      </div>
    </nav>
    <div class="container">
      <div class="container__left">
        <h1>Customer Ticket Management System </h1>
        <div class="container__btn">
          <a href="user-profile.php" class="btn">Register</a>
        </div>
      </div>
      <div class="container__right">
        <div class="images">
          <img src="" alt="" class="" />
          <img src="" alt="" />
        </div>
        <div class="content">
          <h4>How can we help you? </h4>
          <h2>Customer Ticket Management System</h2>
          <h3>Effortlessly Manage Customer Inquiries and Support Requests </h3>
          <p>
            Welcome to Sasim-IT's Ticket tracking system, the all-in-one solution for efficiently managing customer inquiries, technical support requests, and service tickets.
            Our key features include Efficient Ticket Creation, Smart Ticket Prioritization, and Integrated Knowledge Base.
          </p>
        </div>
      </div>
      <div class="location">
        <span><i class="ri-map-pin-2-fill"></i></span>
         Block B, Woodmead North Office Park, <br> 54 Maxwell Dr, Waterfall City,<br> 2190
      
      </div>
    </div>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
    <script>
       
        function isAuthenticated() {
     
            return false;
        }

        //handle navigation link click
        document.getElementById('ticket-link').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            if (isAuthenticated()) {
                window.location.href = 'tickets.php'; // Redirect to tickets.php if authenticated
            } else {
                window.location.href = 'sign-in.php'; // Redirect to sign-in.php if not authenticated
            }
        });
    </script>
  </body>
</html>
