<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
        <link rel="shortcut icon" href="https://www.comapanuevolaredo.gob.mx/sitio/wp-content/themes/comapa-nuevo-laredo/images/favicon.ico" type="image/x-icon">
        <style>
            :root {
                --color-comapa: <?= config('aplicacion.colores.comapa') ?>;
            }
            .bg-comapa {
                background-color: var(--color-comapa);
            }
        </style>
    </head>
    <body class='bg-comapa'>
        <div class="container">
            <div class="row justify-content-center vh-100">
                <div class="col-md col-md-10 col-lg-6">
                    <div class="text-center my-4">
                        <img class="img-fluid" width="256" src="{{ asset('imagenes/logoComapaBlancoPequeno.png') }}" alt="COMAPA NUEVO LAREDO">
                    </div>

                    @if( is_object($cfdi) )
                    @if( $cfdi->existe() )
                    <div class="alert alert-success text-center">
                        <div>Descargando documento automáticamente ó</div>
                        <a href="{{ $cfdi->url() }}" id='descargaDocumentoCFDI' target='_blank' download>haz clic aquí para descargar manualmente</a>
                    </div>
                    <script>const descargaDocumentoCFDI={element:document.getElementById("descargaDocumentoCFDI"),listen:function(){this.element.click()}};descargaDocumentoCFDI.listen();</script>

                    @else
                    <div class="alert alert-danger text-center">Documento CFDI no disponible, intenta más tarde.</div>

                    @endif
                    @endif

                    <div class="card shadow">
                        <div class="card-header">
                            <b class="d-block text-uppercase fs-5">Descarga de CFDI</b>
                            <small>Facturas diversas y bonificaciones</small>
                        </div>
                        <div class="card-body">
                            <form action="{{ url()->current() }}" id="formSearchFileCFDI">
                                <div class="mb-3">
                                    <label class="form-label" for="inputNumeroRecibo">Número de recibo</label>
                                    <input class="form-control" name="numero_recibo" value="{{ request('numero_recibo') }}" type="text" id="inputNumeroRecibo" pattern="[0-9]{7}" maxlength="7" placeholder="Escribe exáctamente los 7 dígitos" autofocus required>
                                    @error('numero_recibo')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Concepto</label>
                                    <div class="form-control">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="concepto" value="diverso" id="conceptoDiverso" {{ request('concepto') == null ? 'checked' : (request('concepto') == 'diverso' ? 'checked' : '') }}>
                                            <label class="form-check-label" for="conceptoDiverso">Diverso</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="concepto" value="bonificacion" id="conceptoBonificacion" {{ request('concepto') == 'bonificacion' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="conceptoBonificacion">Bonificación</label>
                                        </div>
                                    </div>
                                    @error('concepto')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Formato</label>
                                    <div class="form-control">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="formato" id="formatoXml" value="xml" {{ request('formato') == null ? 'checked' : (request('formato') == 'xml' ? 'checked' : '') }}>
                                            <label class="form-check-label" for="formatoXml">XML</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="formato" id="formatoPdf" value="pdf" {{ request('formato') == 'pdf' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="formatoPdf">PDF</label>
                                        </div>
                                    </div>
                                    @error('formato')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button class="btn btn-success w-100" type="submit">Buscar documento CFDI</button>
                            </form>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
        <?php /*
        <script>
            const descargaDocumentoCFDI = {
                element: document.getElementById('descargaDocumentoCFDI'),
                listen: function () {
                    this.element.click()
                }
            }
            descargaDocumentoCFDI.listen()
        </script>
        */?>
    </body>
</html>
