<?php 

    class User {
        public $id;
        public $name;
        public $lastname;
        public $email;
        public $password;
        public $image;
        public $bio;
        public $token;

        public function getFullName($user){
            return $user->name . " " . $user->lastname;
        }

        public function generateToken(){
            return bin2hex(random_bytes(50)); //Fazer um embaralhamento duplo 
        }

        public function generatePassword($password){
            return password_hash($password, PASSWORD_DEFAULT);
        }

        public function imageGenerateName() {
            return bin2hex(random_bytes(60)) . ".jpg";
        }
        
    }

    interface UserDAOInterface {
        public function buildUser($data);
        public function create(User $user,$authUser = false); //Criar o usuário
        public function update(User $user,$redirect = true); // Atualizar o usuário 
        public function verifyToken($protected = false);  
        public function setTokenToSession($token , $redirect = true); // "Dar token para as sessões" i think
        public function authenticateUser($email , $password); //Autenticação de usuário //Esse metodo e o de cima coompoe a autenticação completa
        public function findByEmail($email); // Verificação de usuario por email 
        public function findByToken($token); // Verificação de usuario por token 
        public function findById($id);  // Verificação de usuario por id 
        public function destroyToken();
        public function changePassword(User $user); //Mudar a senha do usuario 
    }

?>