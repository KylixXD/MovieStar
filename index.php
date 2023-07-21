<?php
require_once("templates/header.php");
require_once("dao/MovieDAO.php");

//Dao dos filmes
$movieDao = new MovieDAO($conn, $BASE_URL);

$latestMovies = $movieDao->getLatestMovies();
$fictionMovies = $movieDao->getMoviesByCategory("Ficção científica");
$actionMovies = $movieDao->getMoviesByCategory("Ação");
?>
<div id="main-container" class="container-fluid">
    <h2 class="section-title">Filmes novos</h2>
    <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</p>
    <div class="movies-container">
        <?php foreach ($latestMovies as $movie) : ?>
            <?php require("templates/movie_card.php");?>
        <?php endforeach; ?>
        <?php if(count($latestMovies) === 0):?>
            <p class="empty-list">Ainda não há filmes cadastrados!</p>
        <?php endif;?>
    </div>
</div>
<div id="main-container" class="container-fluid">
    <h2 class="section-title">Ficção científica</h2>
    <p class="section-description">Veja os mais bem avaliados filmes de ficção científica</p>
    <div class="movies-container"></div>
    <?php foreach ($fictionMovies as $movie) : ?>
        <?php require("templates/movie_card.php");?>
    <?php endforeach; ?>
    <?php if(count($latestMovies) === 0):?>
        <p class="empty-list">Ainda não há filmes de ficção científica cadastrados!</p>
    <?php endif;?>
</div>
<div id="main-container" class="container-fluid">
    <h2 class="section-title">Ação</h2>
    <p class="section-description">Veja os mais bem avaliados filmes de Ação</p>
    <div class="movies-container"></div>
    <?php foreach ($actionMovies as $movie) : ?>
        <?php require("templates/movie_card.php");?>
    <?php endforeach; ?>
    <?php if(count($latestMovies) === 0):?>
        <p class="empty-list">Ainda não há filmes de Ações cadastrados!</p>
    <?php endif;?>
</div>
<?php
require_once("templates/footer.php");
?>