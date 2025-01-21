<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5">
    <h1>Profiel Bewerken</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Gebruikersnaam</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mailadres</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Nieuw Wachtwoord</label>
            <input type="password" class="form-control" id="new_password" name="new_password" 
                   placeholder="Laat leeg om wachtwoord niet te wijzigen">
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Bevestig Nieuw Wachtwoord</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <button type="submit" class="btn btn-primary">Opslaan</button>
        <a href="/profile" class="btn btn-secondary">Annuleren</a>
    </form>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?> 