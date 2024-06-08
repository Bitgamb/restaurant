<div class="box2" id="box2">
    <h1><b><i>Gallery</i></b></h1>
    <div class="container1">
        <div class="container text-center">
            <div class="row">
                <?php
                    for ($i = 1; $i <= 6; $i++) {
                        echo "<div class='col'>
                                <div class='card' style='width: 21rem;'>
                                    <img src='assets/img{$i}.jpeg' class='card-img-top' alt='...'>
                                </div>
                              </div>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>
