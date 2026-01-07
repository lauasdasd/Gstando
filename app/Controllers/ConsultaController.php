<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Consulta.php';
// Importa la clase de Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class ConsultaController {
    
    private $clienteModel;
    private $consultaModel;

    public function __construct() {
        $db_connection = $this->getDbConnection();
        
        // Asumiendo que tu constructor del modelo recibe una conexión a la base de datos
        $this->clienteModel = new Cliente();
        $this->consultaModel = new ConsultaModel($db_connection);
    }

    public function index() {
        require_once __DIR__ . '/../views/consultas/index.php';
    }

    // Método principal que procesa la consulta AJAX
    public function procesar() {
        header('Content-Type: application/json');
        
        $identificacion = $_POST['identificacion'] ?? '';

        if (empty($identificacion)) {
            echo json_encode(['error' => 'Identificación no proporcionada.']);
            return;
        }

        // Paso 1: Busca al cliente en tu base de datos (vital para luego guardar)
        $cliente = $this->clienteModel->buscarPorCuilDni($identificacion);
        
        $resultados = [
            'cliente_id' => $cliente ? $cliente['idCliente'] : null,
            'cliente_nombre' => $cliente ? ($cliente['nombre'] . ' ' . $cliente['apellido']) : 'Cliente no encontrado',
            'bcra' => [],
            'docuest' => [],
            'ecom' => []
        ];

        // Paso 2: Llamada ÚNICA a la API del BCRA
        $resultados['bcra'] = $this->callBcraApi($identificacion);

        // Devolvemos todos los resultados (aunque solo uno tendrá datos por ahora)
        echo json_encode($resultados);
        exit;
    }

    // Métodos para las llamadas a las APIs
private function callBcraApi($identificacion) {
    // URL correcta de la API del BCRA para la Central de Deudores
    $url = "https://api.bcra.gob.ar/centraldedeudores/v1.0/Deudas/" . $identificacion;
    
    // Inicializa cURL
    $ch = curl_init();
    
    // Configura la llamada
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    // Solución para el problema de certificado SSL en entornos de desarrollo
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Ejecuta la llamada
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Maneja la respuesta
    if ($http_code == 200 && $response) {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        } else {
            return ['error' => 'Respuesta JSON inválida de la API.'];
        }
    } else {
        return ['error' => 'Error de conexión a la API BCRA: ' . $error];
    }
}
    
    // Dejamos los otros métodos vacíos o comentados por ahora
    private function callDocuestApi($identificacion) { return []; }
    private function callEcomApi($identificacion) { return []; }

    private function getDbConnection() {
        // Debes adaptar esto a tu forma de obtener la conexión a la base de datos
        return null;
    }
    /**
     * Genera un reporte PDF de la situación crediticia.
     * @param string $cuil El CUIL a consultar.
     */
    public function generarReporte() {
            // Lee el CUIL desde el parámetro de la URL
        // Lee el CUIL directamente desde el parámetro de la URL
        $cuil = $_GET['cuil'] ?? null;

        if (empty($cuil)) {
            // Maneja el error si el CUIL no se proporciona
            die('Error: CUIL no proporcionado.');
        }

        // Paso 1: Obtener los datos de la API del BCRA
        $bcra_response = $this->callBcraApi($cuil);

        // Paso 2: Verificar que la respuesta es válida antes de generar el reporte
        if ($bcra_response && isset($bcra_response['status']) && $bcra_response['status'] === 200) {
            $bcra_data = $bcra_response['results'];

            // Paso 3: Crear la plantilla HTML del reporte
            // Este contenido se puede mover a un archivo de vista por limpieza.
            $html = $this->createReporteHtml($bcra_data);

            // Paso 4: Configurar y usar Dompdf
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($options);
            
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Paso 5: Enviar el PDF al navegador para que el usuario lo vea/imprima
            $dompdf->stream("Reporte_BCRA_" . $cuil . ".pdf", ["Attachment" => false]);
            exit;

        } else {
            // Manejar el error si la API no devuelve datos válidos
            die('Error: No se pudo obtener la información de la API BCRA para generar el reporte.');
        }
    }

    /**
     * Crea el contenido HTML del reporte.
     * @param array $data Los datos de la API del BCRA.
     * @return string El HTML del reporte.
     */
/**
 * Crea el contenido HTML del reporte con el formato del BCRA.
 * @param array $data Los datos de la API del BCRA.
 * @return string El HTML del reporte.
 */
private function createReporteHtml($data) {
    // La URL debe ser una ruta local al archivo
    $logoPath = __DIR__ . '/../../public/assets/img/logo_bcra.png';
    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Reporte de Situación Crediticia</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
            .header {
                display: flex; /* Habilita Flexbox */
                align-items: center; /* Centra los elementos verticalmente */
                justify-content: space-between; /* Espacia el logo y el texto */
                margin-bottom: 20px;
            }
            .header img {
                width: 300px; /* Tamaño del logo un poco más grande */
            }
            .header-text {
                flex-grow: 1; /* Permite que el div de texto ocupe el espacio restante */
                text-align: center; /* Centra solo el texto */
            }
            h2, h3 { margin: 5px 0; text-transform: uppercase; }
            .info-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            .note { margin-top: 20px; font-style: italic; font-size: 10px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="' . $logoBase64 . '" alt="Logo BCRA"> 
            <div class="header-text">
                <h2>Central de Deudores del Sistema Financiero</h2>
            </div>
        </div>
        <div class="info-box">
            <p><strong>Consulta de información para el CUIT-CUIL-CDI ' . htmlspecialchars($data['identificacion']) . ' - ' . htmlspecialchars($data['denominacion']) . '</strong></p>
        </div>
        <p class="note">En el siguiente cuadro, el monto de deuda se encuentra expresado en miles de pesos.</p>
        <table>
            <thead>
                <tr>
                    <th>Denominación del deudor</th>
                    <th>Entidad</th>
                    <th>Período</th>
                    <th>Situación</th>
                    <th>Montos</th>
                    <th>Días de atraso</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>';

    // Recorre los datos y crea las filas de la tabla
    foreach ($data['periodos'] as $periodo) {
        foreach ($periodo['entidades'] as $entidad) {
            $situacion = '';
            switch ($entidad['situacion']) {
                case 1: $situacion = '1 - Normal'; break;
                case 2: $situacion = '2 - Riesgo bajo'; break;
                case 3: $situacion = '3 - Riesgo medio'; break;
                case 4: $situacion = '4 - Riesgo alto'; break;
                case 5: $situacion = '5 - Riesgo muy alto'; break;
                case 6: $situacion = '6 - Riesgo inminente'; break;
                case 7: $situacion = '7 - Irrecuperable'; break;
                case 8: $situacion = '8 - Irrecuperable por disposición técnica'; break;
            }

            // Construye la cadena de observaciones
            $observaciones = [];
            if ($entidad['refinanciaciones']) $observaciones[] = 'Refinanciación';
            if ($entidad['recategorizacionOblig']) $observaciones[] = 'Recategorización obligatoria';
            if ($entidad['situacionJuridica']) $observaciones[] = 'Situación jurídica';
            if ($entidad['irrecDisposicionTecnica']) $observaciones[] = 'Irrecuperable por disp. técnica';
            if ($entidad['enRevision']) $observaciones[] = 'En revisión';
            if ($entidad['procesoJud']) $observaciones[] = 'Proceso judicial';

            $observacionesTexto = implode(', ', $observaciones);
            if (empty($observacionesTexto)) {
                $observacionesTexto = 'N/A';
            }
            
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($data['denominacion']) . '</td>
                    <td>' . htmlspecialchars($entidad['entidad']) . '</td>
                    <td>' . htmlspecialchars($periodo['periodo']) . '</td>
                    <td>' . htmlspecialchars($situacion) . '</td>
                    <td>' . htmlspecialchars($entidad['monto']) . '</td>
                    <td>' . htmlspecialchars($entidad['diasAtrasoPago']) . '</td>
                    <td>' . htmlspecialchars($observacionesTexto) . '</td>
                </tr>';
        }
    }
    
    $html .= '
            </tbody>
        </table>';
    
    // VALIDACIÓN DE LA SITUACIÓN
    if (!empty($data['periodos']) && $data['periodos'][0]['entidades'][0]['situacion'] === 1) {
        $html .= '
        <p class="note">Este deudor permanece en Situación 1 - Normal o no ha sido incluido en la Central de Deudores en períodos intermedios, desde:
        10/2016 (Sección 4. Apartado 2. del Texto ordenado de Centrales de Información (../Pdfs/Texord/t-ceninf.pdf) – llamadas * y **)</p>';
    }

    $html .= '
    </body>
    </html>';

    return $html;
}
}