<!-- Footer Simplificado -->
<footer class="bg-dark text-white mt-5" style="background: #343a40 !important; padding: 30px 0 20px 0 !important; display: block !important; visibility: visible !important;">
    <div class="container py-4" style="background: #343a40 !important;">
        <div class="row" style="background: #343a40 !important; display: flex; align-items: stretch;">
            <!--Maps-->
            <div class="col-md-3 mb-3" style="background: #343a40 !important; padding: 15px !important; display: flex; flex-direction: column;">
                <h6 style="color: #FFD700 !important;">
                    <img src="/src/Public/images/logo_solo_circulo.png" alt="Logo IFTS15" style="height: 28px;vertical-align:middle;margin-right:8px;"> IFTS15
                </h6>
                <ul class="list-unstyled" style="flex-grow: 1;">
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-geo-alt" style="color: #FFD700;"></i>
                        Loyola 1500, B1806 Buenos Aires
                    </li>
                    <li class="text-light text-decoration-none">
                        <span class="d-none d-md-block">
                           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.6206359454677!2d-58.44578502353153!3d-34.588464456694!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcb5f3a7cbbb97%3A0x6ab20f460a2395ad!2sLoyola%201500%2C%20C1414%20AVF%2C%20Cdad.%20Aut%C3%B3noma%20de%20Buenos%20Aires!5e0!3m2!1ses-419!2sar!4v1764794541823!5m2!1ses-419!2sar" width="300" height="125" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </span>
                    </li>
                </ul>
            </div>
            <!--Enlaces utiles-->
            <div class="col-md-3 mb-3" style="background: #343a40 !important; padding: 15px !important; display: flex; flex-direction: column;">
                <h6 style="color: #FFD700 !important;">Enlaces Útiles</h6>
                <ul class="list-unstyled" style="flex-grow: 1;">
                    <li><a href="/src/Controllers/viewController.php?view=pagina_en_construccion" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Sobre Nosotros
                        </a></li>
                    <li><a href="<?php echo BASE_URL; ?>/src/Controllers/viewController.php?view=realizador-productor-tv" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Realizador y Productor Televisivo
                        </a></li>
                    <li><a href="/src/Controllers/viewController.php?view=pagina_en_construccion" class="text-light text-decoration-none">
                            <i class="bi bi-chevron-right"></i> Biblioteca
                        </a></li>
                    <li><a href="#" class="text-light text-decoration-none" data-bs-toggle="modal" data-bs-target="#consultasModal" style="color: #FFD700 !important;">
                            <i class="bi bi-chevron-right"></i> Contacto
                        </a></li>
                </ul>
            </div>
            <!--Contacto-->
            <div class="col-md-3 mb-3" style="background: #343a40 !important; padding: 15px !important; display: flex; flex-direction: column;">
                <h6 style="color: #FFD700 !important;">Contacto</h6>
                <ul class="list-unstyled" style="flex-grow: 1;">
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-geo-alt" style="color: #FFD700;"></i>
                        Horario: 19:00 hs a 23:00 hs<br>
                    </li>
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-telephone" style="color: #FFD700;"></i>
                        (011) 4555-1234<br>
                    </li>
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-envelope" style="color: #FFD700;"></i>
                        cursos@ropirdz.com.ar
                    </li>
                      <li class="text-light text-decoration-none">
                        <i class="bi bi-envelope" style="color: #FFD700;"></i>
                        infoifts15@gmail.com
                    </li>
                </ul>
            </div>
            <!--Redes sociales-->
            <div class="col-md-3 mb-3" style="background: #343a40 !important; padding: 15px !important; display: flex; flex-direction: column;">
                <h6 style="color: #FFD700 !important;">Redes Sociales</h6>
                <ul class="list-unstyled" style="flex-grow: 1;">
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-youtube"></i>
                        <a href="https://www.youtube.com/@iftsn15" target="_blank">youtube</a><br>
                    </li>
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-facebook"></i>
                        <a href="https://facebook.com/15ifts" target="_blank">facebook</a><br>
                    </li>
                    <li class="text-light text-decoration-none">
                        <i class="bi bi-instagram"></i>
                        <a href="https://instagram.com/iftsn15" target="_blank">instagram</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Separador amarillo degradado -->
        <div class="footer-separator" style="
            width: 100%; 
            height: 2px; 
            background: linear-gradient(90deg, transparent 0%, #FFD700 20%, #FFD700 50%, #FFD700 80%, transparent 100%); 
            margin: 15px 0; 
            border-radius: 1px;
            box-shadow: 0 1px 4px rgba(255, 215, 0, 0.4);
        "></div>

        <hr class="my-3" style="border-color: #495057; background: #343a40 !important; display: none;">
        <div class="row" style="background: #343a40 !important;">
            <div class="col-md-6">
                <small style="color: white !important;">
                    © <?php echo date('Y'); ?> IFTS15. Todos los derechos reservados.
                </small>
            </div>
            <div class="col-md-6 text-md-end">
                <small style="color: white !important;">
                    Desarrollado por Les muchaches del Inap
                </small>
            </div>
        </div>
    </div>
</footer>

<!-- Modal de Consultas -->

<?php include_once __DIR__ . '/../Components/modalConsultas.php'; ?>
<?php include_once __DIR__ . '/../Components/modalLogin.php'; ?>
<?php include_once __DIR__ . '/../Components/modalRegistrar.php'; ?>

<!-- JavaScript Centralizado del Sistema -->
<?php include_once __DIR__ . '/../Public/Utilities/JS.php'; ?>