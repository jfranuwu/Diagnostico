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

    public function listarTareas()
    {
        try {
            $query = "SELECT * FROM tarea";
            $stmt = $this->db->query($query);
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $tareas;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function eliminarTarea($id)
    {
        try {
            $query = "DELETE FROM tarea WHERE id_tarea = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function agregarTarea($descripcion, $estado)
    {
        try {
            $query = "INSERT INTO tarea (descripcion, estado) VALUES (:descripcion, :estado)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function editarTarea($id, $descripcion, $estado)
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

    public function get($id = null)
{
    if (is_null($id)) {
        $sql = "select * from tarea";
        $st = $this->db->prepare($sql);
        $st->execute();
        $data = $st->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $sql = "select * from tarea where id_tarea = :id";
        $st = $this->db->prepare($sql);
        $st->bindParam(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $data = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    return $data;
}
public function edit($id, $descripcion, $estado)
{
    try {
        $query = "UPDATE tarea SET descripcion = :descripcion, estado = :estado WHERE id_tarea = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount(); 
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

public function delete($id)
{
    try {
        $query = "DELETE FROM tarea WHERE id_tarea = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount(); 
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

public function new($descripcion, $estado)
{
    try {
        $query = "INSERT INTO tarea (descripcion, estado) VALUES (:descripcion, :estado)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount(); 
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}



}

$sistema = new Sistema();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'borrar') {
        $id_tarea = $_POST['id_tarea'];
        if ($sistema->eliminarTarea($id_tarea)) {
            header('Location: sistema.php');
            exit();
        }
    } elseif ($_POST['accion'] === 'crear') {
        $descripcion = $_POST['descripcion'];
        $estado = $_POST['estado'];
        if ($sistema->agregarTarea($descripcion, $estado)) {
            header('Location: sistema.php');
            exit();
        }
    } elseif ($_POST['accion'] === 'editar') {
        $id_tarea = $_POST['id_tarea'];
        $descripcion = $_POST['descripcion'];
        $estado = $_POST['estado'];
        if ($sistema->editarTarea($id_tarea, $descripcion, $estado)) {
            header('Location: sistema.php');
            exit();
        }
    }
}

$tareas = $sistema->listarTareas();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tareas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Lista de Tareas</h1>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($tareas as $tarea) : ?>
        <tr>
            <td><?= $tarea['id_tarea'] ?></td>
            <td><?= $tarea['descripcion'] ?></td>
            <td><?= $tarea['estado'] ?></td>
            <td>
                <a href="edit.php?id=<?= $tarea['id_tarea'] ?>" class="btn btn-outline-primary">Editar</a>
                <form method="POST" action="">
                    <input type="hidden" name="id_tarea" value="<?= $tarea['id_tarea'] ?>">
                    <input type="hidden" name="accion" value="borrar">
                    <button type="submit" class="btn btn-outline-danger">Borrar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a class="btn btn-outline-dark" href="form.php">Agregar tarea</a>
            
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/
