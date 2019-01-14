<div class="row d-flex" id='<?php echo $content['parentId'];?>' orderID='<?php echo $content['orderID'];?>' data-filename = 'form_blocks' data-filename = 'registration_form' onclick="addClassBox(this)">
    <div class="card">
        <div class="header">
            <h2><?php echo $content[0]['value'];?></h2>
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);" class=" waves-effect waves-block"><?php echo $content[1]['value'];?></a></li>
                        <li><a href="javascript:void(0);" class=" waves-effect waves-block"><?php echo $content[2]['value'];?></a></li>
                        <li><a href="javascript:void(0);" class=" waves-effect waves-block"><?php echo $content[3]['value'];?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <form id="form_validation" method="POST" novalidate="novalidate">
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="<?php echo $content[4]['value'];?>" required="" aria-required="true">
                        <label class="form-label"><?php echo $content[4]['value'];?></label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="<?php echo $content[5]['value'];?>" required="" aria-required="true">
                        <label class="form-label"><?php echo $content[5]['value'];?></label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="<?php echo $content[6]['value'];?>" required="" aria-required="true">
                        <label class="form-label"><?php echo $content[6]['value'];?></label>
                    </div>
                </div>
                <div class="form-group">
                    <input type="radio" name="gender" id="<?php echo $content[7]['value'];?>" class="with-gap">
                    <label for="<?php echo $content[7]['value'];?>"><?php echo $content[7]['value'];?></label>

                    <input type="radio" name="gender" id="<?php echo $content[8]['value'];?>" class="with-gap">
                    <label for="<?php echo $content[8]['value'];?>" class="m-l-20"><?php echo $content[8]['value'];?></label>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea name="description" cols="30" rows="5" class="form-control no-resize" required="" aria-required="true"></textarea>
                        <label class="form-label">Description</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" required="" aria-required="true">
                        <label class="form-label">Password</label>
                    </div>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="checkbox" name="checkbox">
                    <label for="checkbox"><?php echo $content[9]['value'];?></label>
                </div>
                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
            </form>
        </div>
    </div>
</div>