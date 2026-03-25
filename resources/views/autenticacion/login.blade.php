  @extends('autenticacion.app')
  @section('titulo', 'Sistema')
  @section('contenido')
      <div class="card card-outline card-primary">
          <div class="card-header">
              <a href="../index2.html" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                  <h1 class="mb-0"><b>Tech</b>Parking</h1>
              </a>
          </div>
          <div class="card-body login-card-body">
              <p class="login-box-msg">Ingrese sus credenciales</p>
              @if (session('error'))
                  <div class="alert alert-danger">
                      {{ session('error') }}
                  </div>
              @endif
              @if (Session::has('mensaje'))
                  <div class="alert alert-info alert-dismissible fade show mt-2">
                      {{ Session::get('mensaje') }}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                  </div>
              @endif
              <form action="{{ route('login.post') }}" method="post">
                  @csrf
                  <div class="input-group mb-1">
                      <div class="form-floating">
                          <input id="loginEmail" type="email" name="email" class="form-control" value=""
                              placeholder="" />
                          <label for="loginEmail">Email</label>
                      </div>
                      <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                  </div>
                  <div class="input-group mb-1">
                      <div class="form-floating">
                          <input id="loginPassword" type="password" name="password" class="form-control" placeholder="" />
                          <label for="loginPassword">Contraseña</label>
                      </div>
                      <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                  </div>
                  <p class="mb-1"><a href="{{route('password.request')}}">Recuperar contraseña</a></p>
                  <!--begin::Row-->
                  <div class="row">
                      <!-- /.col -->
                      <div class="col-4">
                          <div class="d-grid gap-2">
                              <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                          </div>
                      </div>
                      <!-- /.col -->
                  </div>
                  <!--end::Row-->
              </form>

              <p class="mt-3 mb-0 text-center">
                  ¿No tienes cuenta? <a href="{{ route('registro') }}" class="text-primary">Regístrate aquí</a>
              </p>
          </div>
          <!-- /.login-card-body -->
      </div>
  @endsection
