<?php

namespace App\Http\Controllers;

use App\Infraestructura\Documento;
use Illuminate\Http\Request;

class DocumentoCFDI extends Controller
{
    const CFDI_ID = 'CMA930425IZ2';

    const RUTA_BASE = '../../../httpdocs/f_ele_xml/FactElec/';

    CONST URL_BASE = 'https://www.comapanuevolaredo.gob.mx/f_ele_xml/FactElec/';

    public function __invoke(Request $request)
    {
        $this->validar($request);

        return view('start')->with('cfdi', $this->obtenerCFDI($request));
    }


    /**
     * Validar si hay parametros en la URL
     */
    private function validar(Request $request)
    {
        if( $request->has('numero_recibo') || $request->has('concepto') || $request->has('formato') )
        {
            $request->validate([
                'numero_recibo' => ['bail', 'required', 'regex:/^[0-9]{7}$/'],
                'concepto' => ['bail', 'required','in:diverso,bonificacion'],
                'formato' => ['bail', 'required', 'in:xml,pdf'],
            ], [
                'numero_recibo.required' => __('Escribe el número del recibo'),
                'numero_recibo.regex' => __('Escribe un número válido del recibo'),
                'concepto.required' => __('Selecciona el concepto'), 
                'concepto.in' => __('Selecciona un concepto válido'), 
                'formato.required' => __('Selecciona el formato'), 
                'formato.in' => __('Selecciona un formato válido'), 
            ]);
        }
    }

    /**
     * Si la petición despúes de la validación, no tiene concepto retorna falso
     * 
     * Si la petición tiene concepto, retorna un objeto anónimo "Documento" con los valores generados
     * por los parametros.
     */
    private function obtenerCFDI(Request $request)
    {
        if(! $request->filled('concepto') )
            return false;

        if( $request->concepto == 'diverso' )
            return $this->diversoCFDI($request);

        return $this->bonificacionCFDI($request);
    }


    // DIVERSO 

    private function diversoCFDI(Request $request)
    {
        $archivo = $request->formato == 'pdf' 
                ? $this->diversoCFDIpdf($request->numero_recibo) 
                : $this->diversoCFDIxml($request->numero_recibo);

        $ruta = self::RUTA_BASE . $archivo;

        $url = self::URL_BASE . $archivo;

        return $this->generarDocumento($ruta, $url);
    }

    private function diversoCFDIpdf(string $numero_recibo)
    {
        $pdf = sprintf('%s_DIV-%s.pdf', self::CFDI_ID, $numero_recibo);

        return implode('/', [
            'PDF',
            'DIVERSOS',
            $pdf,
        ]);
    }

    private function diversoCFDIxml(string $numero_recibo)
    {
        $xml = sprintf('%s_DIV-%s.xml', self::CFDI_ID, $numero_recibo);

        return implode('/', [
            'XML',
            'DIVERSOS',
            $xml,
        ]);
    }


    // BONIFICACION

    private function bonificacionCFDI(Request $request)
    {
        $archivo = $request->formato == 'pdf' 
                ? $this->bonificacionCFDIpdf($request->numero_recibo) 
                : $this->bonificacionCFDIxml($request->numero_recibo);

        $ruta = self::RUTA_BASE . $archivo;

        $url = self::URL_BASE . $archivo;

        return $this->generarDocumento($ruta, $url);
    }

    private function bonificacionCFDIpdf(string $numero_recibo)
    {
        $pdf = sprintf('BON-%s.pdf', $numero_recibo);
        
        return implode('/', [
            'PDF',
            'BONIFICA',
            $pdf
        ]);
    }

    private function bonificacionCFDIxml(string $numero_recibo)
    {
        $xml = sprintf('%s-BON-%s.xml', self::CFDI_ID, str_pad($numero_recibo, 10, '0', STR_PAD_LEFT));
        
        return implode('/', [
            'XML',
            'BONIFICA',
            $xml,      
        ]);
    }


    /**
     * Retorna una clase anónima "Documento" con las funciones necesarias para funcionamiento
     * para su descarga por el usuario.
     */
    private function generarDocumento(string $ruta, string $url)
    {
        return new class($ruta, $url) {

            public $ruta_generada;

            public $url_generado;

            public function __construct(string $ruta, string $url)
            {
                $this->ruta_generada = $ruta;

                $this->url_generado = $url;
            }

            public function ruta()
            {
                return $this->ruta_generada;
            }

            public function url()
            {
                return $this->url_generado;
            }

            public function existe()
            {
                return is_file($this->ruta_generada);
            }
        };
    }
}
