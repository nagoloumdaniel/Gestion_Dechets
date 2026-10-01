<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['conne'])) {
  $user_name = trim(htmlspecialchars($_POST['user']));
  $mot_de_passe = trim($_POST['pass']);

  if (!empty($user_name) && !empty($mot_de_passe)) {
    $query = "SELECT * FROM administrateur WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $admin = $result->fetch_assoc();

      if (password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        $_SESSION['admin_name'] = $admin['user_name'];
        header("Location: admin.php");
        exit();
      } else {
        $error = "Mot de passe incorrect.";
      }
    } else {
      $error = "Nom d'utilisateur introuvable";
    }
  } else {
    $error = "Veuillez remplir tous les champs";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <title>Se connecter sur  Eco Pro (Admin)</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/fontawesome.css" />
  <link rel="stylesheet" href="assets/css/templatemo-scholar.css" />
  <link rel="stylesheet" href="assets/css/owl.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/css/swiper.min.css" />
  <link rel="apple-touch-icon" sizes="180x180" href="../vendor/img/ico/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="../vendor/img/ico/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="../vendor/img/ico/favicon-16x16.png" />
</head>

</head>

<style>
  .password-container {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
  }

  #pass {
    flex: 1;
    padding: 12px;
    font-size: 16px;
    border: 2px solid gray;
    border-radius: 25px;
    outline: none;
  }

  .password-btn {
    position: absolute;
    right: 0px;
    bottom: 15px;
    background-color: #57c08f;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
  }

  .password-btn:hover {
    background-color: #218838;
  }

  .error-modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 10px gray;
    text-align: center;
    border-radius: 30px 30px 30px 30px;
  }
</style>

<body>

  <div class="contact-us" style="display: flex; height: 100vh; text-align: center; justify-content: center; align-items: center;">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 align-self-center">
          <div class="section-heading">
            <h6>Connectez Nous</h6>
            <h2>Connectez-vous à n'importe quel moment !</h2>
            <div class="special-offer">
              <span class="offer"><img style="height: auto; width: 80%" src="./vendor/img/logo.png" alt="" /></span>
              <h4><em>Se connecter</em> en tant que</h4>
              <h6><em>Administrateur</em> !</h6>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="contact-us-content">
            <form id="contact-form" action="" method="post">
              <div class="row">
                <div class="col-lg-12">
                  <fieldset>
                    <input type="text" name="user" id="user" placeholder="Votre Nom d'Utilisateur..." autocomplete="on" required />
                  </fieldset>
                </div>
                <div class="col-lg-12">
                  <fieldset class="password-container">
                    <input type="password" name="pass" id="pass" placeholder="Votre mot de passe..." required />
                    <button type="button" class="password-btn toggle-password" onclick="togglePassword()">🙈</button>
                  </fieldset>
                </div>
                <div class="col-lg-12">
                  <fieldset>
                    <button type="submit" id="form-submit" name="conne" class="orange-button">
                      Se connecter
                    </button>
                  </fieldset>
                </div>
              </div>
            </form>

            <?php if (isset($error)): ?>
              <div id="errorModal" class="error-modal">
                <p><?php echo "🚨🚧 $error ! 🚧🚨"; ?></p><br>
                <button style="border-radius: 30px 30px 30px 30px; padding:10px;" onclick="document.getElementById('errorModal').style.display='none'">Fermer</button>
              </div>
              <script>
                document.getElementById('errorModal').style.display = 'block';
              </script>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("pass");
      const toggleIcon = document.querySelector(".toggle-password");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.textContent = "👁️";
      } else {
        passwordInput.type = "password";
        toggleIcon.textContent = "🙈";
      }
    }
  </script>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/custom.js"></script>

</body>

</html>
