<div class="container mt-4">
    <h1 class="mb-4">Mes équipes favorites</h1>

    <?php if (empty($teams)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore d'équipes favorites.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($teams as $team): ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                                <img src="/img/<?= htmlspecialchars($team['clubLogo']) ?>" alt="Club Logo" height="60">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($team['teamName']) ?></h5>
                            </div>
                            <p class="card-text text-muted mb-3"><?= htmlspecialchars($team['clubName']) ?></p>
                            <a href="/team/<?= $team['id'] ?>" class="btn btn-primary">Voir l'équipe</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 