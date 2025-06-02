<h1>Créer des joueurs</h1>

<form method="post" action="/player/create">
    <div class="mb-3">
        <label for="globalTeamId" class="form-label">Équipe</label>
        <select name="teamId" id="globalTeamId" class="form-select" required>
            <option value="">-- Choisir une équipe --</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?= $team->getId() ?>"><?= htmlspecialchars($team->getTeamName()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="players-container">
        <div class="player-form border rounded p-3 mb-3">
            <div class="mb-3">
                <label for="name[]" class="form-label">Nom du joueur</label>
                <input type="text" name="name[]" class="form-control" required>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-secondary mb-3" onclick="addPlayerForm()">+ Ajouter un joueur</button>
    <br>
    <button type="submit" class="btn btn-primary">Créer les joueurs</button>
</form>

<script>
function addPlayerForm() {
    const container = document.getElementById('players-container');
    const original = container.querySelector('.player-form');
    const clone = original.cloneNode(true);
    clone.querySelector('input').value = '';
    container.appendChild(clone);
}
</script>
