<section class="vh-100" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 50%, #2d3436 100%);">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-6 col-lg-5 col-xl-4">
        <div class="card" style="border-radius: 20px; border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 25px 45px rgba(0,0,0,0.1);">
          
          <div class="card-header text-center text-white" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border-radius: 20px 20px 0 0; padding: 40px 20px 30px;">
            <div class="mb-3">
              <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-phone-fill" style="font-size: 2rem;"></i>
              </div>
            </div>
            <h2 class="fw-bold mb-2">Bienvenido</h2>
            <p class="mb-0" style="opacity: 0.9;">Sistema de Gestión de Celulares</p>
          </div>

          <div class="card-body p-4" style="background: #f8f9fa;">
            <form id="FormLogin">
              
              <div class="mb-4">
                <div class="form-floating">
                  <input type="text" name="usu_codigo" id="usu_codigo" class="form-control" placeholder="Ingrese su DPI" style="border-radius: 12px; height: 55px; border: 2px solid #e1e5e9;">
                  <label for="usu_codigo">Ingrese su DPI</label>
                </div>
              </div>

              <div class="mb-4">
                <div class="form-floating">
                  <input type="password" name="usu_password" id="usu_password" class="form-control" placeholder="Ingrese su contraseña" style="border-radius: 12px; height: 55px; border: 2px solid #e1e5e9;">
                  <label for="usu_password">Ingrese su contraseña</label>
                </div>
              </div>

              <div class="d-grid mb-3">
                <button type="submit" id="BtnIniciar" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border: none; border-radius: 12px; padding: 15px; font-weight: 600;">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
              </div>

              <div class="text-center">
                <p class="mb-0">¿No tienes cuenta? <a href="" style="color: #0984e3; font-weight: 600; text-decoration: none;">Registrarse aquí</a></p>
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
    }
    
    .form-control:focus {
        border-color: #74b9ff;
        box-shadow: 0 0 0 0.2rem rgba(116, 185, 255, 0.25);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0984e3 0%, #0057b3 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(116, 185, 255, 0.4);
    }
    
    .card {
        backdrop-filter: blur(10px);
    }
</style>