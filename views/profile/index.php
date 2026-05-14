<?php require_once __DIR__ . '/../templates/header.php'; ?>

<?php
$created = new DateTime($user['created_at']);
$now = new DateTime();
$interval = $created->diff($now);
$accountAge = $interval->y > 0 ? $interval->y . ' jaar' : ($interval->m > 0 ? $interval->m . ' maanden' : $interval->d . ' dagen');

$profilePhoto = getProfilePhotoUrl($user['profile_photo'], $user['username']);
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Profiel',
    'title'   => $user['username'],
    'lead'    => 'Lid sinds ' . date('d F Y', strtotime($user['created_at'])) . '. Bekijk je activiteit, instellingen en gegevens.',
]) ?>

<section class="pp-container pp-container--wide py-10 md:py-14">
    <?php if (!empty($success_message)): ?>
        <div class="border-l-4 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] text-[color:var(--color-olive)] p-4 mb-8 rounded-r">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5"><?= pp_icon('check-circle', 18) ?></span>
                <span class="text-sm leading-relaxed"><?= pp_e($success_message) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <aside class="lg:col-span-1 space-y-6">
            <div class="keyline-card p-6 text-center">
                <div class="w-24 h-24 mx-auto mb-4 rounded-md overflow-hidden border border-[color:var(--color-keyline)] bg-[color:var(--color-paper-2)] flex items-center justify-center text-3xl font-display text-[color:var(--color-hague)]">
                    <?php if ($profilePhoto['type'] === 'img'): ?>
                        <img src="<?= pp_e($profilePhoto['value']) ?>" alt="<?= pp_e($user['username']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= $profilePhoto['value'] ?>
                    <?php endif; ?>
                </div>
                <h2 class="font-display text-display-md text-[color:var(--color-ink)] mb-1 leading-tight"><?= pp_e($user['username']) ?></h2>
                <p class="text-sm text-[color:var(--color-ink-muted)]">Lid sinds <?= pp_e(date('d F Y', strtotime($user['created_at']))) ?></p>
                <div class="mt-5 pt-5 border-t border-[color:var(--color-keyline)]">
                    <a href="/profile/edit" class="btn btn--primary w-full justify-center">
                        <?= pp_icon('edit-3', 14) ?>
                        Profiel bewerken
                    </a>
                </div>
            </div>

            <div class="keyline-card p-6">
                <div class="eyebrow mb-3">Persoonlijk</div>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-[color:var(--color-ink-faint)] text-xs uppercase tracking-wider mb-0.5">Gebruikersnaam</dt>
                        <dd class="text-[color:var(--color-ink)] font-medium"><?= pp_e($user['username']) ?></dd>
                    </div>
                    <div>
                        <dt class="text-[color:var(--color-ink-faint)] text-xs uppercase tracking-wider mb-0.5">E-mailadres</dt>
                        <dd class="text-[color:var(--color-ink)] font-medium break-all"><?= pp_e($user['email']) ?></dd>
                    </div>
                    <div>
                        <dt class="text-[color:var(--color-ink-faint)] text-xs uppercase tracking-wider mb-0.5">Account-leeftijd</dt>
                        <dd class="text-[color:var(--color-ink)] font-medium"><?= pp_e($accountAge) ?></dd>
                    </div>
                    <?php if (!empty($user['bio'])): ?>
                        <div class="pt-3 border-t border-[color:var(--color-keyline)]">
                            <dt class="text-[color:var(--color-ink-faint)] text-xs uppercase tracking-wider mb-1.5">Over mij</dt>
                            <dd class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed"><?= nl2br(pp_e($user['bio'])) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

        </aside>

        <div class="lg:col-span-2 space-y-6">
            <div class="keyline-card p-6 md:p-8">
                <div class="eyebrow mb-3">Activiteit</div>
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-5 leading-tight">Account-statistieken</h2>
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center py-4 border-r border-[color:var(--color-keyline)] last:border-r-0">
                        <div class="font-display text-display-2xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) ($stats['blogs'] ?? 0) ?></div>
                        <div class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Blogs</div>
                    </div>
                    <div class="text-center py-4 border-r border-[color:var(--color-keyline)] last:border-r-0">
                        <div class="font-display text-display-2xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) ($stats['comments'] ?? 0) ?></div>
                        <div class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Reacties</div>
                    </div>
                    <div class="text-center py-4">
                        <div class="font-display text-display-2xl text-[color:var(--color-ink)] font-mono text-tabular"><?= (int) ($stats['likes_received'] ?? 0) ?></div>
                        <div class="text-xs uppercase tracking-wider text-[color:var(--color-ink-faint)]">Likes</div>
                    </div>
                </div>

                <div class="border-t border-[color:var(--color-keyline)] pt-5">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="eyebrow mb-1">Engagement-niveau</div>
                            <div class="font-display text-lg text-[color:var(--color-ink)]"><?= pp_e($stats['engagement_level'] ?? 'Beginner') ?></div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-[color:var(--color-ink-faint)] uppercase tracking-wider">Laatste activiteit</div>
                            <div class="text-sm text-[color:var(--color-ink)]"><?= pp_e($stats['last_activity'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="h-1.5 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden">
                        <div class="h-full bg-[color:var(--color-hague)] rounded-full transition-all" style="width: <?= pp_e($stats['engagement_percentage'] ?? '0%') ?>"></div>
                    </div>
                    <div class="flex justify-between text-xs text-[color:var(--color-ink-faint)] mt-2">
                        <span>Beginner</span>
                        <span>Regelmatig</span>
                        <span>Actief</span>
                        <span>Gevorderd</span>
                        <span>Expert</span>
                    </div>
                </div>
            </div>

            <div class="keyline-card p-6 md:p-8">
                <div class="eyebrow mb-3">Snelle toegang</div>
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-5 leading-tight">Aan de slag</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="/blogs/create" class="border border-[color:var(--color-keyline)] rounded-md p-5 hover:bg-[color:var(--color-paper-2)] transition-colors">
                        <div class="text-[color:var(--color-hague)] mb-2"><?= pp_icon('pen-tool', 22) ?></div>
                        <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Schrijf een blog</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Deel je politieke analyse.</p>
                    </a>
                    <a href="/partijmeter" class="border border-[color:var(--color-keyline)] rounded-md p-5 hover:bg-[color:var(--color-paper-2)] transition-colors">
                        <div class="text-[color:var(--color-hague)] mb-2"><?= pp_icon('vote', 22) ?></div>
                        <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Doe de PartijMeter</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Vergelijk je standpunten.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (isAdmin()): ?>
    <div class="fixed bottom-4 right-4 z-50">
        <a href="?debug_photo=1" class="text-xs bg-[color:var(--color-ink)] text-white py-1 px-2 rounded opacity-70 hover:opacity-100 transition-opacity">
            Debug photo
        </a>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
