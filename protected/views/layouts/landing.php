<?php $this->beginContent('//layouts/main'); ?>

<script>
    $(function(){
        $('#c3ForgetPassword').click(function(){
           ic.open(baseUrl+'/site/resetPassword');
        });
    });
</script>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner" style="height:60px">
        <div class="container">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/" class="brand" style="padding-top:13px"><h1>Casting3</h1></a>
            <div class="nav-collapse">
                    <form class="pull-right form-inline" id="LoginForm" action="<?php echo Yii::app()->request->baseUrl; ?>/" method="post">
                        <input class="input-small" style="width:150px" placeholder="Email" name="LoginForm[email]" id="LoginForm_email" type="text" />                    
                        <input class="input-small" placeholder="Password" name="LoginForm[password]" id="LoginForm_password" type="password" />                    
                        <button id="submit_login" class="btn" type="submit"><i class="icon-arrow-right"></i> Log in</button>
                        <br/>
                        <a href=""><h6 id="c3ForgetPassword">Forgot Your Password?</h6></a>
                    </form>  
                    
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:80px" id="page">
    <?php echo $content; ?>
</div>

<?php $this->endContent(); ?>