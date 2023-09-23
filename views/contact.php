<?php

use app\core\form\TextareaField;

/** @var $this \app\core\View */
/** @var $model \app\models\ContactForm */

$this->title = 'Contact';
?>
<h1>Contact us</h1>
<?php $form = \app\core\form\Form::begin('', 'post'); ?>
<?php echo $form->field($model, 'subject'); ?>
<?php echo $form->field($model, 'email'); ?>
<?php echo new TextareaField($model, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>

<?php \app\core\form\Form::end(); ?>
<!-- <form action="#" method="post">
    <div class="form-group">
        <label>Subject</label>
        <input type="text" name="subject" class="form-control">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name='email' class="form-control">
    </div>
    <div class="form-group">
        <label>body</label>
        <textarea name='body' class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form> -->