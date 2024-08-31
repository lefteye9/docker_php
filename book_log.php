<?php

require __DIR__. '/vendor/autoload.php';

$title = '';
$author = '';
$status = '';
$score = '';
$summary = '';

$reviews = [];

function validate($reviews)
{
    $errors = [];

    //書籍名が正しく入力されているかチェック
    if(!strlen($reviews['title'])){
        $errors['title'] = '書籍名を入力してください';
    }elseif ( strlen($reviews['title']) > 255){
        $errors['title'] = '書籍名は255文字以内で入力してください';
    }

    if($reviews['score'] < 1 || $reviews['score'] > 5){
        $errors['score'] = '評価は整数１～５で入力してください';
    }

    if(!in_array($reviews['status'],['未読','読んでる','読了',])){
        $errors['status'] ='未読、読んでる、読了の３つのいずれかを入力してください。';
    }

    if(!strlen($reviews['summary'])){
        $errors['summary'] = '感想を入力してください';
    }elseif(strlen($reviews['summary']) > 1000){
        $errors['summary'] = '感想は1000文字以内で入力してください';

    }

    // var_export($errors);
    return $errors;
}

function dbConnect()
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $dbHost = $_ENV['DB_HOST'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbDatabase = $_ENV['DB_DATABASE'];


    $link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase );
    if(!$link){
        echo 'Error: データベースに接続できません'.PHP_EOL;
        echo 'Debugging error' .mysqli_connect_error(). PHP_EOL;
        exit;
    }
    echo 'データベースに接続しました'.PHP_EOL;

    return $link;
}

function createReview($link)
{
    echo '読書ログを登録してください'.PHP_EOL;

    echo '書籍名：'.PHP_EOL;
    $reviews['title'] = trim( fgets(STDIN) );

    echo '著者名：'.PHP_EOL;
    $reviews['author'] = trim( fgets(STDIN) );

    echo '読書状況（未読,呼んでる,読了）：'.PHP_EOL;
    $reviews['status'] = trim( fgets(STDIN) );

    echo '評価（５点満点の整数）：'.PHP_EOL;
    $reviews['score'] = (int) trim( fgets(STDIN) );

    echo '感想：'.PHP_EOL;
    $reviews['summary'] = trim( fgets(STDIN) );

    echo '登録が完了しました。'.PHP_EOL;

    // return [
    //     'title' => $title,
    //     'author' => $author,
    //     'status' => $status,
    //     'score' => $score,
    //     'summary' => $summary,
    // ];

    //この処理を直した
    // $reviews[] = [
    //     'title' => $title,
    //     'author' => $author,
    //     'status' => $status,
    //     'score' => $score,
    //     'summary' => $summary,
    // ];




    //TODO:あとで削除する

    // echo '読書ログを表示します'.PHP_EOL;
    // echo '書籍名：'.$title.PHP_EOL;
    // echo '著者名：'.$author.PHP_EOL;
    // echo '読書状況（未読,呼んでる,読了）：'.$status.PHP_EOL;
    // echo '評価（５点満点の整数）：'.$score.'点'.PHP_EOL;
    // echo '感想：'.$summary.PHP_EOL;

    // var_export($reviews);


    $validated = validate($reviews);


    // $count = count($validated);

    // var_dump($count);

    if(count($validated) > 0){
        foreach ($validated as $error) {
            // var_export($error);
            echo $error.PHP_EOL;
        }
        return;
    }

    $sql = <<<EOT
    INSERT INTO reviews (
        title,
        author,
        status,
        score,
        summary
    ) VALUES (
        "{$reviews['title']}",
        "{$reviews['author']}",
        "{$reviews['status']}",
        "{$reviews['score']}",
        "{$reviews['summary']}"
    )
    EOT;

    $result = mysqli_query($link,$sql);
    if($result){
        echo '登録が完了しました';
    } else {
        echo 'データの追加に失敗しました'.PHP_EOL;
        echo 'Debugging Error:'.mysqli_error($link).PHP_EOL.PHP_EOL;
    }
}

function listReview($link)
{
    //  echo '登録されている読書ログを表示します' . PHP_EOL;

    // $sql = 'SELECT id, title, author, status, score, summary FROM reviews';
    // $results = mysqli_query($link, $sql);

    // while ($review = mysqli_fetch_assoc($results)) {
    //     echo '書籍名：' . $review['title'] . PHP_EOL;
    //     echo '著者名：' . $review['author'] . PHP_EOL;
    //     echo '読書状況：' . $review['status'] . PHP_EOL;
    //     echo '評価：' . $review['score'] . PHP_EOL;
    //     echo '感想：' . $review['summary'] . PHP_EOL;
    //     echo '-------------' . PHP_EOL;
    // }

    // mysqli_free_result($results);
    $sql = 'SELECT id, title, author, status, score, summary FROM reviews';

    $results = mysqli_query($link,$sql);

    $review = mysqli_fetch_assoc($results);

    // var_dump($link);

    while ($review = mysqli_fetch_assoc($results)) {


            echo '書籍名：'.$review['title'].PHP_EOL;
            echo '書籍名：'.$review['author'].PHP_EOL;
            echo '書籍名：'.$review['status'].PHP_EOL;
            echo '書籍名：'.$review['score'].PHP_EOL;
            echo '書籍名：'.$review['summary'].PHP_EOL;
            echo '-------------------------------'.PHP_EOL;


    }



        // // 読書ログを表示する
        // foreach ($reviews as $key => $review) {
        //     # code...

        //     echo '読書ログを表示します'.PHP_EOL;
        //     echo '書籍名：'.$review['title'].PHP_EOL;
        //     echo '著者名：'.$review['author'].PHP_EOL;
        //     echo '読書状況（未読,呼んでる,読了）：'.$review['status'].PHP_EOL;
        //     echo '評価（５点満点の整数）：'.$review['score'].'点'.PHP_EOL;
        //     echo '感想：'.$review['summary'].PHP_EOL;
        //     echo '--------------------------------------：'.PHP_EOL;
        // }

}

$quit = false;

$link = dbConnect();


while ($quit === false) {
    # code...

echo'1.読書ログを登録'.PHP_EOL;
echo'2.読書ログを表示'.PHP_EOL;
echo'9.アプリケーションを終了'.PHP_EOL;

echo'番号を選択してください（１，２，９）：';

$num = trim(fgets(STDIN));


// $_SESSION['title'] = $title;
// $_SESSION['book_book_author'] = $author;
// $_SESSION['book_finishied'] = $status;
// $_SESSION['score'] = $score;
// $_SESSION['summary'] = $summary;



if($num === '1'){

    $reviews[] = createReview($link);




} elseif ( $num ==='2' ){

    echo listReview($link);





} elseif ( $num === '9'){
    //アプリケーションを終了する
    $quit = true;

}
}
