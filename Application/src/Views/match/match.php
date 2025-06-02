<div class="card mb-4">
    <div class="card-body text-center">
        <h3 class="mb-3">Match Summary</h3>

        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap mb-3">

            <!-- Home team -->
            <div class="d-flex align-items-center gap-2">
                <img src="/img/<?= htmlspecialchars($homeClub->getLogo()) ?>" alt="Home Logo" height="60">
                <span class="h5 mb-0"><a href="/team/<?= $homeTeam->getId() ?>"> <?= htmlspecialchars($homeTeam->getTeamName()) ?> </a></span>
            </div>

            <!-- Score -->
            <div class="fw-bold display-6 mx-4">
                <?= $match->getHomeScore() ?> : <?= $match->getVisitorScore() ?>
            </div>

            <!-- Visitor team -->
            <div class="d-flex align-items-center gap-2">
                <span class="h5 mb-0"><a href="/team/<?= $visitorTeam->getId() ?>"><?= htmlspecialchars($visitorTeam->getTeamName()) ?></a></span>
                <img src="/img/<?= htmlspecialchars($visitorClub->getLogo()) ?>" alt="Visitor Logo" height="60">
            </div>

           
                

        </div>
    
        <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($homeClub->getLocation()) ?></p>
        <p class="mb-1">
            <strong>Date Time:</strong>
            <?= $match->getDateTime()->format('d/m/Y') ?> at <?= $match->getDateTime()->format('H:i') ?>
        </p>

        <?php if ($user && $user->verifyAdmin()) : ?>
        <div class="text-center mt-3">
            <a href="/match/edit/<?= $match->getId() ?>" class="btn btn-primary">Modifier</a>
        </div>
        <?php endif; ?>
            
    </div>
</div>

<h3>Home Team Players</h3>
<table class="table text-center">
    <thead>
        <tr>
            <th>Player</th>
            <th>Points</th>
            <th>Minutes</th>
            

        </tr>
    </thead>
    <tbody>
        <?php foreach ($homePlayers as $player): ?>
            <tr>
                <td><?= htmlspecialchars($player->getPlayerName()) ?></td>
                <td><?= $player->getPoints() ?></td>
                <td><?= $player->getMinutes() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Visitor Team Players</h3>
<table class="table text-center">
    <thead>
        <tr>
            <th>Player</th>
            <th>Points</th>
            <th>Minutes</th>
    
        </tr>
    </thead>
    <tbody>
        <?php foreach ($visitorPlayers as $player): ?>
            <tr>
                <td><?= htmlspecialchars($player->getPlayerName()) ?></td>
                <td><?= $player->getPoints() ?></td>
                <td><?= $player->getMinutes() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
