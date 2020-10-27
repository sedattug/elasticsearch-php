<?php
include "header.php";
include "nav.php";
require_once "app/init.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $q = $_GET['q'];
    //echo $id;
    $params = [
        'index' => 'text_container',
        'type' => 'text',
        'id'    => $id
    ];

    $query = $client->get($params);

    //echo '<pre>' , print_r($query) , '</pre>';

    if($query['found'] === true) {
        $result = $query['_source'];
    }
}

?>
    <div class="row">

        <div class="col-md-12">
            <img class="img-fluid" src="http://placehold.it/1366x500" alt="">
        </div>

        <div class="col-md-12">
            <?php if(isset($result)) {?>
            <h3 class="my-3"><?php echo $result['title'];?></h3>
            <p><?php echo str_replace($q, '<mark>' . $q . '</mark>', $result['page_content']);?></p>
            <h3 class="my-3">Project Details</h3>
            <ul>
                <li>Sayfa Numarası : <a target="_blank" href="http://localhost:8888/#page=<?php echo $result['page_num']; ?>"><?php echo $result['page_num']; ?></a></li>
            </ul>
        <?php } else {?>
                <p>No data available.</p>
        <?php }?>
        </div>

    </div>
    <!-- /.row -->
<?php
include "footer.php";
?>