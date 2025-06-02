<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Créer une équipe</h1>

    <?php if ($user && $user->verifyAdmin()) { ?>
        <a href="/club/create" class="btn btn-primary">Créer un club</a>
    <?php } ?>
</div>
<form method="post" action="/team/create">

    <div class="mb-3">
        <label for="teamName">Nom de l'équipe</label>
        <input type="text" name="teamName" id="teamName" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="idClub">Club</label>
        <select name="idClub" id="idClub" class="form-select" required>
            <option value="" disabled selected>-- Sélectionnez un club --</option>
            <?php foreach ($clubs as $club) : ?>
                <option value="<?= $club->getId() ?>"><?= htmlspecialchars($club->getName()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="niveau">Niveau</label>
        <select name="niveau" id="niveau" class="form-select" required>
            <option value="" disabled selected>-- Sélectionnez un niveau --</option>
            <option value="U16">U16</option>
            <option value="U18">U18</option>
            <option value="U20">U20</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="region">Region</label>
        <select name="region" id="region" class="form-select" required>
            <option value="" disabled selected>-- Sélectionnez un region --</option>
            <option value="Cantonal">Cantonal</option>
            <option value="Regional">Regional</option>
            <option value="National">National</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Créer l'équipe</button>
</form>
