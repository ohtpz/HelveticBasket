<h1>Modifier le club</h1>

<form action="/club/update" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $club->getId() ?>">

    <div class="mb-3">
        <label for="clubName">Nom du club</label>
        <input type="text" name="clubName" id="clubName"
               class="form-control <?= isset($errors['clubName']) ? 'is-invalid' : '' ?>"
               value="<?= htmlspecialchars($club->getName()) ?>" required>
        <?php if (isset($errors['clubName'])) : ?>
            <div class="invalid-feedback"><?= $errors['clubName'] ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="logoClub">Logo du club (laisser vide pour ne pas changer)</label>
        <input type="file" name="logoClub" id="logoClub"
               class="form-control <?= isset($errors['file']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errors['file'])) : ?>
            <div class="invalid-feedback"><?= $errors['file'] ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="location">Location des matchs</label>
        <input type="text" name="location" id="location"
               class="form-control <?= isset($errors['location']) ? 'is-invalid' : '' ?>"
               value="<?= htmlspecialchars($club->getLocation()) ?>" required>
        <?php if (isset($errors['location'])) : ?>
            <div class="invalid-feedback"><?= $errors['location'] ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Modifier le club</button>
</form>
