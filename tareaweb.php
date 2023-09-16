<?php
header('Content-Type: application/json');

require_once('sistema.php');

$sistema = new Sistema();

$metodo = $_SERVER['REQUEST_METHOD'];

$respuesta = [];

switch ($metodo) {
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $respuesta = $sistema->get($id);
        break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
        
            if (isset($data['id_tarea'])) {
                $id_tarea = $data['id_tarea'];
                $descripcion = $data['descripcion'];
                $estado = $data['estado'];
        
                $rc = $sistema->edit($id_tarea, $descripcion, $estado);
        
                if ($rc > 0) {
                    $respuesta['mensaje'] = 'Tarea editada con éxito.';
                } else {
                    $respuesta['mensaje'] = 'Error al editar la tarea.';
                }
            } else {
                $descripcion = $data['descripcion'];
                $estado = $data['estado'];
        
                $rc = $sistema->new($descripcion, $estado);
        
                if ($rc > 0) {
                    $respuesta['mensaje'] = 'Tarea creada con éxito.';
                } else {
                    $respuesta['mensaje'] = 'Error al crear la tarea.';
                }
            }
            break;
        
  

    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $rc = $sistema->delete($id);
        if ($rc > 0) {
            $respuesta['mensaje'] = 'Tarea eliminada con éxito.';
        } else {
            $respuesta['mensaje'] = 'Error al eliminar la tarea.';
        }
        break;

    default:
        http_response_code(405);
        $respuesta['mensaje'] = 'Método no admitido.';
        break;
}

echo json_encode($respuesta);
?>
