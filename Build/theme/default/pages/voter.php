<section id="Vote">
    <div class="container-fluid col-md-9 col-lg-9 col-sm-10">
        <!-- Alerts -->
        <div class="alertSection mb-3">
            <?php if (isset($_GET['success'])) :
                if ($_GET['success'] != 'recupTemp') : ?>
                    <div class="alert alert-success alert-dismissible fade show text-shadow-none" role="alert">
                        Votre récompense arrive, si vous n'avez pas vu de fenêtre s'ouvrir pour voter, la fenêtre à dû s'ouvrir derrière votre navigateur, validez le vote et <strong>profitez de votre récompense In-Game</strong> !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php else : ?>
                    <div class="alert alert-success alert-dismissible fade show text-shadow-none" role="alert">
                        La récompense séléctionnée arrive, <strong>Profitez de cette dernière In-Game ! </strong>
                        Votre(vos) récompense(s) arrive(nt), profitez de votre(vos) récompense(s) In-Game !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            <?php endif;
            endif; ?>
        </div>
        <div class="row">
            <!-- Présentation -->
            <div class="d-flex col-12 info-page">
                <i class="fas fa-info-circle notification-icon"></i>
                <div class="info-content">
                    Voter pour le serveur permet d'améliorer son référencement ! Les votes sont récompensés par des items In-Game.
                </div>
            </div>
        </div>

        <!-- Gestion des information et doublons de vote -->
        <div>
            <?php
            if (Permission::getInstance()->verifPerm("connect") and isset($_GET['player']) and $_Joueur_['pseudo'] == $_GET['player']) {  ?>
                    <!-- Gestion des Récompenses -->
                    <div class="alert alert-success text-center"  id="disprecompList" <?php if (!empty($donneesVotesTemp)) { ?> style="display:none;" <?php } ?>>
                        <ul style="list-style-position: inside; padding-left: 0px;" id="recompList">

                            <script>
                            <?php foreach ($donneesVotesTemp as $data) { ?>
                                addRecompense(JSON.parse('<?php echo $data['action']; ?>'));
                            <?php }
                            ?>
                            updateRecompenseList();
                            </script>
                    </div>
            <?php } ?>
        </div>


        <div class="row">
            <?php if (!isset($_GET['player'])) { ?>

                <!-- Demande du Pseudonyme -->
                <div class="col col-md-12 col-lg-12 col-sm-12 mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>Entrez votre pseudonyme</h4>
                            <div class="card-body">
                                <form id="forme-vote" role="form" method="GET" action="index.php">
                                    <div>
                                        <div class="row">
                                            <input type="text" style="display:none;" name="page" value="voter">
                                            <div class="col-md-12 col-lg-9 col-sm-12">
                                                <input type="text" id="vote-pseudo" class="form-control" name="player" placeholder="Pseudo" value="<?= (Permission::getInstance()->verifPerm("connect")) ? $_Joueur_['pseudo'] : '' ?>" required>
                                            </div>
                                            <div class="col-md-12 col-lg-3 col-sm-12">
                                                <button class="form-control btn btn-reverse" type="submit">Suivant</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            <?php } else { ?>

                <!-- Affichage des serveurs de jeu -->
                <div class="col-md-12 col-lg-3 col-sm-12 mb-3">
                    <!-- Serveurs -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Serveurs :</h4>
                        </div>
                        <div class="card-body categories">
                            <ul class="categorie-content nav nav-tabs">
                                <!-- Affichage noms Serveurs -->
                                <?php if(count($lectureJSON) == 0) { ?>

                                    <p>Veuillez relier votre serveur à votre site avec JsonAPI depuis le panel pour avoir les liens de vote !</p>

                                <?php } else { ?>

                                <?php $first = true; foreach($lectureJSON as $serveur) { ?>

                                    <li class="nav-item categorie-item<?= ($i == 0) ? ' active' : '' ?>">
                                        <a href="#voter-<?= $serveur['id']; ?>" data-toggle="tab" class="categorie-link nav-link<?= ($first) ? ' active' : '' ?>">
                                            <?= $serveur['nom']; ?>
                                        </a>
                                    </li>
                                <?php $first = false; } } ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php
                require("modele/vote.class.php");
                 $pseudo = htmlspecialchars($_GET['player']); ?>
                <div class="col col-md-12 col-lg-6 col-sm-12 mb-5">
                    <!-- Affichage des sites de vote -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Voter pour <?= $_Serveur_['General']['name']; ?></h4>
                            <div class="card-body">


                                <div class="tab-content">

                                    <?php

                                    if(Permission::getInstance()->verifPerm("connect") AND  isset($_GET['player']) AND $_Joueur_['pseudo'] == $_GET['player'] ) {
                                        echo '<script>isConnect = true;</script>';
                                    }

                                     $first=true; foreach($lectureJSON as $serveur) { ?>

                                        <div id="voter-<?= $serveur['id']; ?>" class="tab-pane fade <?= ($first) ? ' in active show' : ''; ?>" aria-expanded="<?= ($first) ? 'true' : 'false' ?>">
                                            <div class="info-page">
                                                <div class="info-content">
                                                    Bienvenue dans la catégorie de vote pour le serveur : <br>
                                                    <strong><?= $serveur['nom']; ?></strong>
                                                </div>
                                            </div>
                                            <h5 class="title-vote-listing">
                                                Liste des sites de vote <div class="vote-line"></div>
                                            </h5>
                                            <?php 
                                            $req_vote->execute(array('serveur' => $serveur['id']));
                                            while($allvote = $req_vote->fetch(PDO::FETCH_ASSOC)) {
                                                 $vote = new vote($bddConnection, $pseudo, $allvote['id']);
    
                                                  ?>

                                                 <button type="button" id="votebtn-<?php echo $allvote['id']; ?>" ></button>
                                                 <script>initVoteBouton(document.getElementById('votebtn-<?php echo $allvote['id']; ?>'), '<?php echo $pseudo; ?>', <?php echo $allvote['id']; ?>, <?php echo $vote->getLastVoteTimeMili(); ?>, <?php echo $vote->getTimeVoteTimeMili(); ?>, '<?php echo $vote->getUrl(); ?>', '<?php echo $vote->getTitre(); ?>', JSON.parse('<?php echo $vote->getAction(); ?>'));</script>
                                            <?php } ?>
                                         </div>

                                    <?php $first=false; } ?>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col col-md-12 col-lg-3 col-sm-12 mb-5">
                    <!-- Affichage des informations du joueur -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Informations</h4>
                            <div class="card-body">
                                <h5>Bonjour, <?= $pseudo ?></h5>

                                <h6>Merci d'avance pour votre vote !</h6>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Top vote -->
        <div class="row">

            <table class="table table-dark table-striped table-hover" id="baltop">
               <!-- theme/default/assets/js/voteControleur.js::updateBaltop -->
            </table>

        </div>

    </div>
</section>