<div class="columns">
    <div class="column is-one-quarter">
        <p class="title">Stickers</p>
        <div class="columns is-multiline is-mobile">
<?php   $stickers = ["michael.png", "dwight.png", "monkas.png", "thenking.png"]; 
        foreach ($stickers as $sticker) { ?>
            <div class="column is-full-desktop is-half-mobile">
                <label for="<?=$sticker?>">
                    <figure class="image is-128x128 is-clipped">
                        <img src="<?= URL . "public/images/" . $sticker ?>" alt="<?=substr($sticker, 0, -4)?>">
                    </figure>
                </label>
                <input type="radio" name="sticker" id="<?=$sticker?>" value="<?=$sticker?>">
            </div>
<?php   } ?>
        </div>
    </div>

    <div class="column is-half">
        
        <div id="notifications"></div>

        <div id="montage">
        </div>
    </div>

    <div class="column is-one-quarter">
        <p>Photos</p>
        <div id="photos">
<?php   $photos = $data->photos;
        // print_r($photos);
        foreach ($photos as $photo) { ?>
            <div class="column">
                <figure class="image is-16by9">
                    <img src="<?= URL . "public/images/montage/$photo->photo.jpeg"?>" alt="image">
                </figure>
            </div>
<?php   } ?>
        </div>
    </div>
</div>
