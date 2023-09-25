<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <style>
        @import url(https://fonts.googleapis.com/css?family=Nunito);

        .w-50 {
            max-width: 200px !important;
        }

        .lead {
            color: #444 !important;
            font-weight: 400 !important;
            font-size: 16px !important;
            font-family: 'Nunito', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
        }

        p {
            margin-bottom: 0 !important;
        }

        @media only screen and (max-width: 480px) {
            .display-4 {
                color: #444;
                font-size: 27px !important;
            }
        }

    </style>
</head>

<body>

    <main>
        <div class="container">
            <div class="row mt-1">
                <div class="col-md-6 mx-auto text-center ">
                    <img alt="logo" class="w-50  " src="{{asset('admin/images/cw_color.png')}}" alt="logo" style="height:60px;width:190px;">
                    <br>
                    <h3>Dear {{ $userDetails['FULLNAME'] }},</h3>
                    <p>Your new password is: <span class="text-info">{{ $newPassword }}</span></p>

                    @foreach ($userDetails as $key => $value)
                    <p>{{ $value }}</p>
                    @endforeach

                    <h3>Thank you!</h3>

                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>
