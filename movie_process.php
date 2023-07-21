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

            // //Upload de imagem (Será que vai funcionar?)
            // if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
            //     $image = $_FILES["image"];
            //     $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            //     $jpgArray = ["image/jpeg", "image/jpg"];
                
            //     //Checando tipo da imagem
            //     if(in_array($image["type"], $imageTypes)){
            //         //Checa se imagem é jpg
            //         if(in_array($image["type"], $jpgArray)){
            //             $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            //         }else{
            //             $imageFile = imagecreatefrompng($image["tmp_name"]);
            //         }

            //         $imageName = $movie->imageGenerateName();

            //         imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

            //         $movie->image = $imageName;

            //     } else {
            //         $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            //     }

            // } 
            // $movieDao->create($movie);

            // Upload da imagem
                if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
                
                    $image = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif" , "image/bmp"];
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


    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }

?>