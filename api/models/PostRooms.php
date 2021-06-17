<?php
	class PostRooms {
		private $conn;
		private $table = 'rooms';

		public $id;
		public $category_id;
		public $category_name;
		public $price;
    public $components;
    public $created_at;
    public $added_by;

    public function __construct($db){
      $this->conn = $db;
    }

    public function readAll(){
      $query = 'SELECT 
                c.name as category_name,
                p.id,
                p.category_id,
                p.price,
                p.components,
                p.created_at,
                p.added_by
                FROM 
                '.$this->table.' p
                LEFT JOIN 
                  categories c ON p.category_id = c.id
                ORDER BY
                  p.created_at DESC';

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }

    public function readAllImages(){
      $query = 'SELECT *
                FROM 
                  roomimages 
                ORDER BY
                  p.created_at DESC';

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }

    public function readSingle(){
      $query = 'SELECT 
                c.name as category_name,
                p.id,
                p.category_id,
                p.price,
                p.components,
                p.created_at,
                p.added_by
                FROM 
                '.$this->table.' p
                LEFT JOIN 
                  categories c ON p.category_id = c.id
                WHERE 
                  p.id = ?
                LIMIT 0,1';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(1, $this->id);

      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $this->id = $row['id'];
      $this->category_id = $row['category_id'];
      $this->category_name = $row['category_name'];
      $this->price = $row['price'];
      $this->components = $row['components'];
      $this->added_by = $row['added_by'];
      $this->created_at = $row['created_at'];
      // return $stmt;
    }

    public function readImagesByRoomId(){
      $query = 'SELECT 
                  *
                FROM 
                  roomimages
                WHERE 
                  room_id = ?';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(1, $this->id);

      $stmt->execute();

      // $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // $this->image_id = $row['id'];
      // $this->room_id = $row['room_id'];
      // $this->image = $row['image'];
      return $stmt;
    }

    public function create(){

      $query = 'INSERT into '
                . $this->table . '
                SET 
                  category_id = :category_id,
                  price = :price,
                  components = :components,
                  added_by = :added_by';
      
      $stmt = $this->conn->prepare($query);

      //clean data
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));
      $this->price = htmlspecialchars(strip_tags($this->price));
      $this->components = htmlspecialchars(strip_tags($this->components));
      $this->added_by = htmlspecialchars(strip_tags($this->added_by));
      

      //bind data
      $stmt->bindParam(':category_id', $this->category_id);
      $stmt->bindParam(':price', $this->price);
      $stmt->bindParam(':components', $this->components);
      $stmt->bindParam(':added_by', $this->added_by);

      if($stmt->execute()){
        return true;
      }

      echo ($stmt->error);
      return false;
    }

    public function createRoomImage(){
      $query = 'INSERT into roomImages
                SET 
                  room_id = :room_id,
                  imageLocation = :imageLocaton';
      
      $stmt = $this->conn->prepare($query);

      //clean data
      $this->room_id = htmlspecialchars(strip_tags($this->id));
      $this->imageLocation = htmlspecialchars(strip_tags($this->imageLocation));
      
      //bind data
      $stmt->bindParam(':room_id', $this->room_id);
      $stmt->bindParam(':imageLocation', $this->imageLocation);

      if($stmt->execute()){
        return true;
      }

      echo ($stmt->error);
      return false;
    }


	}
