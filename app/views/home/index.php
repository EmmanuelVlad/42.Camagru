<div class="columns">
    <div class="column is-one-quarter">
        <p>Stickers</p>
<?php   $stickers = ["michael.png", "dwight.png", "monkas.png", "thenking.png"]; 
        foreach ($stickers as $sticker) { ?>
            <div class="column">
                <figure class="image is-128x128 is-clipped">
                    <img src="<?= URL . "public/images/" . $sticker ?>" alt="<?=substr($sticker, 0, -4)?>">
                </figure>
            </div>
<?php   } ?>
    </div>

    <div class="column is-half">
        
        <div id="notifications"></div>

        <div id="montage">
        </div>
    </div>

    <div class="column is-one-quarter">
    <p>Photos</p>
<?php   $photos = $data->photos;
        // print_r($photos);
        foreach ($photos as $photo) { ?>
            <div class="column">
                <figure class="image is-16by9">
                    <img src="<?=$photo->photo?>" alt="image">
                </figure>
            </div>
<?php   } ?>
    </div>
</div>
