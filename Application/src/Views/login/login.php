<div class="d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card bg-white border border-danger border-1">
                    <div class="card-body px-5 py-3">

                        <form class="mb-3 mt-md-4" method="post">
                            <h2 class="fw-bold mb-2 text-uppercase ">Connexion</h2>
                            <?php if (isset($errors['credentials'])) { ?>
                                <div class="alert alert-danger" role="alert"><?= $errors['credentials'] ?></div>
                            <?php } ?>
                            <div class="mb-3">
                                <label for="email" class="form-label ">Adresse email</label>
                                <input required type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="name@example.com" value="<?= $data['email'] ?? '' ?>">
                                <?php if (isset($errors['email'])) { ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php } ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label ">Mot de passe</label>
                                <input required type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="*******">
                                <?php if (isset($errors['password'])) { ?>
                                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                <?php } ?>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-danger" type="submit">Se connecter</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p class="mb-0">Pas de compte ? <a href="/signup" class="text-decoration-none">Créez-en un</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

