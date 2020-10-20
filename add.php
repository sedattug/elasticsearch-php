<?php
include "header.php";
include "nav.php";
require_once "app/init.php";

if (!empty($_POST)) {
    if (isset($_POST['title'], $_POST['body'], $_POST['keywords'])) {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $keywords = explode(',', $_POST['keywords']);

        $indexed = $client->index([
            'index' => 'articles',
            'type' => 'article',
            'body' => [
                'title' => $title,
                'body' => $body,
                'keywords' => $keywords
            ]
        ]);

        if($indexed) {
            print_r($indexed);
        }else{
            echo "Bir hata olustu";
        }
    }
}

?>
    <section class="bg-secondary">
        <div class="container">
            <div class="row">
                <form  action="add.php" method="post" autocomplete="off">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control form-control-lg" id="title" placeholder="Title">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea type="text" name="body" class="form-control form-control-lg" id="body" placeholder="Body"
                                      rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="keywords">Keywords</label>
                            <input type="text" name="keywords" class="form-control form-control-lg" id="keywords"
                                   placeholder="Keywords">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </section>
<?php
include "footer.php";
?>