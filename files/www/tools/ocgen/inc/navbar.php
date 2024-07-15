<?php
    $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
    $url = end($url_array);
?>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgba(0,123,255,.25);" style="font-family:courier">
<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i>
    <a class="navbar-brand" style="font-family:courier" href="#">Config Generator</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if ($url === 'index.php') echo 'active'; ?>">
                <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php if ($url === 'config.php') echo 'active'; ?>">
                <a class="nav-link" href="config.php"><i class="fa fa-gear"></i> Config Generator</a>
            </li>
            <li class="nav-item <?php if ($url === 'vmess.php') echo 'active'; ?>">
                <a class="nav-link" href="vmess.php"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Vmess</a>
            </li>
			<li class="nav-item <?php if ($url === 'vless.php') echo 'active'; ?>">
                <a class="nav-link" href="vless.php"><i class="fa fa-gg-circle" aria-hidden="true"></i> Vless</a>
            </li>
			<li class="nav-item <?php if ($url === 'trojan.php') echo 'active'; ?>">
                <a class="nav-link" href="trojan.php"><i class="fa-solid fa-horse" aria-hidden="true"></i> Trojan</a>
            </li>
			<li class="nav-item <?php if ($url === 'ss.php') echo 'active'; ?>">
                <a class="nav-link" href="ss.php"><i class="fa-regular fa-paper-plane" aria-hidden="true"></i> Shadowsocks</a>
            </li>
			<li class="nav-item <?php if ($url === 'about.php') echo 'active'; ?>">
                <a class="nav-link" href="about.php"><i class="fa-brands fa-github-alt"></i> About</a>
            </li>
          
        </ul>
    </div>
</nav>