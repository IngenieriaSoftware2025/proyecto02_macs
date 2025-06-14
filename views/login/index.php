<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <form id="FormLogin">
              <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                <p class="text-white-50 mb-5">Por favor ingrese su DPI y contraseña!</p>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="text" name="usuario_dpi" id="usuario_dpi" class="form-control form-control-lg" placeholder="Ingrese su DPI" />
                  <label class="form-label" for="usuario_dpi">DPI</label>
                </div>

                <div data-mdb-input-init class="form-outline form-white mb-4">
                  <input type="password" name="usuario_password" id="usuario_password" class="form-control form-control-lg" placeholder="Ingrese su contraseña" />
                  <label class="form-label" for="usuario_password">Contraseña</label>
                </div>

                <button class="btn btn-outline-light btn-lg px-5" type="submit" id="BtnIniciar">Iniciar Sesión</button>

              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= asset('build/js/login/login.js') ?>"></script>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background: #6a11cb;
        background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
        background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
    }
    body {
        min-height: 100vh;
        height: 100%;
    }
    .gradient-custom {
        min-height: 100vh;
        height: 100%;
    }
</style>