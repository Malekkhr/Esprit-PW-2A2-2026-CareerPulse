<?php
/** @var CareerPulse\Models\UserModel $account */
$action = $isEdit ? route('admin/users/edit', ['id' => $account->getId()]) : route('admin/users/create');
?>
<div class="adm-head">
    <div>
        <p class="adm-eyebrow">COMPTES</p>
        <h1 class="adm-title"><?= $isEdit ? 'Modifier l’utilisateur' : 'Ajouter un utilisateur' ?></h1>
        <p class="adm-sub"><?= $isEdit ? 'Modifiez les informations, le rôle et le statut du compte.' : 'Créez un compte et choisissez son rôle.' ?></p>
    </div>
    <a class="adm-btn" href="<?= e(route('admin/users')) ?>">← Retour à la liste</a>
</div>

<section class="adm-card">
    <?php if (!empty($errors)): ?>
        <div class="adm-alert" role="alert">
            <?php foreach ($errors as $error): ?>
                <div><?= e($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e($action) ?>" id="user-form" data-cp-validate novalidate>
        <?= csrf_input() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="user_id" value="<?= (int) $account->getId() ?>">
        <?php endif; ?>

        <div class="adm-form-grid">
            <div class="adm-field">
                <label for="username">Nom d’utilisateur *</label>
                <input class="adm-input" id="username" name="username" value="<?= e($account->getUsername()) ?>" autocomplete="off" maxlength="50" data-v="required username" required>
                <span class="adm-help">3 à 50 caractères : lettres, chiffres, _ ou -</span>
            </div>
            <div class="adm-field">
                <label for="email">Adresse e-mail *</label>
                <input class="adm-input" id="email" name="email" type="email" value="<?= e($account->getEmail()) ?>" autocomplete="off" maxlength="150" data-v="required email" required>
            </div>
            <div class="adm-field adm-full">
                <label for="study">Formation actuelle</label>
                <input class="adm-input" id="study" name="study" value="<?= e($account->getStudy()) ?>" maxlength="160" data-label="La formation actuelle" data-v="max" data-max="160">
            </div>

            <div class="adm-field">
                <label for="role">Rôle *</label>
                <select class="adm-select" id="role" name="role" <?= $isSelf ? 'disabled' : '' ?>>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= e($r) ?>" <?= $account->getRole() === $r ? 'selected' : '' ?>><?= e($r) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="adm-help"><?= $isSelf ? 'Vous ne pouvez pas modifier votre propre rôle.' : 'user : frontoffice · admin : backoffice' ?></span>
            </div>
            <div class="adm-field">
                <label for="status">Statut *</label>
                <select class="adm-select" id="status" name="status" <?= $isSelf ? 'disabled' : '' ?>>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= e($s) ?>" <?= $account->getStatus() === $s ? 'selected' : '' ?>><?= $s === 'active' ? 'Actif' : 'Suspendu' ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="adm-help"><?= $isSelf ? 'Vous ne pouvez pas suspendre votre propre compte.' : 'Un compte suspendu ne peut plus se connecter.' ?></span>
            </div>

            <?php if ($isEdit): ?>
                <div class="adm-field adm-full">
                    <label for="skills">Compétences</label>
                    <textarea class="adm-textarea" id="skills" name="skills" rows="3" maxlength="1000" data-label="Les compétences" data-v="max" data-max="1000"><?= e($account->getSkills()) ?></textarea>
                </div>
                <div class="adm-field adm-full">
                    <label for="goal">Objectif d’apprentissage</label>
                    <textarea class="adm-textarea" id="goal" name="goal" rows="3" maxlength="1000" data-label="L’objectif" data-v="max" data-max="1000"><?= e($account->getGoal()) ?></textarea>
                </div>
            <?php endif; ?>

            <div class="adm-field">
                <label for="password">Mot de passe <?= $isEdit ? '' : '*' ?></label>
                <input class="adm-input" id="password" name="password" type="password" autocomplete="new-password" maxlength="72" data-label="Le mot de passe" <?= $isEdit ? 'data-v="password" data-required-if="#password_confirm"' : 'data-v="required password"' ?>>
                <span class="adm-help"><?= $isEdit ? 'Laissez vide pour conserver le mot de passe actuel. ' : '' ?>8 caractères minimum, avec au moins une lettre et un chiffre.</span>
            </div>
            <div class="adm-field">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input class="adm-input" id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" maxlength="72" data-label="La confirmation" data-v="match" data-match="#password" data-required-if="#password">
            </div>
        </div>

        <div class="adm-actions" style="margin-top: 24px;">
            <button class="adm-btn adm-btn--primary" type="submit"><?= $isEdit ? 'Enregistrer les modifications' : 'Créer l’utilisateur' ?></button>
            <a class="adm-btn" href="<?= e(route('admin/users')) ?>">Annuler</a>
        </div>
    </form>
</section>
