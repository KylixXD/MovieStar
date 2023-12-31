<?php 

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDAO($conn, $BASE_URL);

    //tratar erros
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Resgata o tipo do formulário
    $type = filter_input(INPUT_POST, "type");

    //atualizar usuario
    if ($type === "update") {
        //Resgate dados do usuário
        $userData = $userDao->verifyToken();
        //Receber os dados do formulário
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $bio = filter_input(INPUT_POST, "bio");

        //Criar um novo objeto usuário
        $user = new User();

        //Preencher os dados do usuário
        $userData->name = $name;
        $userData->lastname = $lastname;
        $userData->email = $email;
        $userData->bio = $bio;

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
      
              $imageName = $user->imageGenerateName($ext);
              imagejpeg($imageFile, "./img/users/" . $imageName, 100);
              $userData->image = $imageName;
      
            } else {
      
              $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
      
            }
          }
     
        $userDao->update($userData);

    } else if ($type === "changepassword") {
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
        $userData = $userDao->verifyToken();
        $id = $userData->id;

        if ($password == $confirmpassword) {
            $user = new User();

            $finalPassword = $user->generatePassword($password);
            $user->password = $finalPassword;
            $user->id = $id;

            $userDao->changePassword($user);

        } else {
            $message->setMessage("As senhas não são iguais!", "error","back");
        }

    } else {
        $message->setMessage("Informações inválidas!", "error","index.php");
    }

?>