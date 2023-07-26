<?php 

    class Review {
        public $id;
        public $rating;
        public $review;
        public $users_id;
        public $movies_id;
        public $user;
    }

    interface ReviewDAOInterface {
        public function buildReview($data); //buildar a review
        public function create(Review $review); //Criar a review
        public function getMoviesReview($id); //Pegar as reviews de filme
        public function hasAlreadyReviewed($id, $userId); //Verificar se o usuário já fez a review 
        public function getRatings($id); //pegar a nota do filme

    }