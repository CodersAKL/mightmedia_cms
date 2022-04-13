<div class="row d-flex" id='<?php echo $content['parentId'];?>' orderID='<?php echo $content['orderID'];?>' onclick="addClassBox(this)">
    <div class="col-lg-12 pt-4"
        <?php
            if (!empty($content['0']['style'])) {
                echo ' style = "'.$content['0']['style'].'" ';
            }
        ?>>
        <h1 class="<?php echo $content['0']['style'];?>"><?php echo $content['0']['value'];?></h1>
    </div>
</div>