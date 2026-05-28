<?php
session_start();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_forward" />

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Archivos CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body style="background-color: #F2F0EF !important;">

    <nav class="main-nav">
        <!-- ------------------------------------------------------------------ SIDE BAR --------------------------------------------------------------------------------->
        <ul class="sidebar">
            <li onclick="hideSidebar()"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg></a></li>
            <li><a href="aboutus.php">NOSOTROS</a></li>
            <li><a href="event.php">Eventos</a></li>
            <li><a href="support.php">Soporte</a></li>
            <?php if (isset($_SESSION["user_id"])): ?>
                <li><a href="login.php">Perfil</a></li>
            <?php else: ?>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="register.php">Registrarse</a></li>
            <?php endif; ?>
        </ul>
        <!-- ------------------------------------------------------------------ MAIN MENU --------------------------------------------------------------------------------->
        <ul class="main-menu">
            <li>
                <a href="index.php">
                    <img class="logo-nav" src="images/logo2-modified.png" alt="logo" id="logo-nav">
                </a>
            </li>
            <li class="hideOnMobile link"><a href="aboutus.php">NOSOTROS</a></li>
            <li class="hideOnMobile link"><a href="event.php">EVENTOS</a></li>
            <li class="hideOnMobile link"><a href="support.php">SOPORTE</a></li>

            <li>
                <form class="nav-form">
                    <input type="text" class="search-bx" placeholder="">
                    <input type="image" class="search-icon" src="images/search.svg" alt="Submit">
                </form>
            </li>
            <?php if (isset($_SESSION["user_id"])): ?>
                <li><a href="profile.php"><img src="images/icons/estandarPfp.jpg" alt="Pfp" class="pfpNav"></a></li>
            <?php else: ?>
                <li class="hideOnMobile"><button id="open-popup">LOG IN</button></li>
            <?php endif; ?>

            <li class="menu-button" onclick="showSidebar()"><a href="#"><svg xmlns="http://www.w3.org/2000/svg"
                        height="24px" viewBox="0 -960 960 960" width="26px" fill="#e8eaed">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg></a></li>
        </ul>
    </nav>
    <!-- -------------------------------------------------------------------------- LOG IN  --------------------------------------------------------------------------------->
    <div class="popup" id="popup">
        <div class="overlay"></div>
        <div class="popup-content">
            <h2>Login</h2>
            <form method="POST" action="../controller/UserController.php">
                <div class="login-box">
                    <?php
                    if (isset($_SESSION["error_message"])) {
                        echo "<p class='error-message'>" . $_SESSION["error_message"] . "</p>";
                        unset($_SESSION["error_message"]);
                    }
                    ?>
                    <input type="text" name="user" id="user" placeholder="Username" required>
                    <input type="hidden" name="login" value="login">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <input type="hidden" name="redirect" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                    <a href="register.php" class="atext">Problemas con la contraseña?</a>
                    <a href="register.php" class="atext">No tienes cuenta? Registrate!</a>
                </div>
                <div class="controls">
                    <button class="close-btn">Cancelar</button>
                    <button class="submit-btn" type="submit">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>

    <section class="login-section">
        <div class="login-grid">
            <div class="login-container">
                <h2>Regístrate</h2>
                <form method="POST" action="../controller/UserController.php">
                    <input type="hidden" name="register" value="register">

                    <div class="input-box">
                        <input type="text" name="name" id="name" placeholder="Nombre" required>
                    </div>

                    <div class="input-box">
                        <input type="text" name="lastname" id="lastname" placeholder="Apellidos" required>
                    </div>

                    <div class="input-box">
                        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required formnovalidate>
                    </div>

                    <div class="input-box">
                        <input type="text" name="user" id="user" placeholder="Username" required>
                    </div>

                    <input type="hidden" name="rol" id="rol" value="3">

                    <div class="input-box">
                        <input type="password" name="password" id="password" placeholder="Contraseña" required formnovalidate>
                    </div>

                    <div class="input-box">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmación Contraseña" required formnovalidate>
                    </div>



                    <button type="submit" class="btn-a">Registrarme</button>

                    <div class="register-box">
                        <p>Eres un administrador? <a href="registerAdmin.php">Registro para administradores</a></p>
                    </div>
                </form>
                <?php if (isset($_SESSION["register_error_message_email"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_email"];
                                                        unset($_SESSION["register_error_message_email"]); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION["register_error_message_rol"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_rol"];
                                                        unset($_SESSION["register_error_message_rol"]); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION["register_error_message_password"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_password"];
                                                        unset($_SESSION["register_error_message_password"]); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION["register_error_message_confirmation"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_confirmation"];
                                                        unset($_SESSION["register_error_message_confirmation"]); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION["register_error_message_image_size"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_image_size"];
                                                        unset($_SESSION["register_error_message_image_size"]); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION["register_error_message_image_format"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_image_format"];
                                                        unset($_SESSION["register_error_message_image_format"]); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION["register_error_message_sql"])): ?>
                    <div class="register-error-message"><?php echo $_SESSION["register_error_message_sql"];
                                                        unset($_SESSION["register_error_message_sql"]); ?></div>
                <?php endif; ?>
            </div>
        </div>

    </section>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-container-1-1">
                <p>FIRALIA</p>
            </div>
            <div class="footer-container-1-2">
                <p>Connecta con nosotros!</p>
                <nav>
                    <ul class="ul-apps">
                        <li><a href="#"><img src="images/icons/facebook.png" alt="Facebook"></a></li>
                        <li><a href="#"><img src="images/icons/instagram.png" alt="Instagram"></a></li>
                        <li><a href="#"><img src="images/icons/twitter.png" alt="X"></a></li>
                        <li><a href="#"><img src="images/icons/youtube.png" alt="YouTube"></a></li>
                        <li><a href="#"><img src="images/icons/tiktok.png" alt="TikTok"></a></li>
                    </ul>
                </nav>
            </div>
            <div class="footer-container-1-3">
                <p>Descarga Nuestra App</p>
                <nav>
                    <ul class="ul-download">
                        <li><a href="#"><img src="images/icons/appstore.png" alt="Apple Store"></a></li>
                        <li><a href="#"><img src="images/icons/googleplay.webp" alt="Google Play"></a></li>
                    </ul>
                </nav>
            </div>

            <div class="footer-container-2">
                <ul>
                    <li><a href="#">Política de Privacidad</a></li>
                    <li><a href="#">Política de Compra</a></li>
                    <li><a href="#">Terminos de Uso</a></li>
                    <li><a href="#">Política de Cookies</a></li>
                    <li><a href="#">Control de Cookies</a></li>
                    <li>
                        <p>© 2024-2025 FIRALIA. Todos los derechos reservados.</p>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function showSidebar() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function hideSidebar() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.style.display = 'none';
                document.body.style.overflow = '';
            }


            function createPopup(id) {
                let popupNode = document.querySelector(id);
                let overlay = popupNode.querySelector(".overlay");
                let closeBtn = popupNode.querySelector(".close-btn");

                function openPopup() {
                    popupNode.classList.add("active");
                    document.body.style.overflow = 'hidden';
                }

                function closePopup() {
                    popupNode.classList.remove("active");
                    document.body.style.overflow = '';
                }

                if (document.querySelector(".error-message")) {
                    openPopup();
                }

                overlay.addEventListener("click", closePopup);
                closeBtn.addEventListener("click", closePopup);

                return openPopup;
            }


            const img = document.getElementById('logo-nav');
            if (img) {
                img.addEventListener('mouseenter', () => {
                    img.src = 'images/logo2.png';
                });

                img.addEventListener('mouseleave', () => {
                    img.src = 'images/logo2-modified.png';
                });
            }


            document.querySelector(".menu-button")?.addEventListener("click", showSidebar);
            document.querySelector(".sidebar li:first-child")?.addEventListener("click", hideSidebar);

            const openPopupBtn = document.querySelector("#open-popup");
            if (openPopupBtn) {
                let popup = createPopup("#popup");
                openPopupBtn.addEventListener("click", popup);
            }


            if (typeof Swiper !== 'undefined') {
                new Swiper('.swiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    freeMode: true,
                });
            }
        });
    </script>
</body>

</html>