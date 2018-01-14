
    <div class="container column">
        <div id="notifications"></div>

        <div id="modal" class="modal">
            <div class="modal-background"></div>
            <div id="modal-content" class="modal-content" style="background: white; padding: 10px;">
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
        </div>

        <div id="photos" class="columns is-multiline is-flex">
<?php   $photos = $data->photos;
        foreach ($photos as $photo) { ?>
            <div id="<?=$photo->photo?>" class="column is-6-mobile is-4-tablet">
                <figure class="image is-16by9" onclick="getImage('<?=$photo->photo?>')">
                    <img src="<?= URL . "public/images/montage/$photo->photo.jpeg"?>" alt="image">
                </figure>
            </div>
<?php   } ?>
        </div>
        <div id="loadmore" class="column" style="text-align:center;">
            <button class="button is-primary is-centered" onclick="loadMore()">Load more...</button>
        </div>
    </div>

