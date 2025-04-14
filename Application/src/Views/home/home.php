<h1>Matches</h1>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Location</th>
            <th scope="col">Équipe recevante</th>
            <th scope="col">Équipe visiteur</th>
            <th scope="col">Résultat</th>
            <th scope="col">Détail</th>
        </tr>
    </thead>
    <tbody class="table-group-diviser">

    <?php foreach ($matches as $data): ?>
    <?php
        $match = $data['match'];
        $homeTeam = $data['homeTeam'];
        $visitorTeam = $data['visitorTeam'];
        $homeClub = $data['homeClub'];
        $visitorClub = $data['visitorClub'];
    ?>
    <tr>
        <td><?= $match->getDateTime()->format('d/m/Y H:i') ?></td>
        <td><?= htmlspecialchars($homeTeam->getTeamName()) ?></td>
        <td><?= htmlspecialchars($visitorTeam->getTeamName()) ?></td>
        <td><?= $match->getHomeScore() ?> : <?= $match->getVisitorScore() ?></td>
        <td><?= htmlspecialchars($homeClub->getLocation()) ?></td>
        <td><a href="match/<?= htmlspecialchars($match->getId()) ?>">Voir détail</a></td>

    </tr>
<?php endforeach; ?>
    </tbody>
</table>