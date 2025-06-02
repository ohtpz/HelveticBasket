<h1>Modifier le Match</h1>

<form method="post" action="/match/update/<?= $match->getId() ?>">
    <div class="card mb-4">
        <div class="card-body text-center">
            <h3 class="mb-3">Score</h3>

            <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap mb-3">
                <!-- Home team -->
                <div class="d-flex align-items-center gap-2">
                    <img src="/img/<?= htmlspecialchars($homeClub->getLogo()) ?>" alt="Home Logo" height="60">
                    <span class="h5 mb-0"><?= htmlspecialchars($homeTeam->getTeamName()) ?></span>
                </div>

                <!-- Score inputs -->
                <div class="d-flex align-items-center gap-2">
                    <input type="number" name="homeScore" class="form-control text-center" style="width: 80px;" value="<?= $match->getHomeScore() ?>" required>
                    <span class="fw-bold">:</span>
                    <input type="number" name="visitorScore" class="form-control text-center" style="width: 80px;" value="<?= $match->getVisitorScore() ?>" required>
                </div>

                <!-- Visitor team -->
                <div class="d-flex align-items-center gap-2">
                    <span class="h5 mb-0"><?= htmlspecialchars($visitorTeam->getTeamName()) ?></span>
                    <img src="/img/<?= htmlspecialchars($visitorClub->getLogo()) ?>" alt="Visitor Logo" height="60">
                </div>
            </div>

            <!-- Date and Time inputs -->
            <div class="mt-3">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <label class="form-label">Date du match</label>
                        <input type="date" name="matchDate" class="form-control" value="<?= $match->getDateTime()->format('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Heure du match</label>
                        <input type="time" name="matchTime" class="form-control" value="<?= $match->getDateTime()->format('H:i') ?>" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3>Joueurs de l'équipe recevante</h3>
    <table class="table text-center">
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Points</th>
                <th>Minutes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($homePlayers as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player->getPlayerName()) ?></td>
                    <td><input type="number" name="homePoints[<?= $player->getId() ?>]" class="form-control" value="<?= $player->getPoints() ?>" required></td>
                    <td><input type="number" name="homeMinutes[<?= $player->getId() ?>]" class="form-control" value="<?= $player->getMinutes() ?>" required></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Joueurs de l'équipe visiteur</h3>
    <table class="table text-center">
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Points</th>
                <th>Minutes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visitorPlayers as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player->getPlayerName()) ?></td>
                    <td><input type="number" name="visitorPoints[<?= $player->getId() ?>]" class="form-control" value="<?= $player->getPoints() ?>" required></td>
                    <td><input type="number" name="visitorMinutes[<?= $player->getId() ?>]" class="form-control" value="<?= $player->getMinutes() ?>" required></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-success mt-3">Enregistrer les modifications</button>
</form>

<form method="post" action="/match/delete/<?= $match->getId()?>">
    <button type="submit" class="btn btn-danger mt-3">Supprimer le match</button>
</form>
