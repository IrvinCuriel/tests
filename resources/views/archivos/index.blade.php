<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body>

        <div>
            <form method="post" action="{{ route('archivo.store') }}" role="form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div>
                        <div class="col-md-12">

                            <div class="mb-3">
                                <label for="formFile" class="form-label">Archivo</label>
                                <input class="form-control" type="file" id="file" name="file" required/>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary font-medium waves-effect px-4" id="submit">
                        <div class="d-flex align-items-center">
                            <i data-feather="send" class="feather-sm fill-white me-2"></i>
                            Enviar
                        </div>
                    </button>
                </div>
            
            </form>

            <div>
                <a class="btn btn-primary font-medium waves-effect px-4" href="{{route('archivo.download')}}" class="button">Descargar</a>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js" integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @if (session()->has('success'))
            <script>
            $(document).ready(function () {
                message = {!!json_encode(session()->get('success'))!!}
                console.log(message);
            });
            </script>
        @endif

    </body>
</html>
     
