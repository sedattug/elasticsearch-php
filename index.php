<?php
include "header.php";
include "nav.php";
require_once "app/init.php";

if (isset($_GET['q'])) {
    $result = [];
    $q = $_GET['q'];

    $params = [
        'index' => 'articles',
        'body' => [
            'query' => [
                'multi_match' => [
                    'query' => $q,
                    'fuzziness' => 1 //wrong written char count
                ]
            ]
        ]
    ];

    $query = $client->search($params);

    if ($query['hits']['total'] > 0) {
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
    <div class="list-group">
        <?php if (isset($result) && is_array($result) && count($result) > 0) {
            foreach ($result as $row) {
                ?>
                <a href="javascript:void(0);" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo $row['_source']['title']; ?></h5>
                        <small>3 days ago</small>
                    </div>
                    <p class="mb-1"><?php echo $row['_source']['body']; ?></p>
                    <p>
                        <?php if (isset($row['_source']['keywords']) && is_array($row['_source']['keywords']) && count($row['_source']['keywords']) > 0) { ?>
                            <?php foreach ($row['_source']['keywords'] as $keyword) { ?>
                                <span class="badge badge-info"><?php echo $keyword; ?></span>
                            <?php } ?>
                        <?php } ?>
                    </p>
                </a>
                <?php
            }
        } else { ?>
            <div class="alert alert-dark text-center" role="alert">
                No data available.
            </div>
        <?php
        }
        ?>
    </div>

    <!-- Icons Grid -->
    <section class="features-icons bg-light text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                        <div class="features-icons-icon d-flex">
                            <i class="icon-screen-desktop m-auto text-primary"></i>
                        </div>
                        <h3>Fully Responsive</h3>
                        <p class="lead mb-0">This theme will look great on any device, no matter the size!</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                        <div class="features-icons-icon d-flex">
                            <i class="icon-layers m-auto text-primary"></i>
                        </div>
                        <h3>Bootstrap 4 Ready</h3>
                        <p class="lead mb-0">Featuring the latest build of the new Bootstrap 4 framework!</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-0 mb-lg-3">
                        <div class="features-icons-icon d-flex">
                            <i class="icon-check m-auto text-primary"></i>
                        </div>
                        <h3>Easy to Use</h3>
                        <p class="lead mb-0">Ready to use with your own content, or customize the source files!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Showcases -->
    <section class="showcase">
        <div class="container-fluid p-0">
            <div class="row no-gutters">

                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                     style="background-image: url('img/bg-showcase-1.jpg');"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2>Fully Responsive Design</h2>
                    <p class="lead mb-0">When you use a theme created by Start Bootstrap, you know that the theme will
                        look great on any device, whether it's a phone, tablet, or desktop the page will behave
                        responsively!</p>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col-lg-6 text-white showcase-img"
                     style="background-image: url('img/bg-showcase-2.jpg');"></div>
                <div class="col-lg-6 my-auto showcase-text">
                    <h2>Updated For Bootstrap 4</h2>
                    <p class="lead mb-0">Newly improved, and full of great utility classes, Bootstrap 4 is leading the
                        way in mobile responsive web development! All of the themes on Start Bootstrap are now using
                        Bootstrap 4!</p>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                     style="background-image: url('img/bg-showcase-3.jpg');"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2>Easy to Use &amp; Customize</h2>
                    <p class="lead mb-0">Landing Page is just HTML and CSS with a splash of SCSS for users who demand
                        some deeper customization options. Out of the box, just add your content and images, and your
                        new landing page will be ready to go!</p>
                </div>
            </div>
        </div>
    </section>
<?php
include "footer.php";
?>