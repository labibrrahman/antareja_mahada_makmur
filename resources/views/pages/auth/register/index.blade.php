<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">



    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/admin/css/signin.css') }}" rel="stylesheet">
</head>

<body class="text-center">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <main class="form-signin">
                <form>
                    <img class="mb-4" src="{{ asset('images/logo.png') }}" alt="" width="100"
                        height="100" height="57">
                    <h1 class="h3 mb-3 fw-normal">Please Login</h1>

                    <div class="form-floating">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>


                    <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
                    <p class="mt-5 mb-3 text-muted">&copy; Afwaja Center {{ date('Y') }}</p>
                </form>
                <smal class="d-block text-center">Not Register <a href="/register"> Register Now!</a></small>
            </main>
        </div>
    </div>

</body>

</html>
