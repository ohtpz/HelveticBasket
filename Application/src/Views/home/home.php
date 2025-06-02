<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1>Matches</h1>

        <label for="niveau">Niveau :</label>
        <select name="niveau" id="niveauSelect" class="form-select d-inline-block w-auto ms-2 me-4">
            <option value="null">---</option>
            <option value="U16" <?= ($niveau ?? '') === 'U16' ? 'selected' : '' ?>>U16</option>
            <option value="U18" <?= ($niveau ?? '') === 'U18' ? 'selected' : '' ?>>U18</option>
            <option value="U20" <?= ($niveau ?? '') === 'U20' ? 'selected' : '' ?>>U20</option>
        </select>

        <label for="region">Region :</label>
        <select name="region" id="regionSelect" class="form-select d-inline-block w-auto ms-2">
            <option value="null">---</option>
            <option value="Cantonal" <?= ($region ?? '') === 'Cantonal' ? 'selected' : '' ?>>Cantonal</option>
            <option value="Regional" <?= ($region ?? '') === 'Regional' ? 'selected' : '' ?>>Regional</option>
            <option value="National" <?= ($region ?? '') === 'National' ? 'selected' : '' ?>>National</option>
        </select>
    </div>

    <?php if ($user && $user->verifyAdmin()) : ?>
        <a href="/match/create" class="btn btn-primary">Créer un match</a>
    <?php endif; ?>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Location</th>
            <th>Équipe recevante</th>
            <th>Équipe visiteur</th>
            <th>Résultat</th>
            <th>Détail</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
        <?php foreach ($matches as $data): ?>
            <?php
                $match = $data['match'];
                $homeTeam = $data['homeTeam'];
                $visitorTeam = $data['visitorTeam'];
                $homeClub = $data['homeClub'];
            ?>
            <tr>
                <td><?= $match->getDateTime()->format('d/m/Y H:i') ?></td>
                <td><?= htmlspecialchars($homeClub->getLocation()) ?></td>
                <td><?= htmlspecialchars($homeTeam->getTeamName()) ?></td>
                <td><?= htmlspecialchars($visitorTeam->getTeamName()) ?></td>
                <td><?= $match->getHomeScore() ?> : <?= $match->getVisitorScore() ?></td>
                <td><a href="/match/<?= $match->getId() ?>">Voir détail</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    const niveauSelect = document.getElementById('niveauSelect');
    const regionSelect = document.getElementById('regionSelect');

    function updateFilters() {
        const niveau = niveauSelect.value;
        const region = regionSelect.value;

        const url = new URL(window.location.href);
        if (niveau !== 'null') {
            url.searchParams.set('niveau', niveau);
        } else {
            url.searchParams.delete('niveau');
        }

        if (region !== 'null') {
            url.searchParams.set('region', region);
        } else {
            url.searchParams.delete('region');
        }

        window.location.href = url.toString();
    }

    niveauSelect.addEventListener('change', updateFilters);
    regionSelect.addEventListener('change', updateFilters);
</script>
