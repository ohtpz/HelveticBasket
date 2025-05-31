<div class="d-flex align-items-center mb-3">
    <h2 class="mb-0 me-3">Club</h2>
    <a href="/club/create" class="btn btn-primary">Créé un club</a>
</div>
<!-- List des clubs -->
<div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
    <?php foreach ($clubs as $club): ?>
        <div class="card mb-3" style="width: 24rem;">
            <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($club->getName()) ?></h5>
                <p class="card-text">Location: <?= htmlspecialchars($club->getLocation()) ?></p>
                <img src="img/<?= htmlspecialchars($club->getLogo()) ?>" alt="<?= htmlspecialchars($club->getName()) ?>" class="img-fluid" style="height: 250px;">
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="/club/edit/<?= htmlspecialchars($club->getId()) ?>" class="btn btn-warning">Modifier</a>
                    <a href="/club/delete/<?= htmlspecialchars($club->getId()) ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?')">Supprimer</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex align-items-center mb-3">
    <h2 class="mb-0 me-3" >Équipes</h2>
    <!-- List des équipes -->
    <a href="/team/create" class="btn btn-primary">Créer une équipe</a>
</div>

<div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
    <?php foreach ($teams as $team): ?>
        <div class="card mb-3" style="width: 20rem;">
            <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($team->getTeamName()) ?></h5>
                <p class="card-text">Niveau: <?= htmlspecialchars($team->getLevel()) ?></p>
                <p class="card-text">Région: <?= htmlspecialchars($team->getRegion()) ?></p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="/team/edit/<?= htmlspecialchars($team->getId()) ?>" class="btn btn-warning">Modifier</a>
                    <a href="/team/delete/<?= htmlspecialchars($team->getId()) ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')">Supprimer</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>