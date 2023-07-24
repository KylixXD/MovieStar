<?php
require_once("templates/header.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);
$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
    $message->setMessage("O filme não foi encontrado!", "error", "index.php");
} else {
    $movie = $movieDao->findById($id);
    //Verifica se o filme existe
    if (!$movie) {
        $message->setMessage("O filme não foi encontrado!", "error", "index.php");
    }
}
?>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1><?= $movie->title ?></h1>
                <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
                <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="create">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Digite o nome do filme que quer adicionar">
                    </div>
                    <div class="form-group">
                        <label for="image">Imagem do Filme:</label>
                        <input type="file" class="form-control-file" name="image" id="image">
                    </div>
                    <div class="form-group">
                        <label for="length">Duração:</label>
                        <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Ação">Ação</option>
                            <option value="Aventura">Aventura</option>
                            <option value="Suspense">Suspense</option>
                            <option value="Terror">Terror</option>
                            <option value="Romance">Romance</option>
                            <option value="Animação">Animação</option>
                            <option value="Comédia">Comédia</option>
                            <option value="Drama">Drama</option>
                            <option value="Ficção científica">Ficção científica</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="trailer">Trailer do filme:</label>
                        <input type="text" class="form-control" name="trailer" id="trailer" placeholder="Insira o link do trailer">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição do filme:</label>
                        <textarea name="description" id="description" rows="5" class="form-control" placeholder="Descreva o filme..."></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Adicionar filme">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require_once("templates/footer.php");
?>