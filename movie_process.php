<?php 

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Movie.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDAO($conn, $BASE_URL);
    $movieDao = new MovieDAO($conn, $BASE_URL);

    // Resgatar o tipo do formulário
    $type = filter_input(INPUT_POST,"type");

    //Resgatar dados do usuário
    $userData = $userDao->verifyToken();

    if($type === "create"){
        //Receber os dados do formulário
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        $movie = new Movie();

        //Validação mínima de dados
        if(!empty($title) && !empty($description) && !empty($category)){
            $movie->title = $title;
            $movie->description = $description;
            $movie->trailer = $trailer;
            $movie->category = $category;
            $movie->length = $length;
            $movie->users_id = $userData->id;

            // Upload da imagem
                if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
                
                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];
            
                    //PEGANDO EXTENSÃO DO ARQUIVO
                    $ext = strtolower(substr($image['name'],-4));
            
                    // Checagem de tipo de imagem
                    if(in_array($image["type"], $imageTypes)) {
            
                    if($ext == ".jpg") {
            
                        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            
                    } else if($ext == ".png") {
            
                        $imageFile = imagecreatefrompng($image["tmp_name"]);
            
                    } else {
            
                        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                        
                    }
            
                    $imageName = $movie->imageGenerateName($ext);
                    imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
                    $movie->image = $imageName;
            
                    } else {
            
                    $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            
                    }
                }
            
                $movieDao->create($movie);

        } else {
            $message->setMessage("Você precisa adicionar pelo menos:Título,descrição e categoria", "error", "back"); 
        }

    } else if($type === "delete"){
        //Receber os dados do form
        $id = filter_input(INPUT_POST, "id");
        $movie = $movieDao->findById($id);

        if($movie){
            //Verificar se o filme é do usuário
            if($movie->users_id === $userData->id){
                $movieDao->destroy($movie->id);
                
            } else {
                $message->setMessage("Informações inválidas!", "error", "index.php");
            }

        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        }
    
    } else if($type === "update"){
        //Receber os dados do formulário
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");
        $id = filter_input(INPUT_POST, "id");

        $movieData = $movieDao->findById($id);

        if($movieData){
            //Verificar se o filme é do usuário
            if($movieData->users_id === $userData->id){
                //Validação mínima de dados
                if(!empty($title) && !empty($description) && !empty($category)){
                    //Edição do filme
                    $movieData->title = $title;
                    $movieData->description = $description;
                    $movieData->trailer = $trailer;
                    $movieData->category = $category;
                    $movieData->length = $length;

                // Upload da imagem
                if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
                
                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];
            
                    //PEGANDO EXTENSÃO DO ARQUIVO
                    $ext = strtolower(substr($image['name'],-4));
                    $extmaior = strtolower(substr($image['name'],-5));
            
                    // Checagem de tipo de imagem
                    if(in_array($image["type"], $imageTypes)) {
            
                        if($ext == ".jpg" or $extmaior == ".jpeg") {
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                
                        } else if($ext == ".png") {
                
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                            
                        }else {
                
                            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                
                        }
            
                    $imageName = $movieData->imageGenerateName($ext);
                    imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
                    $movieData->image = $imageName;
            
                    } else {
            
                    $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            
                    }
                }
                $movieDao->update($movieData);
                } else {
                    $message->setMessage("Você precisa adicionar pelo menos:Título,descrição e categoria", "error", "back");  
                }
            } else {
                $message->setMessage("Informações inválidas!", "error", "index.php");
            }
        } else {
            $message->setMessage("Informações inválidas!", "error", "index.php");
        }

    }else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }

?>