<div class="card mb-4">
  <div class="card-body text-center">
    <h3 class="mb-3">Match Summary</h3>

    <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap mb-3">

      <!-- Home team -->
      <div class="d-flex align-items-center gap-2">
        <img src="/img/<?= htmlspecialchars($homeClub->getLogo()) ?>" alt="Home Logo" height="60">
        <span class="h5 mb-0"><?= htmlspecialchars($homeTeam->getTeamName()) ?></span>
      </div>

      <!-- Score -->
      <div class="fw-bold display-6 mx-4">
        <?= $match->getHomeScore() ?> : <?= $match->getVisitorScore() ?>
      </div>

      <!-- Visitor team -->
      <div class="d-flex align-items-center gap-2">
        <span class="h5 mb-0"><?= htmlspecialchars($visitorTeam->getTeamName()) ?></span>
        <img src="/img/<?= htmlspecialchars($visitorClub->getLogo()) ?>" alt="Visitor Logo" height="60">
      </div>

    </div>

    <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($homeClub->getLocation()) ?></p>
    <p class="mb-1">
      <strong>Date Time:</strong>
      <?= $match->getDateTime()->format('d/m/Y') ?> at <?= $match->getDateTime()->format('H:i') ?>
    </p>
  </div>
</div>

