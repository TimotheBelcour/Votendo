<?php
include 'includes/header.php';

// Rediriger si non connecté
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int) ($_SESSION['user_id']);
$errors = [];
$success = false;

// Récupérer les données actuelles de l'utilisateur
$stmt = $conn->prepare("SELECT nomUtilisateur, email, passwordHash FROM utilisateur WHERE idutilisateur = ?");
$stmt->execute([$userId]);
$currentUser = $stmt->fetch();

// Valeurs initiales
$nomUtilisateur = $currentUser['nomUtilisateur'] ?? '';
$email = $currentUser['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des champs
    $nomUtilisateurPost = trim($_POST['nomUtilisateur'] ?? '');
    $emailPost = trim($_POST['email'] ?? '');
    $newPassword = $_POST['newPassword'] ?? '';
    $newPasswordConfirm = $_POST['newPasswordConfirm'] ?? '';
    $currentPassword = $_POST['currentPassword'] ?? '';

    // Validation du nom d'utilisateur
    if ($nomUtilisateurPost === '') {
        $errors['nomUtilisateur'] = "Le nom d'utilisateur est requis.";
    } elseif (strlen($nomUtilisateurPost) < 3) {
        $errors['nomUtilisateur'] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    } elseif (strlen($nomUtilisateurPost) > 20) {
        $errors['nomUtilisateur'] = "Le nom d'utilisateur ne doit pas dépasser 20 caractères.";
    } else {
        // Vérifier unicité si modifié
        if ($nomUtilisateurPost !== $nomUtilisateur) {
            $stmt = $conn->prepare("SELECT idutilisateur FROM utilisateur WHERE nomUtilisateur = ? AND idutilisateur != ?");
            $stmt->execute([$nomUtilisateurPost, $userId]);
            $r = $stmt->fetch();
            if ($r) {
                $errors['nomUtilisateur'] = "Ce nom d'utilisateur est déjà utilisé.";
            }
        }
    }

    // Validation de l'email
    if ($emailPost === '') {
        $errors['email'] = "L'adresse e-mail est requise.";
    } elseif (!filter_var($emailPost, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'adresse e-mail n'est pas valide.";
    } elseif (strlen($emailPost) > 45) {
        $errors['email'] = "L'adresse e-mail ne doit pas dépasser 45 caractères.";
    } else {
        // Vérifier unicité si modifié
        if ($emailPost !== $email) {
            $stmt = $conn->prepare("SELECT idutilisateur FROM utilisateur WHERE email = ? AND idutilisateur != ?");
            $stmt->execute([$emailPost, $userId]);
            $r = $stmt->fetch();
            if ($r) {
                $errors['email'] = "Cette adresse e-mail est déjà utilisée.";
            }
        }
    }

    // Validation du nouveau mot de passe (s'il est fourni)
    $passwordWillChange = false;
    if ($newPassword !== '' || $newPasswordConfirm !== '') {
        $passwordWillChange = true;
        if (strlen($newPassword) < 6) {
            $errors['newPassword'] = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif (strlen($newPassword) > 20) {
            $errors['newPassword'] = "Le mot de passe ne doit pas dépasser 20 caractères.";
        } elseif ($newPassword !== $newPasswordConfirm) {
            $errors['newPassword'] = "Les mots de passe ne correspondent pas.";
        }
    }

    // Si l'email ou le mot de passe change, exiger la validation par le mot de passe actuel
    $sensitiveChange = ($emailPost !== $email) || $passwordWillChange;
    if ($sensitiveChange) {
        if (empty($currentPassword)) {
            $errors['currentPassword'] = "Veuillez entrer votre mot de passe actuel pour valider cette modification.";
        } else {
            // Vérifier le mot de passe actuel
            $storedHash = $currentUser['passwordHash'] ?? '';
            if (!password_verify($currentPassword, $storedHash)) {
                $errors['currentPassword'] = "Mot de passe actuel incorrect.";
            }
        }
    }

    // Si pas d'erreurs, appliquer les modifications
    if (empty($errors)) {
        $updates = 0;

        if ($nomUtilisateurPost !== $nomUtilisateur) {
            $stmt = $conn->prepare("UPDATE utilisateur SET nomUtilisateur = ? WHERE idutilisateur = ?");
            if ($stmt->execute([$nomUtilisateurPost, $userId])) {
                $updates++;
                $_SESSION['user_name'] = $nomUtilisateurPost; // Mettre à jour la session
                $nomUtilisateur = $nomUtilisateurPost;
            }
        }

        if ($emailPost !== $email) {
            $stmt = $conn->prepare("UPDATE utilisateur SET email = ? WHERE idutilisateur = ?");
            if ($stmt->execute([$emailPost, $userId])) {
                $updates++;
                $email = $emailPost;
            }
        }

        if ($passwordWillChange) {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE utilisateur SET passwordHash = ? WHERE idutilisateur = ?");
            if ($stmt->execute([$passwordHash, $userId])) {
                $updates++;
            }
        }

        if ($updates > 0) {
            $success = true;
        }
    }
}

?>

<main class="page page--espace-membre">
  <section class="section">
    <div class="container">
      <h1>Mon profil</h1>
      <h2>Modifier les informations utilisateur</h2>

      <?php if ($success): ?>
        <div class="alert alert--success">✓ Vos informations ont été mises à jour.</div>
      <?php endif; ?>

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert--error"><?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="post" class="settings-form" novalidate>
        <div class="form__field <?= isset($errors['nomUtilisateur']) ? 'form__field--error' : '' ?>">
          <label for="nomUtilisateur">Nom d'utilisateur</label>
          <input type="text" id="nomUtilisateur" name="nomUtilisateur" value="<?= htmlspecialchars($nomUtilisateur, ENT_QUOTES, 'UTF-8') ?>" maxlength="20" required>
          <?php if (isset($errors['nomUtilisateur'])): ?>
            <p class="form__error"><?= htmlspecialchars($errors['nomUtilisateur'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>

        <hr>

        <div class="form__field <?= isset($errors['email']) ? 'form__field--error' : '' ?>">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" maxlength="45" required>
          <?php if (isset($errors['email'])): ?>
            <p class="form__error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>

        <hr>

        <div class="form__field <?= isset($errors['newPassword']) ? 'form__field--error' : '' ?>">
          <label for="newPassword">Nouveau mot de passe (laisser vide pour conserver l'actuel)</label>
          <input type="password" id="newPassword" name="newPassword" maxlength="20" placeholder="••••••••">
          <?php if (isset($errors['newPassword'])): ?>
            <p class="form__error"><?= htmlspecialchars($errors['newPassword'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>

        <div class="form__field">
          <label for="newPasswordConfirm">Confirmer le nouveau mot de passe</label>
          <input type="password" id="newPasswordConfirm" name="newPasswordConfirm" maxlength="20" placeholder="••••••••">
        </div>

        <hr>

        <div class="form__field <?= isset($errors['currentPassword']) ? 'form__field--error' : '' ?>">
          <label for="currentPassword">Mot de passe actuel (nécessaire pour modifier l'email ou le mot de passe)</label>
          <input type="password" id="currentPassword" name="currentPassword" placeholder="••••••••">
          <?php if (isset($errors['currentPassword'])): ?>
            <p class="form__error"><?= htmlspecialchars($errors['currentPassword'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>

        <div class="form__actions">
          <button type="submit" class="btn btn--primary">Enregistrer les modifications</button>
        </div>
      </form>

      <hr>

      <details class="reglement-details">
        <summary class="reglement-summary"><span class="reglement-title">Informations réglementaires et conditions de participation</span></summary>
        <div class="reglement-content">
          <?php 
            // Extraire le contenu de mentionsLegales.php en excluant header et footer
            ob_start();
            include 'mentionsLegales.php';
            $content = ob_get_clean();
            // Extraire juste le contenu entre <div class="legal-content"> et </div>
            preg_match('/<div class="legal-content">(.*?)<\/div>/s', $content, $matches);
            if (isset($matches[1])) {
              echo $matches[1];
            }
          ?>
        </div>
      </details>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
