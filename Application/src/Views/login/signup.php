<div class="d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card bg-white border border-danger border-1">
                    <div class="card-body px-5 py-3">

                        <form class="mb-3 mt-md-4" method="post">
                            <h2 class="fw-bold mb-2 text-uppercase text-center">Inscription</h2>

                            <?php if (isset($errors['general'])): ?>
                                <div class="alert alert-danger"><?= $errors['general'] ?></div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" name="name" id="name"
                                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                                       value="<?= $data['name'] ?? '' ?>" required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" name="email" id="email"
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                       value="<?= $data['email'] ?? '' ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" id="password"
                                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="confirmPassword" id="confirmPassword"
                                       class="form-control <?= isset($errors['confirmPassword']) ? 'is-invalid' : '' ?>" required>
                                <?php if (isset($errors['confirmPassword'])): ?>
                                    <div class="invalid-feedback"><?= $errors['confirmPassword'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">S'inscrire</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
