<div class="container mt-4">
    <div class="text-center mb-4">
        <div class="d-flex align-items-center justify-content-center gap-3">
            <img src="/img/<?= htmlspecialchars($club->getLogo()) ?>" alt="Club Logo" height="80">
            <div class="d-flex align-items-center gap-2">
                <h1 class="mb-0"><?= htmlspecialchars($team->getTeamName()) ?></h1>
                <?php if ($user): ?>
                    <button class="btn btn-link favorite-btn" data-team-id="<?= $team->getId() ?>" data-is-favorite="<?= $isFavorite ? '1' : '0' ?>">
                        <i class="bi <?= $isFavorite ? 'bi-heart-fill' : 'bi-heart' ?> fs-4 text-danger"></i>
                    </button>
            <?php endif; ?>
            </div>
        </div>
    </div>  
</div>


    <h2 class="mb-3">Prochains matches</h2>
    <?php if (empty($futureMatches)): ?>
        <p class="text-muted">Aucun match à venir</p>
    <?php else: ?>
        <div class="list-group mb-4">
            <?php foreach ($futureMatches as $data): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($data['isHomeTeam']): ?>
                                <img src="/img/<?= htmlspecialchars($data['homeClub']->getLogo()) ?>" alt="Home Logo" height="40">
                                <strong><?= htmlspecialchars($data['homeTeam']->getTeamName()) ?></strong>
                                <span>vs</span>
                                <span><?= htmlspecialchars($data['visitorTeam']->getTeamName()) ?></span>
                                <img src="/img/<?= htmlspecialchars($data['visitorClub']->getLogo()) ?>" alt="Visitor Logo" height="40">
                            <?php else: ?>
                                <img src="/img/<?= htmlspecialchars($data['homeClub']->getLogo()) ?>" alt="Home Logo" height="40">
                                <span><?= htmlspecialchars($data['homeTeam']->getTeamName()) ?></span>
                                <span>vs</span>
                                <strong><?= htmlspecialchars($data['visitorTeam']->getTeamName()) ?></strong>
                                <img src="/img/<?= htmlspecialchars($data['visitorClub']->getLogo()) ?>" alt="Visitor Logo" height="40">
                            <?php endif; ?>
                        </div>
                        <div class="text-muted">
                            <?= (new DateTime($data['match']->getDate()))->format('d.m.Y H:i') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2 class="mb-3">Matches passés</h2>
    <?php if (empty($pastMatches)): ?>
        <p class="text-muted">Aucun match passé</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($pastMatches as $data): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($data['isHomeTeam']): ?>
                                <img src="/img/<?= htmlspecialchars($data['homeClub']->getLogo()) ?>" alt="Home Logo" height="40">
                                <strong><?= htmlspecialchars($data['homeTeam']->getTeamName()) ?></strong>
                                <span class="badge bg-primary"><?= $data['match']->getHomeScore() ?></span>
                                <span>-</span>
                                <span class="badge bg-primary"><?= $data['match']->getVisitorScore() ?></span>
                                <span><?= htmlspecialchars($data['visitorTeam']->getTeamName()) ?></span>
                                <img src="/img/<?= htmlspecialchars($data['visitorClub']->getLogo()) ?>" alt="Visitor Logo" height="40">
                            <?php else: ?>
                                <img src="/img/<?= htmlspecialchars($data['homeClub']->getLogo()) ?>" alt="Home Logo" height="40">
                                <span><?= htmlspecialchars($data['homeTeam']->getTeamName()) ?></span>
                                <span class="badge bg-primary"><?= $data['match']->getHomeScore() ?></span>
                                <span>-</span>
                                <span class="badge bg-primary"><?= $data['match']->getVisitorScore() ?></span>
                                <strong><?= htmlspecialchars($data['visitorTeam']->getTeamName()) ?></strong>
                                <img src="/img/<?= htmlspecialchars($data['visitorClub']->getLogo()) ?>" alt="Visitor Logo" height="40">
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="/match/<?= $data['match']->getId() ?>" class="btn btn-sm btn-outline-primary">Détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const favoriteBtn = document.querySelector('.favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            const teamId = this.dataset.teamId;
            const icon = this.querySelector('i');
            
            fetch('/team/toggle-favorite/' + teamId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.isFavorite) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                    } else {
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                    }
                }
            });
        });
    }
});
</script> 