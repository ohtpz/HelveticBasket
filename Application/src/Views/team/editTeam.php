<h1>Modifier l'équipe</h1>

<form method="post" action="/team/update">
    <!-- ID de l'équipe (caché) -->
    <input type="hidden" name="id" value="<?= $team->getId() ?>">

    <div class="mb-3">
        <label for="teamName">Nom de l'équipe</label>
        <input type="text" name="teamName" id="teamName"
               class="form-control <?= isset($errors['teamName']) ? 'is-invalid' : '' ?>"
               value="<?= htmlspecialchars($data['teamName'] ?? '') ?>" required>
        <?php if (isset($errors['teamName'])): ?>
            <div class="invalid-feedback"><?= $errors['teamName'] ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="idClub">Club associé</label>
        <select name="idClub" id="idClub" class="form-select <?= isset($errors['idClub']) ? 'is-invalid' : '' ?>" required>
            <option value="" disabled>-- Sélectionnez un club --</option>
            <?php foreach ($clubs as $club): ?>
                <option value="<?= $club->getId() ?>" <?= ($club->getId() == $data['idClub']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($club->getName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['idClub'])): ?>
            <div class="invalid-feedback"><?= $errors['idClub'] ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="level">Niveau</label>
        <select name="level" id="level" class="form-select <?= isset($errors['level']) ? 'is-invalid' : '' ?>" required>
            <option value="" disabled>-- Sélectionnez un niveau --</option>
            <option value="U16" <?= ($data['level'] ?? '') === 'U16' ? 'selected' : '' ?>>U16</option>
            <option value="U18" <?= ($data['level'] ?? '') === 'U18' ? 'selected' : '' ?>>U18</option>
            <option value="U20" <?= ($data['level'] ?? '') === 'U20' ? 'selected' : '' ?>>U20</option>
        </select>
        <?php if (isset($errors['level'])): ?>
            <div class="invalid-feedback"><?= $errors['level'] ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="region">Région</label>
        <select name="region" id="region" class="form-select <?= isset($errors['region']) ? 'is-invalid' : '' ?>" required>
            <option value="" disabled>-- Sélectionnez une région --</option>
            <option value="Cantonal" <?= ($data['region'] ?? '') === 'Cantonal' ? 'selected' : '' ?>>Cantonal</option>
            <option value="Regional" <?= ($data['region'] ?? '') === 'Regional' ? 'selected' : '' ?>>Regional</option>
            <option value="National" <?= ($data['region'] ?? '') === 'National' ? 'selected' : '' ?>>National</option>
        </select>
        <?php if (isset($errors['region'])): ?>
            <div class="invalid-feedback"><?= $errors['region'] ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
</form>
