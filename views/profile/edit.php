<?php require_once __DIR__ . '/../templates/header.php'; ?>

<?php
$profilePhoto = getProfilePhotoUrl($user['profile_photo'], $user['username']);
$usernameInitial = strtoupper(substr($user['username'], 0, 1));
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Profiel',
    'title'   => 'Profiel bewerken',
    'lead'    => 'Pas je gebruikersnaam, e-mailadres, bio, profielfoto en wachtwoord aan.',
]) ?>

<section class="pp-container pp-container--narrow py-10 md:py-14">
    <div class="mb-6">
        <a href="/profile" class="inline-flex items-center gap-2 text-sm text-[color:var(--color-ink-muted)] hover:text-[color:var(--color-hague)] transition-colors">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar profiel
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="border-l-4 border-[color:var(--color-terracotta)] bg-[color:var(--color-terracotta-tint)] text-[color:var(--color-terracotta)] p-4 mb-6 rounded-r">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5"><?= pp_icon('alert-circle', 18) ?></span>
                <span class="text-sm leading-relaxed"><?= pp_e($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="border-l-4 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] text-[color:var(--color-olive)] p-4 mb-6 rounded-r">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5"><?= pp_icon('check-circle', 18) ?></span>
                <span class="text-sm leading-relaxed"><?= pp_e($success) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="keyline-card p-6 md:p-10">
        <form method="POST" class="space-y-8" id="profile-form" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">

            <div class="flex flex-col sm:flex-row items-start gap-5 pb-6 border-b border-[color:var(--color-keyline)]">
                <div class="w-20 h-20 rounded-md overflow-hidden border border-[color:var(--color-keyline)] bg-[color:var(--color-paper-2)] flex items-center justify-center text-3xl font-display text-[color:var(--color-hague)]">
                    <?php if ($profilePhoto['type'] === 'img'): ?>
                        <img src="<?= pp_e($profilePhoto['value']) ?>" alt="Profielfoto" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= $profilePhoto['value'] ?>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Profielfoto</h3>
                    <p class="text-xs text-[color:var(--color-ink-muted)] mb-3">Ondersteund: JPG, PNG, GIF, WEBP. Max 5MB.</p>
                    <div class="flex flex-wrap gap-2">
                        <label for="profile_photo_input" class="btn btn--ghost cursor-pointer">
                            <?= pp_icon('upload', 14) ?>
                            Uploaden
                        </label>
                        <input type="file" id="profile_photo_input" name="profile_photo" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp">
                        <button type="button" id="remove_photo_button"
                                class="btn btn--ghost text-[color:var(--color-terracotta)]"
                                <?= empty($user['profile_photo']) ? 'disabled' : '' ?>>
                            <?= pp_icon('trash-2', 14) ?>
                            Verwijderen
                        </button>
                        <input type="hidden" id="remove_photo" name="remove_photo" value="0">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="field">
                    <label for="username" class="field__label">Gebruikersnaam</label>
                    <input type="text" id="username" name="username" required class="input" value="<?= pp_e($user['username']) ?>">
                    <div class="field__hint">Zichtbaar voor andere gebruikers.</div>
                </div>

                <div class="field">
                    <label for="email" class="field__label">E-mailadres</label>
                    <input type="email" id="email" name="email" required class="input" value="<?= pp_e($user['email']) ?>">
                    <div class="field__hint">Wordt niet publiek gedeeld.</div>
                </div>

                <div class="field md:col-span-2">
                    <label for="bio" class="field__label">Over mij <span class="text-[color:var(--color-ink-faint)] font-normal">(optioneel)</span></label>
                    <textarea id="bio" name="bio" rows="4" class="textarea" placeholder="Een korte introductie..."><?= pp_e($user['bio'] ?? '') ?></textarea>
                    <div class="field__hint">Verschijnt op je profielpagina.</div>
                </div>
            </div>

            <div class="pt-6 border-t border-[color:var(--color-keyline)]">
                <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-2 leading-tight">Wachtwoord wijzigen</h3>
                <p class="text-sm text-[color:var(--color-ink-muted)] mb-5">Laat de velden leeg als je je wachtwoord niet wilt wijzigen.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="field">
                        <label for="new_password" class="field__label">Nieuw wachtwoord</label>
                        <input type="password" id="new_password" name="new_password" class="input" placeholder="Minimaal 8 karakters">
                    </div>
                    <div class="field">
                        <label for="confirm_password" class="field__label">Bevestig wachtwoord</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="input" placeholder="Herhaal het wachtwoord">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-6 border-t border-[color:var(--color-keyline)]">
                <a href="/profile" class="btn btn--ghost">
                    <?= pp_icon('arrow-left', 14) ?>
                    Annuleren
                </a>
                <button type="submit" class="btn btn--primary">
                    <?= pp_icon('save', 14) ?>
                    Opslaan
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profile_photo_input');
        const removePhotoButton = document.getElementById('remove_photo_button');
        const removePhotoInput = document.getElementById('remove_photo');
        const profilePhotoPreview = document.querySelector('.w-20.h-20.rounded-md');

        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function() {
                if (!this.files || !this.files[0]) return;
                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Alleen JPG, PNG, GIF of WEBP afbeeldingen zijn toegestaan.');
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('De afbeelding mag niet groter zijn dan 5MB.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!profilePhotoPreview) return;
                    let imgElement = profilePhotoPreview.querySelector('img');
                    if (!imgElement) {
                        profilePhotoPreview.textContent = '';
                        imgElement = document.createElement('img');
                        imgElement.classList.add('w-full', 'h-full', 'object-cover');
                        imgElement.alt = 'Profielfoto';
                        profilePhotoPreview.appendChild(imgElement);
                    }
                    imgElement.src = e.target.result;
                    if (removePhotoButton) removePhotoButton.disabled = false;
                    if (removePhotoInput) removePhotoInput.value = '0';
                };
                reader.readAsDataURL(file);
            });
        }

        if (removePhotoButton) {
            removePhotoButton.addEventListener('click', function() {
                if (profilePhotoInput) profilePhotoInput.value = '';
                if (removePhotoInput) removePhotoInput.value = '1';
                if (profilePhotoPreview) {
                    const img = profilePhotoPreview.querySelector('img');
                    if (img) profilePhotoPreview.removeChild(img);
                    profilePhotoPreview.textContent = <?= json_encode($usernameInitial) ?>;
                }
                this.disabled = true;
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
