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

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="180x180" href="../vendor/img/ico/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../vendor/img/ico/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../vendor/img/ico/favicon-16x16.png" />

    <title>Admin - Agents de collecte</title>

    <?php include __DIR__ . "/includes/header.php"; ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Agents de collecte</h1>
    </div>

    <?php include "card.php"; ?>

    <div class="container-fluid">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="#" data-toggle="modal" data-target="#agence" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Ajouter un Agent</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom Agent</th>
                                <th>Mot de passe Agence</th>
                                <th>Email_Agent</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                        require_once __DIR__ . "/../config/db.php";

                        if (isset($_GET['id_agent'])) {

                            $id = $_GET['id_agent'];

                            $delete_query = "DELETE FROM agent_collecte WHERE id_agent = ?";
                            $stmt = $conn->prepare($delete_query);
                            $stmt->bind_param("i", $id);
                            if ($stmt->execute()) {
                                echo "<script>alert('Agent supprimé avec succès !');
                                 window.location.href = 'Agents.php';</script>";
                            } else {
                                echo "<script>alert('Erreur lors de la suppression !');
                                 window.location.href = 'Agents.php';</script>";
                            }
                            $stmt->close();
                        }

                        $sql2 = "SELECT * FROM agent_collecte";
                        $result2 = $conn->query($sql2);

                        if ($result2->num_rows > 0) {
                            while ($row = $result2->fetch_assoc()) {
                                echo "<tbody>";
                                echo "<tr>";
                                echo "<td>" . $row['id_agent'] . "</td>";
                                echo "<td>" . $row['nom'] . "</td>";
                                echo "<td>••••••••</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['statut'] . "</td>";
                                echo "<td>
                                        <a href='mod_agent.php?id_agent=" . $row['id_agent'] . "'><i class='fas fa-edit'></i></a> |
                                        <a href='?id_agent=" . $row['id_agent'] . "'><i class='fas fa-trash'></i></a> 
                                    </td>";
                                echo "</tr>";
                                echo "</tbody>";
                            }
                        }

                        $conn->close();
                        ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>

    </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="agence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un agent</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="user" action="#">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nom" placeholder="Nom de l'agent">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" name="passw" placeholder="mot de passe de l'agent">
                        </div>
                        <div class="form-group">
                            <input type="email" min="1" class="form-control form-control-user" name="email" placeholder="email de l'agent">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Ajouter" href="#" name="Agentadd">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/dataTables/jquery.dataTables.min.js"></script>
    <script src="vendor/dataTables/dataTables.bootstrap4.min.js"></script>

    <script src="js/demo/dataTables-demo.js"></script>

    <?php

    if (isset($_POST['Agentadd'])) {
        require_once __DIR__ . "/../config/db.php";

        $nom = trim($_POST['nom']);
        $mot_de_passe = password_hash(trim($_POST['passw']), PASSWORD_DEFAULT);
        $email = trim($_POST['email']);
        $statut = 'libre';

        // Vérifier si l'agent existe déjà (même nom ou même email)
        $check_query = "SELECT COUNT(*) FROM agent_collecte WHERE nom = ? OR email = ?";
        $stmt_check = $conn->prepare($check_query);
        $stmt_check->bind_param("ss", $nom, $email);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            echo "<script>alert('⚠️ Un agent avec ce nom ou cet email existe déjà !');
              window.location.href = 'Agents.php';</script>";
        } else {
            // Insérer un nouvel agent si aucun doublon n'est trouvé
            $insert_query = "INSERT INTO agent_collecte (nom, mot_de_passe, email, statut) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssss", $nom, $mot_de_passe, $email, $statut);

            if ($stmt->execute()) {
                echo "<script>alert('✅ Agent ajouté avec succès !');
                  window.location.href = 'Agents.php';</script>";
            } else {
                echo "<script>alert('❌ Erreur lors de l\'ajout de l\'agent.');
                  window.location.href = 'Agents.php';</script>";
            }
            $stmt->close();
        }

        $conn->close();
    }

    ?>

    </body>

</html>
