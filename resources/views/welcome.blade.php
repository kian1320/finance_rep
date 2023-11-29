<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="{{ URL('assets/img/download.png') }}" type="image/png">
    <!-- Favicon-->
    <link rel="stylesheet" type="text/css" href="{{ URL('assets/css/styles1.css') }}">
    <!-- Core theme CSS (includes Bootstrap)-->


<body>



    <!-- Header - set the background image for the header in the line below-->
    <header class="py-5 bg-image-full" style="background-color: black">
        <div class="text-center my-5">
            <img class="img-fluid rounded-circle " src="{{ URL('assets/img/download.png') }}" alt="..." />
            <br>
            <h1 class="text-white fs-4 ">Dominican Province of the Philippines</h1>
            <h4 class="text-white-50 mb-0"></h4>
            <br>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="row mb-3">
                    <label for="email" class="col-md-5 col-form-label text-md-end">{{ __('Email Address') }}</label>
                    <div class="col-md-2">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-5 col-form-label text-md-end">{{ __('Password') }}</label>
                    <div class="col-md-2">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-1 offset-md-5">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember"
                                style="color: white;">{{ __('Remember_Me') }}</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-4 offset-md-4">
                        <button type="submit" class="btn btn-secondary">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-secondary" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </header>



    <header class="py-5 bg-image-full" style="background-color: black">
    </header>
    <header class="py-5 bg-image-full" style="background-color: black">
    </header>

    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">
                Copyright &copy; Your Website 2023
            </p>
        </div>
    </footer>


    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
