<?php
\modava\contact\assets\ContactAsset::register($this);
\modava\contact\assets\MyContactAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/main.php'); ?>
<?php echo $content ?>
<?php $this->endContent(); ?>
