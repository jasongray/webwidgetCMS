<?php if (isset($m['images']) && !empty($m['images'])) {
    $_image = $this->Resize->image('backgrounds' . DS . $m['images']['0'], 1300, 800, true, array(), true);
} else {
    $_image = '';
} ?>
<header class="header main-header parallax-bg <?php echo $m['class'];?>" data-parallax="scroll" data-image-src="<?php echo $_image;?>" data-speed="0.4" >
    <div class="primary-trans-bg">
        <div class="container">
            <div class="outer">
                <div class="inner">
                    <?php echo $m['content'];?>
                </div>
            </div>
        </div>
    </div>
</header>