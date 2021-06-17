<?php

  header('Access-Control-Allow-Origin: *');
  // header('Content-Type: application/json');

  include_once('../config/database.php');
  include_once('../models/PostRooms.php');

  $database = new Database();
  $db = $database->connect();

  $postRooms = new PostRooms($db);

  $postRooms->id = isset($_GET['id']) ? $_GET['id'] : die();

  $postRooms->readSingle();

  $results = $postRooms->readImagesByRoomId();

//   var_dump($results->rowCount());
//   die();

  $num = $results->rowCount();

  $posts_imgArr = array();
  $posts_imgArr['images'] = array();

  if($num > 0){

    while($row = $results->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $post_item = array(
          'id' => $id,
          'room_id' => $room_id,
          'image' => $image
        );
  
        array_push($posts_imgArr['images'], $post_item);
      }
  }
  else {
    array_push($posts_imgArr['images'], 'No images!');
  }

  $posts_arr = array(
      'id' => $postRooms->id,
      'category_id' => $postRooms->category_id,
      'catergory_name' => $postRooms->category_name,
      'price' => $postRooms->price,
      'components' => $postRooms->components,
      'added_by' => $postRooms->added_by,
      'created_at' => $postRooms->created_at,
      'images' => $posts_imgArr['images']
  );

    echo json_encode($posts_arr);
//   }else{

//     echo json_encode(
//       array('message' => 'Not found!')
//     );
//   }
