<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0">Mijn Profiel</h1>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Gebruikersnaam:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($user['username']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>E-mailadres:</strong></div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Lid sinds:</strong></div>
                        <div class="col-sm-8"><?php echo date('d-m-Y', strtotime($user['created_at'])); ?></div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="/profile/edit" class="btn btn-primary">Profiel Bewerken</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?> 