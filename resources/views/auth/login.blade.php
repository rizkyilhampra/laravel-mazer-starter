<x-layouts.auth title="Login">
  <div class="col-lg-5 col-12">
    <div id="auth-left">
      <h1 class="auth-title">Log in.</h1>
      <p class="auth-subtitle mb-5">Masuk dengan akun yang anda punya</p>
      <form
        action="{{ route('login') }}"
        method="POST"
        class="needs-validation"
      >
        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
          <input
            type="text"
            name="email"
            class="form-control form-control-xl {{ $errors->has('email') ? 'is-invalid' : '' }}"
            placeholder="Email"
          />
          <div class="form-control-icon">
            <i class="bi bi-person"></i>
          </div>

          <x-invalid-feedback>
            {{ $errors->first('email') }}
          </x-invalid-feedback>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
          <input
            type="password"
            name="password"
            class="form-control form-control-xl {{ $errors->has('password') ? 'is-invalid' : '' }}"
            placeholder="Password"
          />
          <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
          </div>
          <x-invalid-feedback>
            {{ $errors->first('password') }}
          </x-invalid-feedback>
        </div>
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
          Log in
        </button>
      </form>
    </div>
  </div>
</x-layouts.auth>
