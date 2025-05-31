<h1>Créer un match</h1>

<?php
$homeTeamId = $_POST['idHomeTeam'] ?? null;
$visitorTeamId = $_POST['idVisitorTeam'] ?? null;
?>

<form method="post" action="/match/store">

    <div class="mb-3">
        <label>Équipe recevante</label>
        <select name="idHomeTeam" id="homeTeamSelect" class="form-select" required>
            <option value="" selected disabled>-- Choisir une équipe --</option>
            <?php foreach ($teams as $team) : ?>
                <option value="<?= $team->getId() ?>"
                    <?= ($homeTeamId == $team->getId()) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($team->getTeamName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Équipe visiteur</label>
        <select name="idVisitorTeam" id="visitorTeamSelect" class="form-select" required>
            <option value="" selected disabled>-- Choisir une équipe --</option>
            <?php foreach ($teams as $team) : ?>
                <option value="<?= $team->getId() ?>"
                    <?= ($visitorTeamId == $team->getId()) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($team->getTeamName()) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <a href="/team/create" class="btn btn-secondary">Créer une équipe</a>
    <a href="/player/create" class="btn btn-secondary">Ajouter des joueurs</a>
    <hr>

    <h4>Joueurs - sélectionnez ceux qui ont joué</h4>

    <div class="mb-3">
        <label><strong>Joueurs de l'équipe recevante :</strong></label>
        <?php foreach ($players as $player) : ?>
            <div class="form-check home-player" data-team-id="<?= $player->getTeamId() ?>" style="display:none;">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="homePlayers[]" 
                       value="<?= $player->getId() ?>">
                <label class="form-check-label"><?= htmlspecialchars($player->getName()) ?></label>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <label><strong>Joueurs de l'équipe visiteur :</strong></label>
        <?php foreach ($players as $player) : ?>
            <div class="form-check visitor-player" data-team-id="<?= $player->getTeamId() ?>" style="display:none;">
                <input class="form-check-input" 
                       type="checkbox" 
                       name="visitorPlayers[]" 
                       value="<?= $player->getId() ?>">
                <label class="form-check-label"><?= htmlspecialchars($player->getName()) ?></label>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-primary">Créer le match</button>
</form>

<script>
    const homeSelect = document.getElementById('homeTeamSelect');
    const visitorSelect = document.getElementById('visitorTeamSelect');

    // Affiche uniquement les joueurs de l'équipe sélectionnée et décoche les autres.
    function updatePlayerVisibility(selectElement, playerClass) {
        const selectedTeamId = selectElement.value;

        document.querySelectorAll(playerClass).forEach(function(playerDiv) {
            if (playerDiv.dataset.teamId === selectedTeamId) {
                playerDiv.style.display = 'block';
            } else {
                playerDiv.style.display = 'none';
                playerDiv.querySelector('input').checked = false;
            }
        });
    }

    // Écouteur d'événement pour le changement de sélection
    homeSelect.addEventListener('change', function() {
        updatePlayerVisibility(homeSelect, '.home-player');
    });

    visitorSelect.addEventListener('change', function() {
        updatePlayerVisibility(visitorSelect, '.visitor-player');
    });


    // Change la visibilité des joueurs en fonction de la sélection
    if (homeSelect.value) {
        updatePlayerVisibility(homeSelect, '.home-player');
    }
    if (visitorSelect.value) {
        updatePlayerVisibility(visitorSelect, '.visitor-player');
    }

    // Valide le formulaire si ya au moins 5 joueurs sélectionnés pour chaque équipe
    document.querySelector('form').addEventListener('submit', function(event) {
        const homeSelected = document.querySelectorAll('.home-player input[type="checkbox"]:checked').length;
        const visitorSelected = document.querySelectorAll('.visitor-player input[type="checkbox"]:checked').length;

        if (homeSelected < 5 || visitorSelected < 5) {
            event.preventDefault();
            alert('Vous devez sélectionner au moins 5 joueurs pour chaque équipe.');
        }
    });
</script>
