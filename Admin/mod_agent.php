<?php
$inactive_duration = 3600;

ini_set('session.gc_maxlifetime', $inactive_duration);

session_set_cookie_params($inactive_duration);

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactive_duration)) {
    session_unset();
    session_destroy();
    header("location:index.php");
}

$_SESSION['LAST_ACTIVITY'] = time();

require_once __DIR__ . "/../config/db.php";

$agent = null;

if (isset($_GET['id_agent'])) {
    $idagent = filter_input(INPUT_GET, 'id_agent', FILTER_VALIDATE_INT);

    if ($idagent) {
        $query = "SELECT * FROM agent_collecte WHERE id_agent = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idagent);
        $stmt->execute();
        $result = $stmt->get_result();
        $agent = $result->fetch_assoc();
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modage'])) {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $id = filter_input(INPUT_POST, 'id_agent', FILTER_VALIDATE_INT);
    $mot_de_passe = trim($_POST['passw']);
    if (!empty($mot_de_passe)) {
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    }

    if ($nom && $email && $id) {
        // Hachage du mot de passe uniquement si un nouveau est fourni
        if (!empty($mot_de_passe)) {
            $MOD_query = "UPDATE agent_collecte SET nom = ?, mot_de_passe = ?, email = ? WHERE id_agent = ?";
        } else {
            $MOD_query = "UPDATE agent_collecte SET nom = ?, email = ? WHERE id_agent = ?";
        }

        $stmt = $conn->prepare($MOD_query);

        if (!empty($mot_de_passe)) {
            $stmt->bind_param("sssi", $nom, $mot_de_passe, $email, $id);
        } else {
            $stmt->bind_param("ssi", $nom, $email, $id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Agent modifié avec succès !');
            window.location.href = 'Agents.php';
            </script>";
        } else {
            echo "<script>alert('Erreur lors de la modification !');
            window.location.href = 'Agents.php';</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - Modifier Agent de collecte</title>

    <link rel="apple-touch-icon" sizes="180x180" href="../vendor/img/ico/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../vendor/img/ico/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../vendor/img/ico/favicon-16x16.png" />

    <?php include __DIR__ . "/includes/header.php"; ?>
</head>

<body>
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Modifier Agent de collecte</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                Modifier un agent de collecte
            </div>
            <form action="" method="POST" class="user">
                <input type="hidden" name="id_agent" id="id_agent" value="<?= htmlspecialchars($agent['id_agent'] ?? '') ?>">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" name="nom" id="nom" value="<?= htmlspecialchars($agent['nom'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="passw">Nouveau mot de passe</label>
                    <input type="password" class="form-control" name="passw" id="passw" placeholder="Entrer un nouveau mot de passe">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($agent['email'] ?? '') ?>" required>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <button class="btn btn-secondary" type="reset">Annuler</button>
                    <button class="btn btn-success" type="submit" name="modage">Modifier</button>
                </div>
            </form>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/dataTables/jquery.dataTables.min.js"></script>
    <script src="vendor/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="js/demo/dataTables-demo.js"></script>

</body>

</html>
