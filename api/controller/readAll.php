<?php

  header('Access-Control-Allow-Origin: *');
  // header('Content-Type: application/json');

  include_once('../config/database.php');
  include_once('../models/PostRooms.php');

  $database = new Database();
  $db = $database->connect();

  $postRooms = new PostRooms($db);

  $results = $postRooms->readAll();

  $num = $results->rowCount();

  if($num > 0){

    $posts_arr = array();
    $posts_arr['data'] = array();

    while($row = $results->fetch(PDO::FETCH_ASSOC)){
      extract($row);

      // var_dump($row);
      // die();
      $postRooms->id = $id;
      $imgRes = $postRooms->readImagesByRoomId();
      $imgNum = $imgRes->rowCount();

      $posts_imgArr = array();
      $posts_imgArr['images'] = array();

      if($imgNum > 0){

        while($imgRow = $imgRes->fetch(PDO::FETCH_ASSOC)){
          // extract($imgRow);
    
          $img_post_item = array(
            'id' => $imgRow['id'],
            'room_id' => $imgRow['room_id'],
            'image' => $imgRow['image']
          );
    
          array_push($posts_imgArr['images'], $img_post_item);
        }
      }else {
        array_push($posts_imgArr['images'], 'No images!');
      }


      $post_item = array(
        'id' => $id,
        'category_id' => $category_id,
        'category_name' => $category_name,
        'price' => $price,
        'components' => $components,
        'created_at' => $created_at,
        'images' => $posts_imgArr['images']
      );

      array_push($posts_arr['data'], $post_item);
    }

    echo json_encode($posts_arr);
  }else{

    echo json_encode(
      array('message' => 'No records yet')
    );
  }
