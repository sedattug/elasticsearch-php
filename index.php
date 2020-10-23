<?php
include "header.php";
include "nav.php";
require_once "app/init.php";

if (isset($_GET['q'])) {
    $result = [];
    $q = $_GET['q'];

    if (isset($_GET['pagingSize'])) {
        $paging_size = $_GET['pagingSize'];
    } else {
        $paging_size = 10;
    }

    if (isset($_GET['pagingOffset'])) {
        $paging_offset = $_GET['pagingOffset'];
    } else {
        $paging_offset = $paging_offset_link = 0;
    }

    $page = (integer)(($paging_offset + $paging_size) / $paging_size);
    $paging_offset_link = (($page * $paging_size) - $paging_size);
    $paging_size_array = [10, 20, 50];

    $params = [
        'index' => 'text_container',
        'from' => $paging_offset,
        'size' => $paging_size,
        'body' => [
            'query' => [
                'multi_match' => [
                    'query' => $q,
                    'fuzziness' => 0 //wrong written char count
                ]
            ]
        ]
    ];
    $starttime = microtime(true);

    $query = $client->search($params);

    $endtime = microtime(true);

    if ($query['hits']['total'] > 0) {
        $result_text = '<div class="alert alert-primary" role="alert"><b>' . $query['hits']['total'] . '</b> sonuç bulundu. ( ' . substr($endtime - $starttime, 0, 5) . ' sn. )</div>';
        $total_result = $query['hits']['total'];
        $result = $query['hits']['hits'];

        //echo '<pre>' , print_r($result), '</pre>';
    }
}
?>
    <!-- Masthead -->
    <header class="masthead text-white text-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h1 class="mb-5">Simple Elastic Search Example</h1>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <form action="index.php" method="get" autocomplete="off">
                        <div class="form-row">
                            <div class="col-12 col-md-9 mb-2 mb-md-0">
                                <input type="text" name="q" class="form-control form-control-lg"
                                       placeholder="Search for something..." value="<?php echo isset($q) ? $q : ''; ?>">
                            </div>
                            <div class="col-12 col-md-3">
                                <button type="submit" class="btn btn-block btn-lg btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
<?php
if (isset($result_text)) {
    echo $result_text;
}
if (isset($result) && is_array($result) && count($result) > 0) { ?>
    <div class="list-group">
        <?php foreach ($result as $row) { ?>
            <a href="detail.php?q=<?php echo $q; ?>&id=<?php echo $row['_id']; ?>"
               class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?php echo $row['_source']['title']; ?></h5>
                    <small><?php echo $row['_source']['page_num']; ?>. sayfa</small>
                </div>
            </a>
        <?php } ?>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            for ($p = 1; $p <= (integer)ceil($total_result / $paging_size); $p++) {
                $paging_offset_link = (($p * $paging_size) - $paging_size);
                if ($page == $p) { ?>
                    <li class="page-item active">
                        <a class="page-link" href="#"><?php echo $p; ?></a>
                    </li>
                    <?php
                } else { ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="index.php?q=<?php echo $q; ?>&pagingOffset=<?php echo $paging_offset_link; ?>&pagingSize=<?php echo $paging_size; ?>"><?php echo $p; ?></a>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </nav>
    Sayfada
    <div class="btn-group" role="group" aria-label="Basic example">
        <?php foreach ($paging_size_array as $page_size) { ?>
            <?php if ($page_size == $paging_size) { ?>
                <a class="btn btn-primary" href="#" role="button"><?php echo $page_size; ?></a>
            <?php } else { ?>
                <a class="btn btn-outline-primary"
                   href="index.php?q=<?php echo $q; ?>&pagingOffset=<?php echo $paging_offset_link; ?>&pagingSize=<?php echo $page_size; ?>"
                   role="button"><?php echo $page_size; ?></a>
            <?php } ?>
        <?php } ?>
    </div>
    sonuç göster
    <?php
} else { ?>
    <div class="alert alert-dark text-center" role="alert">
        No data available.
    </div>
<?php } ?>

<?php
include "footer.php";
?>