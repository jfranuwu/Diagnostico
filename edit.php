<?php
require_once('config.php');

class Sistema
{
    private $db = null;

    public function __construct()
    {
        $dsn = DBDRIVER . ':host=' . DBHOST . ';dbname=' . DBNAME . ';port=' . DBPORT;
        $this->db = new PDO($dsn, DBUSER, DBPASS);
    }

    public function obtenerTarea($id)
    {
        try {
            $query = "SELECT * FROM tarea WHERE id_tarea = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function actualizarTarea($id, $descripcion, $estado)
    {
        try {
            $query = "UPDATE tarea SET descripcion = :descripcion, estado = :estado WHERE id_tarea = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

$sistema = new Sistema();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarea = $_POST['id_tarea'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    if ($sistema->actualizarTarea($id_tarea, $descripcion, $estado)) {
        header('Location: sistema.php');
        exit();
    }
}

if (isset($_GET['id'])) {
    $id_tarea = $_GET['id'];
    $tarea = $sistema->obtenerTarea($id_tarea);

    if (!$tarea) {
        die("Tarea no encontrada.");
    }
} else {
    die("ID de tarea no proporcionado.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Editar Tarea</h1>
    <form method="POST" action="">
        <input type="hidden" name="id_tarea" value="<?= $tarea['id_tarea'] ?>">
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= $tarea['descripcion'] ?>" required>
        </div>
        <div class="form-group">
            <label for="estado">Estado:</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="pendiente" <?= ($tarea['estado'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                <option value="en progreso" <?= ($tarea['estado'] === 'en progreso') ? 'selected' : '' ?>>En Progreso</option>
                <option value="completada" <?= ($tarea['estado'] === 'completada') ? 'selected' : '' ?>>Completada</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
