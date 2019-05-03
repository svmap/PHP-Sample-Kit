<nav class="navbar navbar-expand-md bg-primary navbar-dark ">
  <a class="navbar-brand" href="#">Site Name</a>
  <?php 
    if(isset($_SESSION['ssid']))
    {
      ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li> 
        </ul>
      </div> 
      <form class="form-inline" action="/action_page.php">
        <input class="form-control mr-sm-2" type="text" placeholder="Search">
        <button class="btn btn-warning" type="submit">Search</button>
      </form>
      <?php
    }
  ?>
</nav>